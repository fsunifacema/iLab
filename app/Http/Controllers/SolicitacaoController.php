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

        $query->with(['laboratorio', 'roteiro', 'professor', 'autorizante']);

        if ($user->nivel == User::NIVEL_PROFESSOR) {
            // Professor: Vê TODAS as suas solicitações
            $query->where('professor_id', $user->id);
        } elseif ($user->nivel == User::NIVEL_COORDENADOR_CURSO) {
            // Coord. Curso: Vê PENDENTES (status 1) OU JÁ PROCESSADAS POR ELE (status 2, 3, 4)
            $query->where(function ($q) use ($user) {
                // 1. PENDENTES DELE (Status 1)
                $q->where('status', Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO)

                    // 2. OU JÁ PROCESSADAS POR ELE: Status 2 (encaminhado), Status 3 (aprovado), Status 4 (recusado).
                    // Filtra pelo autorizante, mas garante que não pegue Status 1 de outras pessoas.
                    ->orWhere(function ($orQ) use ($user) {
                        $orQ->whereIn('status', [
                            Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB, // Status 2 (Ele acabou de criar)
                            Solicitacao::STATUS_APROVADA,                   // Status 3 (Finalizado)
                            Solicitacao::STATUS_RECUSADA                    // Status 4 (Finalizado)
                        ])
                            ->where('autorizante_id', $user->id);
                    });
            });
        } elseif ($user->nivel == User::NIVEL_COORDENADOR_LABORATORIO) {
            // Coord. Lab: Vê PENDENTES (status 2) OU JÁ APROVADAS (autorizante_id = user_id)
            $query->where(function ($q) use ($user) {
                $q->where('status', Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB)
                    ->orWhere('autorizante_id', $user->id);
            });
        }
        // Para outros níveis (Técnico), sem filtro: ele verá todas as solicitações.

        // Ordena para que as PENDENTES (menores status) apareçam primeiro,
        // garantindo que as que precisam de ação estejam no topo.
        $solicitacoes = $query->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->get(); // Se quiser paginação, mude para ->paginate(10);

        return view('solicitacoes.index', compact('solicitacoes'));
    }

    public function create()
    {
        // Verifica se é professor
        if (auth()->user()->nivel != User::NIVEL_PROFESSOR) {
            abort(403, 'Apenas professores podem criar solicitações.');
        }

        $laboratorios = Laboratorio::all();
        $roteiros = Roteiro::where('professor', auth()->user()->name)->get();
        $insumos = Insumo::all();
        $equipamentos = Equipamento::all();

        return view('solicitacoes.create', compact('laboratorios', 'roteiros', 'insumos', 'equipamentos'));
    }

    public function store(Request $request)
    {
        // 1. Verifica se é professor
        if (auth()->user()->nivel != User::NIVEL_PROFESSOR) {
            abort(403, 'Apenas professores podem criar solicitações.');
        }

        // 2. Validação (incluindo os arrays de IDs)
        $request->validate([
            'laboratorio_id'   => 'required|exists:laboratorios,id',
            'data_solicitacao' => 'required|date|after_or_equal:today',
            'horario_inicio'   => 'required|date_format:H:i',
            'horario_fim'      => 'required|date_format:H:i|after:horario_inicio',
            'insumos_id'       => 'nullable|array', // Valida que é um array
            'insumos_id.*'     => 'exists:insumos,id', // Valida cada ID dentro do array
            'equipamentos_id'  => 'nullable|array',
            'equipamentos_id.*'=> 'exists:equipamentos,id',
        ]);

        // 3. Cria a Solicitação (sem os insumos/equipamentos ainda)
        $solicitacao = Solicitacao::create([
            'professor_id'     => auth()->id(),
            'laboratorio_id'   => $request->laboratorio_id,
            'data_solicitacao' => $request->data_solicitacao,
            'horario_inicio'   => $request->horario_inicio,
            'horario_fim'      => $request->horario_fim,
            'status'           => Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO
        ]);

        // 4. Salva os relacionamentos nas tabelas pivot usando attach()
        if ($request->has('insumos_id')) {
            $solicitacao->insumos()->attach($request->insumos_id);
        }

        if ($request->has('equipamentos_id')) {
            $solicitacao->equipamentos()->attach($request->equipamentos_id);
        }

        return redirect()->route('solicitacoes.index')
            ->with('success', 'Solicitação enviada com sucesso! Aguarde aprovação.');
    }

    // --- MÉTODOS DE APROVAÇÃO (mantidos) ---
    public function aprovarCoordenadorCurso($id)
    {
        // Verifica se é coordenador de curso
        if (auth()->user()->nivel != User::NIVEL_COORDENADOR_CURSO) {
            abort(403, 'Apenas coordenadores de curso podem aprovar solicitações.');
        }

        $solicitacao = Solicitacao::findOrFail($id);

        $solicitacao->update([
            'status' => Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB,
            'autorizante_id' => auth()->id(), // Define o ID de quem autorizou
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

        // Preserve the existing autorizante_id (e.g., coordenador de curso) when present,
        // so that the original approver can still see the request after final approval.
        $solicitacao->update([
            'status' => Solicitacao::STATUS_APROVADA,
            // Marca o Coordenador de Laboratório atual como autorizante da decisão final
            'autorizante_id' => auth()->id(),
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

        // Preserve the existing autorizante_id if present so the earlier autorizante
        // (e.g., coordenador de curso) remains recorded for visibility/audit.
        $solicitacao->update([
            'status' => Solicitacao::STATUS_RECUSADA,
            // Marca o Coordenador de Laboratório atual como autorizante da recusa
            'autorizante_id' => auth()->id(),
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
            'professor', 
            'autorizante'
        ])->findOrFail($id);

        return view('solicitacoes.show', compact('solicitacao'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Solicitacao $solicitacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Solicitacao $solicitacao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $solicitacao = Solicitacao::findOrFail($id);

        // Verifica se o usuário tem permissão para excluir
        if (auth()->user()->nivel != User::NIVEL_PROFESSOR || $solicitacao->professor_id != auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $solicitacao->delete();

        return redirect()->route('solicitacoes.index')
            ->with('success', 'Solicitação excluída com sucesso!');
    }
}
