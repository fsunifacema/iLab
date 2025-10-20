<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;
use App\Models\Roteiro;
use App\Models\Laboratorio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // Adicionando DB para transações

class SolicitacaoController extends Controller
{
    /**
     * Exibe a lista de Solicitações (Roteiros) pendentes ou em fluxo, filtradas por nível.
     * Método mapeado para a rota 'solicitacoes.index'.
     */
    public function indexSolicitacoes()
    {
        $user = Auth::user();
        $nivelUsuario = $user->nivel; 

        $query = Solicitacao::query();
        
        // Carrega os relacionamentos necessários para a view
        $query->with(['roteiro', 'laboratorio', 'criador']);

        // --- LÓGICA DE FILTRO BASEADA NO NÍVEL ---

        // Nível 1: Professor (Vê o que criou e que não foi finalizado)
        if ($nivelUsuario == User::NIVEL_PROFESSOR) {
            $query->where('user_id', $user->id)
                  ->where('status', '!=', 'aprovado')
                  ->where('status', '!=', 'rejeitado');
        } 
        
        // Nível 2: Técnico (Só vê o que precisa da ação dele)
        elseif ($nivelUsuario == User::NIVEL_TECNICO) {
            $query->where('nivel_atual', User::NIVEL_TECNICO)
                  ->where('status', 'pendente');
        }
        
        // Nível 3: Coordenador de Curso (Só vê o que precisa da ação dele)
        elseif ($nivelUsuario == User::NIVEL_COORDENADOR_CURSO) {
            $query->where('nivel_atual', User::NIVEL_COORDENADOR_CURSO)
                  ->where('status', 'pendente');
        }
        
        // Nível 4: Coordenador de Laboratório / Admin (Vê o que precisa da ação dele E o histórico)
        elseif ($nivelUsuario == User::NIVEL_COORDENADOR_LABORATORIO) {
            // Vê os pendentes no nível 4
            $query->where('nivel_atual', User::NIVEL_COORDENADOR_LABORATORIO)
                  ->where('status', 'pendente')
                  // E também vê todas as solicitações finalizadas para fins de histórico/administração
                  ->orWhere('status', 'aprovado')
                  ->orWhere('status', 'rejeitado');
        } else {
             // Nível desconhecido
             $query->whereRaw('1 = 0');
        }
        
        $solicitacoes = $query->latest()->paginate(15);
        
        return view('solicitacoes.index', compact('solicitacoes'));
    }
    
    /**
     * Exibe o formulário de criação de uma nova Solicitação.
     * Método mapeado para a rota 'solicitacoes.create'.
     */
    public function createSolicitacao()
    {
        // Para a criação, é necessário listar os roteiros e laboratórios disponíveis
        $roteiros = Roteiro::all(['id', 'unidade_curricular', 'professor']);
        $laboratorios = Laboratorio::all(['id', 'nome']);
        
        return view('solicitacoes.create', compact('roteiros', 'laboratorios'));
    }

    /**
     * Armazena uma nova Solicitação e a anexa a um Roteiro existente.
     * Esta é a primeira etapa do fluxo.
     * Método mapeado para a rota 'solicitacoes.store'.
     */
    public function storeSolicitacao(Request $request)
    {
        // 1. Validação dos dados necessários para a SOLICITAÇÃO
        $dadosSolicitacao = $request->validate([
            'roteiro_id' => 'required|exists:roteiros,id',
            'laboratorio_id' => 'required|exists:laboratorios,id',
            'data_reserva' => 'required|date|after_or_equal:today',
        ]);
        
        // 2. Criação da Solicitação (Início do Fluxo)
        Solicitacao::create([
            'roteiro_id' => $dadosSolicitacao['roteiro_id'],
            'laboratorio_id' => $dadosSolicitacao['laboratorio_id'],
            'data_reserva' => $dadosSolicitacao['data_reserva'],
            'user_id' => Auth::id(),// O criador da solicitação (usuário logado)
            'nivel_atual' => User::NIVEL_TECNICO, // O fluxo começa no Nível 2 (Técnico)
            'status' => 'pendente',
        ]);

        return redirect()->route('solicitacoes.index')
            ->with('success', 'Solicitação enviada para o Técnico com sucesso!');
    }


