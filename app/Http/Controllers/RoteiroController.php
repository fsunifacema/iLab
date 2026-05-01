<?php

namespace App\Http\Controllers;

use App\Models\Roteiro;
use App\Models\Laboratorio;
use App\Models\Solicitacao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Carbon\Carbon;
use App\Models\User;

class RoteiroController extends Controller
{
    public function index()
    {
        // 1. Inicia a query do modelo Roteiro
        $query = Roteiro::query();

        // 2. Aplica o filtro SE o usuário for Professor
        if (Auth::user()->nivel === User::NIVEL_PROFESSOR) {
            // Adiciona a cláusula WHERE para filtrar por professor
            $query->where('user_id', Auth::id());
            // Os coordenadores, técnicos, etc. verão todos, pois não entram neste IF
        }

        // 3. Executa a query final (com ou sem o filtro de professor)
        //    e aplica a ordenação e a paginação para todos os resultados.
        $roteiros = $query->latest()->paginate(10);
        return view('roteiros.index', compact('roteiros'));
    }

    /**
     * Rota de API para o FullCalendar.
     * Retorna os roteiros no formato JSON de eventos, com filtro de acesso.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoteirosAsEvents()
    {
        // 1. Inicia a query do modelo Roteiro
        $query = Roteiro::query();

        // **FILTRO DE ACESSO DO USUÁRIO:**
        // Se for Professor, filtra apenas os seus roteiros.
        if (Auth::user()->nivel === User::NIVEL_PROFESSOR) {
            $query->where('user_id', Auth::id());
        }

        // Filtro de segurança para ignorar datas obviamente inválidas
        $query->where('data', '>', '2020-01-01');

        $roteiros = $query->get();

        // 3. Formata os dados para o FullCalendar
        $events = $roteiros->map(function ($roteiro) {

            $title = "{$roteiro->unidade_curricular} - {$roteiro->tema} ({$roteiro->laboratorio})";

            // CORREÇÃO DEFINITIVA: Usa Carbon para garantir APENAS a data pura (YYYY-MM-DD).
            try {
                $carbonDate = Carbon::parse($roteiro->data);
                $dateOnly = $carbonDate->format('Y-m-d');
            } catch (\Exception $e) {
                // Ignora roteiros com datas inválidas
                return null;
            }

            $start = $dateOnly;
            $end = null;
            $allDay = true;

            // Lógica de Formatação para ISO 8601 (YYYY-MM-DDTHH:MM:SS)
            // Usa os campos horario_inicio e horario_fim (separados)
            if (!empty($roteiro->horario_inicio)) {
                $hora_inicio = $roteiro->horario_inicio;

                // GARANTE O FORMATO ISO: YYYY-MM-DDTHH:MM:SS
                $start = "{$dateOnly}T{$hora_inicio}:00";
                $allDay = false;

                // Calcula o 'end' se houver horario_fim
                if (!empty($roteiro->horario_fim)) {
                    $hora_fim = $roteiro->horario_fim;
                    $end = "{$dateOnly}T{$hora_fim}:00";
                }
            }


            return [
                'id' => $roteiro->id,
                'title' => $title,
                'start' => $start,
                'end' => $end,
                'color' => 'rgba(255, 255, 255, 1)',
                'allDay' => $allDay,

                // Informações adicionais para o modal
                'extendedProps' => [
                    'professor' => $roteiro->user ? $roteiro->user->name : 'Indefinido',
                    'curso' => $roteiro->curso,
                    'unidade_curricular' => $roteiro->unidade_curricular,
                    'periodo' => $roteiro->periodo,
                    'laboratorio' => $roteiro->laboratorio,
                    'carga_horaria' => $roteiro->carga_horaria,
                    'quantidade_alunos' => $roteiro->quantidade_alunos,
                    'tema' => $roteiro->tema,
                ],
            ];
        })->filter()->values(); // Remove any null items from invalid dates

        // 4. Retorna a lista de eventos em JSON
        return response()->json($events);
    }

public function create()
    {
        // Busca solicitações aprovadas E que ainda NÃO possuem roteiro
        $query = Solicitacao::where('status', Solicitacao::STATUS_APROVADA)
        ->doesntHave('roteiro'); // <--- Importante: Filtra apenas as sem roteiro
        
        // Se for professor, filtra apenas as dele
        if (Auth::user()->nivel === User::NIVEL_PROFESSOR) {
            $query->where('user_id', Auth::id());
        }
        
        $solicitacoes = $query->get();
        $laboratorios = Laboratorio::all();
        
        return view('roteiros.create', compact('solicitacoes', 'laboratorios'));
    }

    /**
     * Criar roteiro a partir de uma solicitacao aprovada
     * Preenche automaticamente: data, horario_inicio, horario_fim, laboratorio
     */
    public function createFromSolicitacao($codigo_solicitacao)
    {
        // Busca a solicitação pelo código
        $solicitacao = Solicitacao::where('codigo', $codigo_solicitacao)->firstOrFail();

        // Validar se a solicitação foi aprovada
        if ($solicitacao->status !== Solicitacao::STATUS_APROVADA) {
            return redirect()->route('solicitacoes.index')
                ->with('error', 'A solicitação deve estar aprovada para criar um roteiro.');
        }

        // Validar se o usuário é o professor da solicitação ou coordenador
        $is_authorized = Auth::id() === $solicitacao->user_id ||
                         Auth::user()->nivel === User::NIVEL_COORDENADOR_CURSO ||
                         Auth::user()->nivel === User::NIVEL_COORDENADOR_LABORATORIO;

        if (!$is_authorized) {
            abort(403, 'Você não tem permissão para criar roteiro para esta solicitação.');
        }

        $laboratorios = Laboratorio::all();
        return view('roteiros.create-from-solicitacao', compact('solicitacao', 'laboratorios'));
    }

