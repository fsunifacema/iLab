<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;
use App\Models\Roteiro;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon; // IMPORTADO PARA CONFIGURAR O LOCALE

class DashboardController extends Controller
{
    /**
     * Exibe a view principal da Dashboard, carregando o feed de notificações.
     */
    public function index()
    {
        // Bloquear acesso de Admins à dashboard
        if (auth()->user()->nivel === User::NIVEL_ADMINISTRADOR) {
            return redirect()->route('users.index');
        }

        // --- ADIÇÃO: Configura o locale para português do Brasil (pt-BR) ---
        Carbon::setLocale('pt_BR');
        // --------------------------------------------------------------------

        $user = auth()->user();
        $notifications = collect(); // Inicializa uma coleção vazia

        // --- NOTIFICAÇÕES DE FLUXO DE APROVAÇÃO DE SOLICITAÇÃO ---
        // Visible to: Professors, Coordenadores de Curso, Coordenadores de Lab
        // NÃO inclui Admin (nível 5)
        if (in_array($user->nivel, [User::NIVEL_PROFESSOR, User::NIVEL_COORDENADOR_CURSO, User::NIVEL_COORDENADOR_LABORATORIO])) {
            
            $query = Solicitacao::query();
            $query->with(['professor', 'roteiro', 'laboratorio', 'autorizante']);

            // Filtro de status: Busca todos os status que geram notificação
            $query->whereIn('status', [
                Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO,
                Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB,
                Solicitacao::STATUS_APROVADA,
                Solicitacao::STATUS_RECUSADA,
            ]);

            // Limita notificações ao período recente: últimas 7 dias
            $query->where('updated_at', '>=', Carbon::now()->subDays(7));

            // --- FILTRAGEM POR NÍVEL DE USUÁRIO ---
            if ($user->nivel == User::NIVEL_PROFESSOR) {
                // Professor: Vê apenas atualizações de SUAS solicitações, mas só após o primeiro passo
                $query->where('professor_id', $user->id)
                    ->whereIn('status', [Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB, Solicitacao::STATUS_APROVADA, Solicitacao::STATUS_RECUSADA]);
            } elseif ($user->nivel == User::NIVEL_COORDENADOR_CURSO) {
                // Coordenador de Curso (CC):
                $query->where(function (Builder $q) use ($user) {
                    $q->where('status', Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO)
                        ->orWhere(function (Builder $orQ) use ($user) {
                            $orQ->whereIn('status', [Solicitacao::STATUS_APROVADA, Solicitacao::STATUS_RECUSADA])
                                ->where('autorizante_id', $user->id);
                        });
                });
            } elseif ($user->nivel == User::NIVEL_COORDENADOR_LABORATORIO) {
                // Coordenador de Laboratório (CL):
                $query->where(function (Builder $q) use ($user) {
                    $q->where('status', Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB)
                        ->orWhere('autorizante_id', $user->id);
                });
            }

            $solicitacoes = $query->orderBy('updated_at', 'desc')->limit(10)->get();

            $notificacoesSolicitacoes = $solicitacoes->map(function ($solicitacao) use ($user) {
                $status_info = match ($solicitacao->status) {
                    Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO => [
                        'text' => 'APROVAÇÃO PENDENTE',
                        'message' => 'Nova solicitação. Requer análise do Coordenador de Curso.',
                        'code' => 'review',
                        'is_pending_for_me' => ($user->nivel == User::NIVEL_COORDENADOR_CURSO)
                    ],
                    Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB => [
                        'text' => 'APROVAÇÃO PENDENTE',
                        'message' => 'Aprovada pelo CC. Requer análise e agendamento do Coordenador de Laboratório.',
                        'code' => 'review',
                        'is_pending_for_me' => ($user->nivel == User::NIVEL_COORDENADOR_LABORATORIO)
                    ],
                    Solicitacao::STATUS_APROVADA => [
                        'text' => 'APROVADA E AGENDADA',
                        'message' => 'A solicitação foi aprovada pelo Coordenador do Laboratório.',
                        'code' => 'approved',
                        'is_pending_for_me' => false
                    ],
                    Solicitacao::STATUS_RECUSADA => [
                        'text' => 'RECUSADA',
                        'message' => 'Parecer: ' . ($solicitacao->parecer ?: 'Não informado.'),
                        'code' => 'rejected',
                        'is_pending_for_me' => false
                    ],
                    default => [
                        'text' => 'MUDANÇA DE STATUS',
                        'message' => 'Verifique os detalhes.',
                        'code' => 'pending',
                        'is_pending_for_me' => false
                    ],
                };

                $title = ($status_info['is_pending_for_me'] ?? false)
                    ? "PENDENTE DE APROVAÇÃO: Solicit. #{$solicitacao->id}"
                    : "Solicitação {$solicitacao->id}: {$status_info['text']}";

                return [
                    'id' => $solicitacao->id,
                    'type' => 'solicitacao',
                    'status' => $status_info['code'],
                    'title' => $title,
                    'message' => $status_info['message'],
                    'time_ago' => $solicitacao->updated_at->diffForHumans(),
                    'time_exact' => $solicitacao->updated_at->format('d/m/Y \à\s H:i'),
                    'link' => route('solicitacoes.show', $solicitacao->id),
                ];
            });

            $notifications = $notifications->merge($notificacoesSolicitacoes);
        }

        // --- NOTIFICAÇÕES DE NOVO ROTEIRO ---
        // Visible to: Técnico, Coordenadores de Curso, Coordenadores de Lab
        // NÃO inclui Admin (nível 5)
        if (in_array($user->nivel, [User::NIVEL_TECNICO, User::NIVEL_COORDENADOR_CURSO, User::NIVEL_COORDENADOR_LABORATORIO])) {
            
            $queryRoteiros = Roteiro::query();
            $queryRoteiros->with(['solicitacao', 'solicitacao.professor', 'solicitacao.laboratorio']);
            $queryRoteiros->where('created_at', '>=', Carbon::now()->subDays(7));

            // Filtrar roteiros apenas daqueles com solicitação aprovada
            $queryRoteiros->whereHas('solicitacao', function (Builder $q) {
                $q->where('status', Solicitacao::STATUS_APROVADA);
            });

            $roteiros = $queryRoteiros->orderBy('created_at', 'desc')->limit(10)->get();

            $notificacoesRoteiros = $roteiros->map(function ($roteiro) {
                return [
                    'id' => $roteiro->id,
                    'type' => 'roteiro',
                    'status' => 'approved',
                    'title' => "NOVO ROTEIRO: {$roteiro->unidade_curricular}",
                    'message' => "Novo roteiro foi anexado à solicitação. Laboratório: {$roteiro->laboratorio}. Professor: {$roteiro->professor}",
                    'time_ago' => $roteiro->created_at->diffForHumans(),
                    'time_exact' => $roteiro->created_at->format('d/m/Y \à\s H:i'),
                    'link' => route('roteiros.show', $roteiro->id),
                ];
            });

            $notifications = $notifications->merge($notificacoesRoteiros);
        }

        // --- ORDENA TODAS AS NOTIFICAÇÕES POR TEMPO (MAIS RECENTES PRIMEIRO) ---
        $notifications = $notifications->sortByDesc(function ($notif) {
            // Extrai o timestamp do campo time_exact para ordenação
            return \Carbon\Carbon::createFromFormat('d/m/Y \à\s H:i', $notif['time_exact']);
        })->take(10); // Limita a 10 notificações totais

        return view('dashboard', [
            'notifications' => $notifications,
        ]);
    }
}