    /**
     * Exibe o formulário para visualização/edição/aprovação da Solicitação.
     * Método mapeado para a rota 'solicitacoes.edit'.
     */
    public function edit(Solicitacao $solicitacao)
    {
        // Garante que o roteiro, laboratório e criador estejam carregados
        $solicitacao->load(['roteiro', 'laboratorio', 'criador']);
        
        // Define o nível do usuário logado para controlar quais ações são permitidas na view
        $nivelUsuario = Auth::user()->nivel;

        // Se o roteiro já está aprovado/rejeitado, a edição deve ser limitada (somente visualização)
        if ($solicitacao->status !== 'pendente') {
            // Você deve criar a view solicitacoes.show para visualização final
            return view('solicitacoes.edit', compact('solicitacao', 'nivelUsuario')); 
        }

        return view('solicitacoes.edit', compact('solicitacao', 'nivelUsuario'));
    }

    /**
     * Processa a Aprovação, Rejeição ou Edição (se solicitada) da Solicitação.
     */
    public function update(Request $request, Solicitacao $solicitacao)
    {
        $user = Auth::user();
        $nivelUsuario = $user->nivel;
        $mensagem = 'Ação processada com sucesso.'; // Definido antes da transação

        // 1. Regras de Validação Comuns
        $validationRules = [
            'action' => ['required', Rule::in(['aprovar', 'rejeitar'])], 
            'comentarios' => 'nullable|string|max:1000',
            
            // Validação dos campos do Roteiro (se houver edição)
            'roteiro_id' => 'required|exists:roteiros,id', 
            'roteiro' => 'required|array', 
            'roteiro.unidade_curricular' => 'nullable|string|max:255',
            'roteiro.professor' => 'nullable|string|max:255',
            'roteiro.conteudo' => 'nullable|string',
            // Adicione aqui todos os campos editáveis do Roteiro!
            
            'data_reserva' => 'required|date|after_or_equal:today', 
        ];

        // Ensure Roteiro fields are validated if present
        $request->merge([
            'roteiro_id' => $solicitacao->roteiro_id,
            'data_reserva' => $request->input('data_reserva'),
        ]);

        $dados = $request->validate($validationRules);
        $action = $dados['action'];
        
        // Verificações de Permissão
        if ($solicitacao->status !== 'pendente') {
            return redirect()->route('solicitacoes.index')->with('error', 'Esta solicitação já foi finalizada.');
        }

        if ($nivelUsuario !== $solicitacao->nivel_atual) {
             return redirect()->back()->with('error', 'Você não tem permissão para aprovar esta solicitação neste momento.');
        }

        // Usamos DB::transaction para garantir a atomicidade das duas atualizações (Solicitação e Roteiro)
        DB::transaction(function () use ($solicitacao, $user, $nivelUsuario, $action, $dados, &$mensagem) {
            
            // 1. Atualizar o Roteiro com as edições feitas (pode ser feito em qualquer nível)
            $solicitacao->roteiro->update($dados['roteiro']);
            
            // 2. Processar a Ação
            if ($action === 'rejeitar') {
                // Rejeição (Finaliza o fluxo)
                $solicitacao->update([
                    'status' => 'rejeitado',
                    'motivo_rejeicao' => $dados['motivo_rejeicao'],
                    'ultimo_autorizante_id' => $user->id,
                ]);
                $mensagem = 'Solicitação rejeitada e fluxo finalizado.';

            } else { // Aprovar
                
                // Avanço de Nível
                $proximoNivel = $nivelUsuario + 1;

                if ($proximoNivel > User::NIVEL_COORDENADOR_LABORATORIO) {
                    // APROVAÇÃO FINAL (Nível 4 aprovou)
                    $solicitacao->update([
                        'status' => 'aprovado',
                        'nivel_atual' => User::NIVEL_COORDENADOR_LABORATORIO,
                        'ultimo_autorizante_id' => $user->id,
                    ]);
                    $mensagem = 'Solicitação aprovada com sucesso! Fluxo finalizado.';
                } else {
                    // AVANÇAR PARA O PRÓXIMO NÍVEL
                    $solicitacao->update([
                        'nivel_atual' => $proximoNivel,
                        'ultimo_autorizante_id' => $user->id,
                    ]);
                    $mensagem = 'Solicitação aprovada e avançada para o próximo nível.';
                }
            }
        });
        
        // Retorna a mensagem que foi definida dentro da transação
        return redirect()->route('solicitacoes.index')
            ->with('success', $mensagem);
    }
}