    /**
     * Armazenar roteiro criado a partir de solicitacao
     */
    /**
     * Armazenar roteiro criado a partir de solicitacao
     */
    public function storeFromSolicitacao(Request $request)
    {
        // Busca a solicitação
        $solicitacao = Solicitacao::where('codigo', $request->codigo_solicitacao)->firstOrFail();

        // Validação de status
        if ($solicitacao->status !== Solicitacao::STATUS_APROVADA) {
            return redirect()->back()
                ->with('error', 'A solicitação deve estar aprovada.');
        }

        // Validação de autorização
        $is_authorized = Auth::id() === $solicitacao->user_id ||
                         Auth::user()->nivel === User::NIVEL_COORDENADOR_CURSO ||
                         Auth::user()->nivel === User::NIVEL_COORDENADOR_LABORATORIO;

        if (!$is_authorized) {
            abort(403, 'Acesso negado.');
        }

        // 1. CORREÇÃO: Validar TODOS os campos para que eles entrem na variável $dados
        $dados = $request->validate([
            'codigo_solicitacao' => 'required|string',
            'unidade_curricular' => 'required|string|max:255',
            'tema'          => 'required|string|max:255',
            'curso'         => 'nullable|string|max:255', // Adicionado
            'periodo'       => 'nullable|string|max:50',   // Adicionado
            'data'          => 'nullable|date',            // Adicionado
            'horario_inicio'=> 'nullable|date_format:H:i', // Adicionado
            'horario_fim'   => 'nullable|date_format:H:i', // Adicionado
            'carga_horaria' => 'nullable|string',          // Adicionado
            'numero_praticas'=> 'nullable|integer',        // Adicionado
            'laboratorio'   => 'nullable|string',          // Adicionado
            'conteudo'      => 'nullable|string',
            'objetivos'     => 'nullable|string',
            'metodologia'   => 'nullable|string',
            'recursos'      => 'nullable|string',
            'avaliacao'     => 'nullable|string',
            'observacoes'   => 'nullable|string',
        ]);

        // 2. CORREÇÃO: Lógica de Fallback (Prioridade: Formulário > Solicitação > Padrão)
        
        // Curso
        if (empty($dados['curso'])) {
            $dados['curso'] = $solicitacao->curso ?? '';
        }

        // Usuário (Sempre pega da solicitação ou do usuário logado se não vier no form)
        $dados['user_id'] = $solicitacao->user_id ?? Auth::id();

        // Período
        if (empty($dados['periodo'])) {
            $dados['periodo'] = $solicitacao->periodo ?? '';
        }

        // Quantidade de Alunos (não estava no validate, adicionei lógica direta)
        $dados['quantidade_alunos'] = $request->input('quantidade_alunos') ?? $solicitacao->quantidade_alunos ?? 1;

        // Data
        if (empty($dados['data'])) {
            // Tenta pegar data da solicitação, se não tiver, pega created_at
            $dados['data'] = $solicitacao->data_solicitacao 
                             ?? $solicitacao->data 
                             ?? $solicitacao->created_at->toDateString();
        }

        // Horários
        if (empty($dados['horario_inicio'])) {
            $dados['horario_inicio'] = $solicitacao->horario_inicio ?? null;
        }
        if (empty($dados['horario_fim'])) {
            $dados['horario_fim'] = $solicitacao->horario_fim ?? null;
        }

        // Carga Horária
        if (empty($dados['carga_horaria'])) {
             // Tenta pegar do roteiro antigo vinculado (se existir) ou define padrão
            $dados['carga_horaria'] = $solicitacao->carga_horaria 
                                      ?? ($solicitacao->roteiro?->carga_horaria ?? 'Não Definida');
        }

        // Número de Práticas
        if (empty($dados['numero_praticas'])) {
            $dados['numero_praticas'] = $solicitacao->numero_praticas 
                                        ?? ($solicitacao->roteiro?->numero_praticas ?? 1);
        }

        // Laboratório
        if (empty($dados['laboratorio'])) {
            // Uso optional() para evitar erro se laboratorio for nulo
            $dados['laboratorio'] = optional($solicitacao->laboratorio)->nome 
                                    ?? ($solicitacao->laboratorio_nome ?? '');
        }

        // Criar o roteiro
        $roteiro = Roteiro::create($dados);

        return redirect()->route('roteiros.show', $roteiro->id)
            ->with('success', 'Roteiro criado com sucesso a partir da solicitação!');
    }
    public function store(Request $request)
    {
        $dados = $request->validate([
            'codigo_solicitacao' => 'required|string|exists:solicitacoes,codigo',
            'curso' => 'nullable|string|max:255',
            'unidade_curricular' => 'nullable|string|max:255',
            'periodo' => 'nullable|string|max:50',
            'quantidade_alunos' => 'nullable|integer',
            'data' => 'nullable|date|after_or_equal:today',
            'horario_inicio' => 'nullable|date_format:H:i',
            'horario_fim' => 'nullable|date_format:H:i|after:horario_inicio',
            'carga_horaria' => 'nullable|string|max:50',
            'numero_praticas' => 'nullable|integer',
            'laboratorio' => 'nullable|string|max:255',
            'conteudo' => 'nullable|string',
            'tema' => 'nullable|string',
            'objetivos' => 'nullable|string',
            'metodologia' => 'nullable|string',
            'recursos' => 'nullable|string',
            'avaliacao' => 'nullable|string',
            'observacoes' => 'nullable|string',
            'data_assinatura' => 'nullable|date',
        ]);

        // Valida se a solicitação está aprovada
        $solicitacao = Solicitacao::where('codigo', $dados['codigo_solicitacao'])->firstOrFail();
        if ($solicitacao->status !== Solicitacao::STATUS_APROVADA) {
            return redirect()->back()
                ->with('error', 'A solicitação deve estar aprovada para criar um roteiro.')
                ->withInput();
        }

        // Adiciona automaticamente o usuário logado
        $dados['user_id'] = auth()->id();

        $roteiro = Roteiro::create($dados);

        return redirect()->route('roteiros.show', $roteiro->id)
            ->with('success', 'Roteiro criado com sucesso!');
    }

