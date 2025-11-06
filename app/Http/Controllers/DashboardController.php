<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;
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

        // --- ADIÇÃO: Configura o locale para português do Brasil (pt-BR) ---
        Carbon::setLocale('pt_BR');
        // --------------------------------------------------------------------

        $user = auth()->user();

        // --- 1. CONFIGURAÇÃO BASE DA QUERY DE NOTIFICAÇÕES ---
        $query = Solicitacao::query();

        // CORRIGIDO: Removido 'roteiro.curso' e deixado apenas 'roteiro'.
        $query->with(['professor', 'roteiro', 'laboratorio', 'autorizante']);

        // Filtro de status: Busca todos os status que geram notificação
        $query->whereIn('status', [
            Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO, // PENDENTE (Nova)
            Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB, // PENDENTE (Passou 1ª fase)
            Solicitacao::STATUS_APROVADA, // FINAL (Aprovada)
            Solicitacao::STATUS_RECUSADA, // FINAL (Recusada)
        ]);

        // Limita notificações ao período recente: últimas 7 dias
        $query->where('updated_at', '>=', Carbon::now()->subDays(7));

        // --- 2. FILTRAGEM POR NÍVEL DE USUÁRIO ---
        if ($user->nivel == User::NIVEL_PROFESSOR) {
            // Professor: Vê apenas atualizações de SUAS solicitações, mas só após o primeiro passo (Status 2, 3, 4).
            $query->where('professor_id', $user->id)
                ->whereIn('status', [Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB, Solicitacao::STATUS_APROVADA, Solicitacao::STATUS_RECUSADA]);
        } elseif ($user->nivel == User::NIVEL_COORDENADOR_CURSO) {
            // Coordenador de Curso (CC):
            $query->where(function (Builder $q) use ($user) {
                // PENDENTE: Status 1 - Requer aprovação dele.
                // MOSTRA TODAS as pendentes de Status 1 (Filtro de Curso removido).
                $q->where('status', Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO)

                    // AUTORIZADAS/RECUSADAS: Vê as solicitações que ELE já autorizou/recusou (Status 3 e 4).
                    ->orWhere(function (Builder $orQ) use ($user) {
                        $orQ->whereIn('status', [Solicitacao::STATUS_APROVADA, Solicitacao::STATUS_RECUSADA])
                            ->where('autorizante_id', $user->id);
                    });
            });
        } elseif ($user->nivel == User::NIVEL_COORDENADOR_LABORATORIO) {
            // Coordenador de Laboratório (CL):
            $query->where(function (Builder $q) use ($user) {
                // PENDENTE: Status 2 - Requer aprovação dele.
                // FILTRO DE LABORATÓRIO REMOVIDO: MOSTRA TODAS as pendentes de Status 2.
                $q->where('status', Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB)

                    // AUTORIZADAS/RECUSADAS: Vê as solicitações que ELE já autorizou/recusou (Status 3 e 4)
                    ->orWhere('autorizante_id', $user->id);
            });
        }
        // Admin/Técnico veem as últimas 10 alterações (sem filtro adicional).

        // --- 3. EXECUÇÃO DA QUERY E FORMATAÇÃO ---

        $notifications = $query
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($solicitacao) use ($user) {

                // Determina o texto e o código de status para exibição
                $status_info = match ($solicitacao->status) {

                    // --- STATUS DE APROVAÇÃO PENDENTE ---
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

                    // --- STATUS FINAIS ---
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

                // Se estiver pendente para o usuário, ajusta o título
                $title = ($status_info['is_pending_for_me'] ?? false)
                    ? "🚨 PENDENTE DE APROVAÇÃO: Solicit. #{$solicitacao->id}"
                    : "Solicitação {$solicitacao->id}: {$status_info['text']}";


                return [
                    'id' => $solicitacao->id,
                    'status' => $status_info['code'],
                    'title' => $title,
                    'message' => $status_info['message'],

                    // RETORNA O TEMPO RELATIVO ("há X tempo")
                    'time_ago' => $solicitacao->updated_at->diffForHumans(),
                    // NOVO CAMPO: RETORNA O TEMPO EXATO ("DD/MM/AAAA HH:MM")
                    'time_exact' => $solicitacao->updated_at->format('d/m/Y \à\s H:i'),

                    'link' => route('solicitacoes.show', $solicitacao->id),
                ];
            });

        // --- 4. Renderiza a View ---

        return view('dashboard', [
            'notifications' => $notifications,
        ]);
    }
}
