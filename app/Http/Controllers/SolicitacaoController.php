<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use App\Models\Roteiro;
use App\Models\Insumo;
use App\Models\Equipamento;
use App\Models\Solicitacao;
use App\Models\User;
use Illuminate\Http\Request;

class SolicitacaoController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Solicitacao::query();

        $query->with(['laboratorio', 'roteiro', 'user', 'autorizante']);

        if ($user->nivel == User::NIVEL_PROFESSOR) {
            // Professor: Vê TODAS as suas solicitações
            $query->where('user_id', $user->id);
        } elseif ($user->nivel == User::NIVEL_COORDENADOR_CURSO) {
            // Coord. Curso: Vê PENDENTES atribuídas a ele (status 1) OU JÁ PROCESSADAS POR ELE (status 2, 3, 4)
            $query->where(function ($q) use ($user) {
                // 1. PENDENTES atribuídas a ele (Status 1)
                $q->where('status', Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO)
                    ->where('coordenador_curso_id', $user->id)

                    // 2. OU JÁ PROCESSADAS POR ELE: Status 2 (encaminhado), Status 3 (aprovado), Status 4 (recusado).
                    // Usa coordenador_curso_id para rastrear quem do curso processou
                    ->orWhere(function ($orQ) use ($user) {
                        $orQ->whereIn('status', [
                            Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB, // Status 2
                            Solicitacao::STATUS_APROVADA,                   // Status 3
                            Solicitacao::STATUS_RECUSADA                    // Status 4
                        ])
                            ->where('coordenador_curso_id', $user->id);
                    });
            });
        }elseif ($user->nivel == User::NIVEL_COORDENADOR_LABORATORIO) {
            // Coord. Lab: Vê PENDENTES (status 2) OU JÁ APROVADAS (autorizante_id = user_id)
            // Agora também filtra por solicitações onde ele foi selecionado como coordenador_lab_id
            $query->where(function ($q) use ($user) {
                $q->where('status', Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB)
                    ->where('coordenador_lab_id', $user->id)
                    ->orWhere('autorizante_id', $user->id);
            });
        }
        // Para outros níveis (Técnico), sem filtro: ele verá todas as solicitações.

        // Ordena para que as PENDENTES (menores status) apareçam primeiro,
        // garantindo que as que precisam de ação estejam no topo.
        $todasSolicitacoes = $query->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->get(); // Se quiser paginação, mude para ->paginate(10);

        // Agrupa por Professor (user) -> Unidade Curricular (campo unidade_curricular ou unidade_curricular do roteiro)
        $solicitacoesPorProfessor = $todasSolicitacoes->groupBy('user_id')->map(function ($solicitacoesPorProf) {
            return [
                'professor' => $solicitacoesPorProf->first()->user,
                'disciplinas' => $solicitacoesPorProf->groupBy(function ($sol) {
                    $unidadeCurricular = $sol->unidade_curricular ?? $sol->roteiro?->unidade_curricular ?? 'Sem Unidade Curricular';

                    // Normalizar nome da unidade curricular para agrupamento inteligente
                    if ($unidadeCurricular !== 'Sem Unidade Curricular') {
                        $unidadeCurricular = $this->normalizarNomeDisciplina($unidadeCurricular);
                    }

                    return $unidadeCurricular;
                })->map(function ($solicitacoesPorDis) {
                    // Pegar o nome original da primeira solicitação para exibição
                    $nomeOriginal = $solicitacoesPorDis->first()->unidade_curricular ?? $solicitacoesPorDis->first()->roteiro?->unidade_curricular ?? 'Sem Unidade Curricular';

                    return [
                        'nome_disciplina' => $nomeOriginal,
                        'solicitacoes' => $solicitacoesPorDis->sortBy('status')
                    ];
                })
            ];
        });

        return view('solicitacoes.index', compact('solicitacoesPorProfessor', 'todasSolicitacoes'));
    }

    public function create()
    {
        // Verifica se é professor
        if (auth()->user()->nivel != User::NIVEL_PROFESSOR) {
            abort(403, 'Apenas professores podem criar solicitações.');
        }

        $laboratorios = Laboratorio::all();
        $insumos = Insumo::all();
        $equipamentos = Equipamento::all();

        // Buscar coordenadores disponíveis
        $coordenadoresCurso = User::where('nivel', User::NIVEL_COORDENADOR_CURSO)->get();
        $coordenadoresLab = User::where('nivel', User::NIVEL_COORDENADOR_LABORATORIO)->get();

        return view('solicitacoes.create', compact('laboratorios', 'insumos', 'equipamentos', 'coordenadoresCurso', 'coordenadoresLab'));
    }

    public function store(Request $request)
    {
        // 1. Verifica se é professor
        if (auth()->user()->nivel != User::NIVEL_PROFESSOR) {
            abort(403, 'Apenas professores podem criar solicitações.');
        }

        // 2. Validação das arrays aninhadas
        $request->validate([
            'coordenador_curso_id'             => 'required|exists:users,id',
            'coordenador_lab_id'               => 'required|exists:users,id',
            'agendamentos'                      => 'required|array|min:1',
            'agendamentos.*.laboratorio_id'     => 'required|exists:laboratorios,id',
            'agendamentos.*.unidade_curricular' => 'required|string|max:255',
            'agendamentos.*.data_solicitacao'   => 'required|date|after_or_equal:today',
            'agendamentos.*.horario_inicio'     => 'required|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'agendamentos.*.horario_fim'        => 'required|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'agendamentos.*.insumos_id'         => 'nullable|array',
            'agendamentos.*.insumos_id.*'       => 'exists:insumos,id',
            'agendamentos.*.equipamentos_id'    => 'nullable|array',
            'agendamentos.*.equipamentos_id.*'  => 'exists:equipamentos,id',
        ]);

        $agendamentos = $request->input('agendamentos', []);
        $quantas = count($agendamentos);

        // Pré-verificação de choques de horário (no banco e na própria requisição)
        $horariosVerificados = [];
        foreach ($agendamentos as $key => $agendamento) {
            // Checa choque no banco
            if ($this->temChoqueHorario(
                $agendamento['laboratorio_id'],
                $agendamento['data_solicitacao'],
                $agendamento['horario_inicio'],
                $agendamento['horario_fim']
            )) {
                return back()->withInput()->withErrors([
                    'agendamentos.'.$key.'.horario_inicio' => 'Já existe uma solicitação para este laboratório neste horário na data informada (' . \Carbon\Carbon::parse($agendamento['data_solicitacao'])->format('d/m/Y') . ').'
                ]);
            }

            // Checa choque na própria lista sendo enviada
            foreach ($horariosVerificados as $verificado) {
                if ($verificado['laboratorio_id'] == $agendamento['laboratorio_id'] &&
                    $verificado['data_solicitacao'] == $agendamento['data_solicitacao'] &&
                    $agendamento['horario_inicio'] < $verificado['horario_fim'] &&
                    $agendamento['horario_fim'] > $verificado['horario_inicio']) {
                    return back()->withInput()->withErrors([
                        'agendamentos.'.$key.'.horario_inicio' => 'Existem horários conflitantes na sua própria requisição para o mesmo laboratório.'
                    ]);
                }
            }
            $horariosVerificados[] = $agendamento;
        }

        // 3. Cria as Solicitações em loop para CADA agendamento
        foreach ($agendamentos as $agendamento) {
            $solicitacao = Solicitacao::create([
                'user_id'                => auth()->id(),
                'laboratorio_id'         => $agendamento['laboratorio_id'],
                'unidade_curricular'     => $agendamento['unidade_curricular'],
                'data_solicitacao'       => $agendamento['data_solicitacao'],
                'horario_inicio'         => $agendamento['horario_inicio'],
                'horario_fim'            => $agendamento['horario_fim'],
                'coordenador_curso_id'   => $request->coordenador_curso_id,
                'coordenador_lab_id'     => $request->coordenador_lab_id,
                'status'                 => Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO
            ]);

            // 4. Salva os relacionamentos nas tabelas pivot usando attach() (Filtrando os campos não preenchidos)
            $insumosIds = array_filter($agendamento['insumos_id'] ?? []);
            if (!empty($insumosIds)) {
                $solicitacao->insumos()->attach($insumosIds);
            }

            $equiposIds = array_filter($agendamento['equipamentos_id'] ?? []);
            if (!empty($equiposIds)) {
                $solicitacao->equipamentos()->attach($equiposIds);
            }
        }

        return redirect()->route('solicitacoes.index')
            ->with('success', $quantas . ' Solicitação(ões) enviada(s) com sucesso! Aguarde aprovação.');
    }

    // --- MÉTODOS DE APROVAÇÃO ---
    public function aprovarCoordenadorCurso($id)
    {
        // Verifica se é coordenador de curso
        if (auth()->user()->nivel != User::NIVEL_COORDENADOR_CURSO) {
            abort(403, 'Apenas coordenadores de curso podem aprovar solicitações.');
        }

        $solicitacao = Solicitacao::findOrFail($id);

        // Verifica choque de horário com solicitações já aprovadas
        if ($this->temChoqueHorario(
            $solicitacao->laboratorio_id,
            $solicitacao->data_solicitacao,
            $solicitacao->horario_inicio,
            $solicitacao->horario_fim,
            $solicitacao->id,
            true // apenasAprovadas
        )) {
            return back()->withErrors(['error' => 'Não é possível aprovar. Já existe outra solicitação APROVADA para este laboratório no mesmo horário.']);
        }

        $solicitacao->update([
            'status' => Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB,
            'autorizante_id' => auth()->id(), // Define o ID de quem autorizou
            'coordenador_curso_id' => auth()->id(), // Rastreia qual coord. curso processou
            'parecer' => null
        ]);

        return redirect()->back()->with('success', 'Solicitação aprovada e encaminhada para o coordenador do laboratório');
    }

    public function recusarCoordenadorCurso(Request $request, $id)
    {
        // Verifica se é coordenador de curso
        if (auth()->user()->nivel != User::NIVEL_COORDENADOR_CURSO) {
            abort(403, 'Apenas coordenadores de curso podem recusar solicitações.');
        }

        $request->validate([
            'parecer' => 'required|string|min:10'
        ]);

        $solicitacao = Solicitacao::findOrFail($id);

        $solicitacao->update([
            'status' => Solicitacao::STATUS_RECUSADA,
            'autorizante_id' => auth()->id(), // Define o ID de quem autorizou
            'coordenador_curso_id' => auth()->id(), // Rastreia qual coord. curso processou
            'parecer' => $request->parecer
        ]);

        return redirect()->back()->with('success', 'Solicitação recusada');
    }

    public function aprovarCoordenadorLab($id)
    {
        // Verifica se é coordenador de laboratório
        if (auth()->user()->nivel != User::NIVEL_COORDENADOR_LABORATORIO) {
            abort(403, 'Apenas coordenadores de laboratório podem aprovar solicitações.');
        }

        $solicitacao = Solicitacao::findOrFail($id);

        // Verifica choque de horário com solicitações já aprovadas
        if ($this->temChoqueHorario(
            $solicitacao->laboratorio_id,
            $solicitacao->data_solicitacao,
            $solicitacao->horario_inicio,
            $solicitacao->horario_fim,
            $solicitacao->id,
            true // apenasAprovadas
        )) {
            return back()->withErrors(['error' => 'Não é possível aprovar. Já existe outra solicitação APROVADA para este laboratório no mesmo horário.']);
        }

        // Preserve both coordenador_curso_id and autorizante_id for audit trail
        $solicitacao->update([
            'status' => Solicitacao::STATUS_APROVADA,
            // Marca o Coordenador de Laboratório atual como autorizante da decisão final
            'autorizante_id' => auth()->id(),
            // Preserva o coordenador_curso_id para que ele continue vendo a solicitação
            'coordenador_curso_id' => $solicitacao->coordenador_curso_id,
            'parecer' => null
        ]);

        return redirect()->back()->with('success', 'Solicitação aprovada com sucesso!');
    }

    public function recusarCoordenadorLab(Request $request, $id)
    {
        // Verifica se é coordenador de laboratório
        if (auth()->user()->nivel != User::NIVEL_COORDENADOR_LABORATORIO) {
            abort(403, 'Apenas coordenadores de laboratório podem recusar solicitações.');
        }

        $request->validate([
            'parecer' => 'required|string|min:10'
        ]);

        $solicitacao = Solicitacao::findOrFail($id);

        // Preserve both coordenador_curso_id and autorizante_id for audit trail
        $solicitacao->update([
            'status' => Solicitacao::STATUS_RECUSADA,
            // Marca o Coordenador de Laboratório atual como autorizante da recusa
            'autorizante_id' => auth()->id(),
            // Preserva o coordenador_curso_id para que ele continue vendo a solicitação
            'coordenador_curso_id' => $solicitacao->coordenador_curso_id,
            'parecer' => $request->parecer
        ]);

        return redirect()->back()->with('success', 'Solicitação recusada');
    }

    public function show($id)
    {
        // O Eager Loading (with) garante que insumos e equipamentos
        // sejam coleções, mesmo que vazias, evitando erros no foreach.
        $solicitacao = Solicitacao::with([
            'laboratorio',
            'roteiro',
            'insumos',
            'equipamentos',
            'user',
            'autorizante'
        ])->findOrFail($id);

        return view('solicitacoes.show', compact('solicitacao'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Solicitacao $solicitacao)
    {
        // Verificar permissões
        $user = auth()->user();
        $podeEditarTudo = (
            ($user->id == $solicitacao->user_id || empty($solicitacao->user_id)) &&
            $solicitacao->status == Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO
        );
        $podeEditarLaboratorio = (
            (($user->id == $solicitacao->coordenador_curso_id && $solicitacao->status == Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO) ||
             ($user->id == $solicitacao->coordenador_lab_id && $solicitacao->status == Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB)) &&
            !in_array($solicitacao->status, [Solicitacao::STATUS_APROVADA, Solicitacao::STATUS_RECUSADA])
        );
        $podeEditar = $podeEditarTudo || $podeEditarLaboratorio;

        if (!$podeEditar) {
            return redirect()->route('solicitacoes.show', $solicitacao)->withErrors('Você não tem permissão para editar esta solicitação.');
        }

        // Carregar dados necessários
        $laboratorios = Laboratorio::all();
        $insumos = Insumo::all();
        $equipamentos = Equipamento::all();
        $coordenadoresCurso = User::where('nivel', User::NIVEL_COORDENADOR_CURSO)->get();
        $coordenadoresLab = User::where('nivel', User::NIVEL_COORDENADOR_LABORATORIO)->get();

        return view('solicitacoes.edit', compact(
            'solicitacao',
            'laboratorios',
            'insumos',
            'equipamentos',
            'coordenadoresCurso',
            'coordenadoresLab',
            'podeEditarTudo',
            'podeEditarLaboratorio',
            'podeEditar'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Solicitacao $solicitacao)
    {
        // Verificar permissões
        $user = auth()->user();
        $podeEditarTudo = (
            ($user->id == $solicitacao->user_id || empty($solicitacao->user_id)) &&
            !in_array($solicitacao->status, [Solicitacao::STATUS_APROVADA, Solicitacao::STATUS_RECUSADA])
        );
        $podeEditarLaboratorio = (
            ($user->id == $solicitacao->coordenador_curso_id || $user->id == $solicitacao->coordenador_lab_id) &&
            !in_array($solicitacao->status, [Solicitacao::STATUS_APROVADA, Solicitacao::STATUS_RECUSADA])
        );

        if (!$podeEditarTudo && !$podeEditarLaboratorio) {
            abort(403, 'Você não tem permissão para editar esta solicitação.');
        }

        if ($podeEditarTudo) {
            // Professor pode editar tudo
            $request->validate([
                'coordenador_curso_id' => $solicitacao->coordenador_curso_id ? 'nullable|exists:users,id' : 'required|exists:users,id',
                'coordenador_lab_id' => $solicitacao->coordenador_lab_id ? 'nullable|exists:users,id' : 'required|exists:users,id',
                'laboratorio_id' => 'required|exists:laboratorios,id',
                'unidade_curricular' => 'required|string|max:255',
                'data_solicitacao' => 'required|date|after_or_equal:today',
                'horario_inicio' => 'required|regex:/^\d{2}:\d{2}(:\d{2})?$/',
                'horario_fim' => 'required|regex:/^\d{2}:\d{2}(:\d{2})?$/',
                'insumos_id' => 'nullable|array',
                'insumos_id.*' => 'exists:insumos,id',
                'equipamentos_id' => 'nullable|array',
                'equipamentos_id.*' => 'exists:equipamentos,id',
            ]);

            // Verifica choque de horário
            if ($this->temChoqueHorario(
                $request->laboratorio_id,
                $request->data_solicitacao,
                $request->horario_inicio,
                $request->horario_fim,
                $solicitacao->id
            )) {
                return back()->withInput()->withErrors([
                    'horario_inicio' => 'Já existe uma solicitação para este laboratório neste horário na data informada.'
                ]);
            }

            try {
                $updateData = [
                    'laboratorio_id' => $request->laboratorio_id,
                    'unidade_curricular' => $request->unidade_curricular,
                    'data_solicitacao' => $request->data_solicitacao,
                    'horario_inicio' => $request->horario_inicio,
                    'horario_fim' => $request->horario_fim,
                ];

                if ($request->has('coordenador_curso_id')) {
                    $updateData['coordenador_curso_id'] = $request->coordenador_curso_id;
                }

                if ($request->has('coordenador_lab_id')) {
                    $updateData['coordenador_lab_id'] = $request->coordenador_lab_id;
                }

                $solicitacao->update($updateData);

                // Sync insumos e equipamentos
                $insumos = $request->input('insumos_id', []);
                $equipamentos = $request->input('equipamentos_id', []);

                // Filtrar valores vazios e duplicados
                $insumos = array_unique(array_filter($insumos, fn($id) => !empty($id)));
                $equipamentos = array_unique(array_filter($equipamentos, fn($id) => !empty($id)));

                $solicitacao->insumos()->sync($insumos);
                $solicitacao->equipamentos()->sync($equipamentos);

                return redirect()->route('solicitacoes.index')->with('success', 'Solicitação atualizada com sucesso!');
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Erro ao atualizar: ' . $e->getMessage()]);
            }
        } elseif ($podeEditarLaboratorio) {
            // Coordenador pode editar apenas laboratório
            $request->validate([
                'laboratorio_id' => 'required|exists:laboratorios,id',
            ]);

            // Verifica choque de horário
            if ($this->temChoqueHorario(
                $request->laboratorio_id,
                $solicitacao->data_solicitacao,
                $solicitacao->horario_inicio,
                $solicitacao->horario_fim,
                $solicitacao->id
            )) {
                return back()->withInput()->withErrors([
                    'laboratorio_id' => 'O laboratório selecionado já possui uma solicitação neste horário.'
                ]);
            }

            try {
                $solicitacao->update([
                    'laboratorio_id' => $request->laboratorio_id,
                ]);

                return redirect()->route('solicitacoes.index')->with('success', 'Laboratório atualizado com sucesso!');
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Erro ao atualizar laboratório: ' . $e->getMessage()]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $solicitacao = Solicitacao::findOrFail($id);

        // Verifica se o usuário tem permissão para excluir
        if (auth()->user()->nivel != User::NIVEL_PROFESSOR || $solicitacao->user_id != auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $solicitacao->delete();

        return redirect()->route('solicitacoes.index')
            ->with('success', 'Solicitação excluída com sucesso!');
    }

    /**
     * Verifica se existe choque de horário para um laboratório
     */
    private function temChoqueHorario($laboratorio_id, $data_solicitacao, $horario_inicio, $horario_fim, $ignoreId = null, $apenasAprovadas = false)
    {
        $query = Solicitacao::where('laboratorio_id', $laboratorio_id)
            ->where('data_solicitacao', $data_solicitacao)
            ->where('status', '!=', Solicitacao::STATUS_RECUSADA)
            ->where(function($q) use ($horario_inicio, $horario_fim) {
                $q->where('horario_inicio', '<', $horario_fim)
                  ->where('horario_fim', '>', $horario_inicio);
            });

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        if ($apenasAprovadas) {
            $query->where('status', Solicitacao::STATUS_APROVADA);
        }

        return $query->exists();
    }

    /**
     * Normaliza o nome da disciplina para agrupamento inteligente
     * Remove variações comuns e padroniza o formato
     */
    private function normalizarNomeDisciplina(string $disciplina): string
    {
        // Converter para minúsculo
        $normalizada = strtolower(trim($disciplina));

        // Remover acentos e caracteres especiais
        $normalizada = $this->removerAcentos($normalizada);

        // Padronizar termos comuns (apenas números romanos por enquanto)
        $padroes = [
            // Números romanos
            '/\bi\b/' => '1', '/\bii\b/' => '2', '/\biii\b/' => '3',
            '/\biv\b/' => '4', '/\bv\b/' => '5', '/\bvi\b/' => '6',

            // Remover palavras irrelevantes comuns
            '/\b(a|o|as|os|de|do|da|dos|das|e|ou|para|com|em|no|na|nos|nas)\b/' => '',

            // Padronizar espaços
            '/\s+/' => ' ',
        ];

        foreach ($padroes as $padrao => $substituicao) {
            $normalizada = preg_replace($padrao, $substituicao, $normalizada);
        }

        // Remover espaços extras e trim final
        $normalizada = trim(preg_replace('/\s+/', ' ', $normalizada));

        return $normalizada;
    }

    /**
     * Remove acentos e caracteres especiais
     */
    private function removerAcentos(string $string): string
    {
        $mapa = [
            'á' => 'a', 'à' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
            'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'ç' => 'c', 'ñ' => 'n'
        ];

        return strtr($string, $mapa);
    }
}