    public function show(Roteiro $roteiro)
    {
        return view('roteiros.show', compact('roteiro'));
    }

    public function edit(Roteiro $roteiro)
    {
        // 1. VERIFICAÇÃO DE AUTORIZAÇÃO
        $user_nivel = Auth::user()->nivel;

        // Níveis permitidos para EDIÇÃO (Professor E Coordenadores)
        $allowed_levels = [
            User::NIVEL_PROFESSOR,
            User::NIVEL_COORDENADOR_CURSO,
            User::NIVEL_COORDENADOR_LABORATORIO,
        ];

        // A autorização é concedida se o nível do usuário estiver na lista de permitidos
        $is_authorized = in_array($user_nivel, $allowed_levels);

        if (!$is_authorized) {
            // Bloqueia se for NIVEL_TECNICO ou qualquer outro não listado
            abort(403, 'Acesso não autorizado. Você não tem permissão para editar roteiros.');
        }

        // 2. Continua se a autorização for bem-sucedida
        $laboratorios = Laboratorio::all();
        return view('roteiros.edit', compact('roteiro', 'laboratorios'));
    }

    public function update(Request $request, Roteiro $roteiro)
    {
        // 1. VERIFICAÇÃO DE AUTORIZAÇÃO
        $user_nivel = Auth::user()->nivel;

        $allowed_levels = [
            User::NIVEL_PROFESSOR,
            User::NIVEL_COORDENADOR_CURSO,
            User::NIVEL_COORDENADOR_LABORATORIO,
        ];

        // A autorização é concedida se o nível do usuário estiver na lista de permitidos
        $is_authorized = in_array($user_nivel, $allowed_levels);

        if (!$is_authorized) {
            // Bloqueia qualquer usuário que tente enviar o formulário sem permissão
            abort(403, 'Acesso não autorizado. Você não tem permissão para atualizar roteiros.');
        }
        $dados = $request->validate([
            'curso' => 'nullable|string|max:255',
            'unidade_curricular' => 'nullable|string|max:255',
            'periodo' => 'nullable|string|max:50',
            'quantidade_alunos' => 'nullable|integer',
            'data' => 'nullable|date|after_or_equal:today',
            'horario_inicio' => 'nullable|date_format:H:i',
            'horario_fim' => 'nullable|date_format:H:i|after:horario_inicio',
            'carga_horaria' => 'nullable|string|max:50',
            'numero_praticas' => 'nullable|integer',
            'laboratorio' => 'nullable|string|max:255',
            'conteudo' => 'nullable|string',
            'tema' => 'nullable|string',
            'objetivos' => 'nullable|string',
            'metodologia' => 'nullable|string',
            'recursos' => 'nullable|string',
            'avaliacao' => 'nullable|string',
            'observacoes' => 'nullable|string',
            'data_assinatura' => 'nullable|date',
        ]);

        $roteiro->update($dados);

        return redirect()->route('roteiros.show', $roteiro->id)
            ->with('success', 'Roteiro atualizado com sucesso!');
    }

    public function destroy(Roteiro $roteiro)
    {
        $roteiro->delete();
        return redirect()->route('roteiros.index')
            ->with('success', 'Roteiro excluído com sucesso!');
    }

    public function pdf(Roteiro $roteiro)
    {
        // Definir as opções para o DomPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $imagePath = public_path('img/logo-facema-azul.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/png;base64,' . $imageData;

        // Instanciar o DomPDF
        $dompdf = new Dompdf($options);

        // Carregar a view e gerar o conteúdo
        $pdfContent = view('pdf.roteiro-pratica', compact('roteiro', 'imageSrc'))->render();

        // Carregar o conteúdo HTML no DomPDF
        $dompdf->loadHtml($pdfContent);

        // (Opcional) Defina o tamanho da página
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar o PDF
        $dompdf->render();

        // Exibir o PDF no navegador
        return $dompdf->stream("roteiro_{$roteiro->unidade_curricular}_{$roteiro->periodo}.pdf");
    }
}
