<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">
            Gerenciamento de Solicitações
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            {{-- Mensagens de feedback (Success/Error) --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Botão "Nova Solicitação" (apenas para Professor) --}}
            @if(Auth::user()->nivel == \App\Models\User::NIVEL_PROFESSOR)
                <a href="{{ route('solicitacoes.create') }}" class="fixed bottom-6 right-6 bg-[#0C2E5C] text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-blue-800 transition duration-200 z-10">
                    + Nova Solicitação
                </a>
            @endif

            <div class="p-3 overflow-x-auto">
                <div class="w-full">
                    
                    {{-- Controles: busca com Alpine.js --}}
                    <div class="mb-4" x-data="{ 
                        query: '',
                        matches(el){
                            const q = this.query.toLowerCase().trim();
                            if(q === '') return true;
                            
                            const unidade = el.dataset.unidade || '';
                            const laboratorio = el.dataset.laboratorio || '';
                            const status = el.dataset.status || '';
                            const professor = el.dataset.professor || '';
                            
                            // Busca por Unidade, Laboratório, Status ou Professor
                            return unidade.includes(q) || laboratorio.includes(q) || status.includes(q) || professor.includes(q);
                        }
                    }">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <input x-model="query" type="text" placeholder="Buscar por unidade, laboratório, professor ou status..." class="border rounded px-3 py-2 text-sm w-full md:w-80" />
                                <button type="button" @click="query=''" class="px-3 py-2 bg-gray-100 rounded text-sm">Limpar</button>
                            </div>
                        </div>

                        {{-- Cabeçalho (desktop) --}}
                        <div class="hidden md:grid bg-[#0C2E5C] text-white px-4 py-3 rounded-t-md text-sm font-semibold mt-3" style="grid-template-columns: 0.75fr 1.25fr 1fr 1fr 1fr 1.25fr;">
                            <div class="text-left">Data Solicitada</div>
                            <div class="text-center">Horário</div>
                            <div class="text-center">Laboratório</div>
                            <div class="text-center">Professor</div>
                            <div class="text-center">Status</div>
                            <div class="text-center">Ações</div>
                        </div>

                        {{-- Lista --}}
                        <div class="space-y-3 mt-2">
                        @forelse($solicitacoes as $solicitacao)
                            @php
                                // LÓGICA DE DESTAQUE: Checa se este item exige a ação do usuário logado
                                $requerAcao = (
                                    (auth()->user()->nivel == \App\Models\User::NIVEL_COORDENADOR_CURSO && $solicitacao->status == \App\Models\Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO) ||
                                    (auth()->user()->nivel == \App\Models\User::NIVEL_COORDENADOR_LABORATORIO && $solicitacao->status == \App\Models\Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB)
                                );
                                
                                // Prepara dados para busca (usando status_texto para busca)
                                $unidade = strtolower($solicitacao->horario_inicio ?? '');
                                $laboratorio = strtolower($solicitacao->laboratorio->nome ?? '');
                                $status = strtolower($solicitacao->status_texto); // Usando status_texto para busca!
                                $professor = strtolower($solicitacao->professor->name ?? '');
                                $dataDisplay = $solicitacao->created_at ? $solicitacao->created_at->format('d/m/Y') : 'N/A';
                            @endphp

                            {{-- Desktop: linha --}}
                            <div data-unidade="{{ $unidade }}" data-laboratorio="{{ $laboratorio }}" data-status="{{ $status }}" data-professor="{{ $professor }}" x-show="matches($el)" 
                                style="grid-template-columns: 0.75fr 1.25fr 1fr 1fr 1fr 1.25fr;" class="hidden md:grid items-center bg-white px-4 py-3 rounded shadow border border-gray-200 hover:shadow-lg transition
                                {{ $requerAcao ? 'border-l-4 border-yellow-500' : '' }}"> {{-- DESTAQUE VISUAL AQUI --}}
                                
                                <div class="text-sm text-gray-700 text-left">{{ $dataDisplay }}</div>
                                <div class="text-sm text-gray-700 text-center">{{ \Carbon\Carbon::parse($solicitacao->horario_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($solicitacao->horario_fim)->format(format: 'H:i') }}</div>
                                <div class="text-sm text-gray-700 text-center">{{ $solicitacao->laboratorio->nome ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-700 text-center">{{ $solicitacao->professor->name ?? 'N/A' }}</div>
                                
                                {{-- Exibição do Status com cor --}}
                                <div class="text-center">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if ($solicitacao->status === \App\Models\Solicitacao::STATUS_APROVADA) bg-green-100 text-green-800 
                                        @elseif ($solicitacao->status === \App\Models\Solicitacao::STATUS_RECUSADA) bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $solicitacao->status_texto }}
                                    </span>
                                </div>
                                
                                <div class="text-center flex justify-center gap-2">
                                    {{-- Link de Visualização/Aprovação --}}
                                    <a href="{{ route('solicitacoes.show', $solicitacao->id) }}" title="{{ $requerAcao ? 'Revisar e Agir' : 'Visualizar Detalhes' }}" class="{{ $requerAcao ? 'text-yellow-600 font-bold' : 'text-[#0C2E5C] hover:text-blue-800' }}">
                                        {{-- Ícone de Olho SVG para Ação --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    {{-- Botão de Exclusão --}}
                                    @if(Auth::user()->nivel == \App\Models\User::NIVEL_PROFESSOR && $solicitacao->professor_id == Auth::id() && $solicitacao->status != \App\Models\Solicitacao::STATUS_APROVADA && $solicitacao->status != \App\Models\Solicitacao::STATUS_RECUSADA)
                                        <form action="{{ route('solicitacoes.destroy', $solicitacao->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Deseja realmente excluir esta solicitação?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Excluir Solicitação">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 hover:text-red-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            {{-- Mobile: card --}}
                            <div data-unidade="{{ $unidade }}" data-laboratorio="{{ $laboratorio }}" data-status="{{ $status }}" data-professor="{{ $professor }}" x-show="matches($el)" 
                                class="md:hidden bg-white px-4 py-3 rounded shadow border border-gray-200 {{ $requerAcao ? 'border-l-4 border-yellow-500' : '' }}"> {{-- DESTAQUE VISUAL AQUI --}}
                                
                                <div class="flex justify-between items-center mb-2">
                                    <div class="text-xs text-gray-500">Solicitação #{{ $solicitacao->id }}</div>
                                    {{-- Status para Mobile --}}
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if ($solicitacao->status === \App\Models\Solicitacao::STATUS_APROVADA) bg-green-100 text-green-800
                                        @elseif ($solicitacao->status === \App\Models\Solicitacao::STATUS_RECUSADA) bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $solicitacao->status_texto }}
                                    </span>
                                </div>

                                <div class="text-base text-gray-800 font-semibold mb-1">{{ \Carbon\Carbon::parse($solicitacao->horario_inicio ?? 'N/A')->format('H:i') }} - {{ \Carbon\Carbon::parse($solicitacao->horario_fim ?? 'N/A')->format('H:i') }}</div>
                                <div class="text-sm text-gray-600 mb-2">Lab: {{ $solicitacao->laboratorio->nome ?? 'N/A' }}</div>

                                <div class="grid grid-cols-2 gap-2 text-sm text-gray-700 border-t pt-2">
                                    <div>
                                        <div class="text-xs text-gray-500">Professor</div>
                                        <div>{{ $solicitacao->professor->name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Data Solicitada</div>
                                        <div>{{ $dataDisplay }}</div>
                                    </div>
                                </div>
                                
                                <div class="mt-3 flex justify-between items-center border-t pt-3">
                                    <a href="{{ route('solicitacoes.show', $solicitacao->id) }}" class="text-sm font-medium {{ $requerAcao ? 'text-yellow-600' : 'text-blue-600 hover:text-blue-800' }}">
                                        {{ $requerAcao ? 'Revisar e Agir' : 'Visualizar Detalhes' }}
                                    </a>

                                    {{-- Botão de Exclusão --}}
                                    @if(Auth::user()->nivel == \App\Models\User::NIVEL_PROFESSOR && $solicitacao->professor_id == Auth::id() && $solicitacao->status != \App\Models\Solicitacao::STATUS_APROVADA && $solicitacao->status != \App\Models\Solicitacao::STATUS_RECUSADA)
                                        <form action="{{ route('solicitacoes.destroy', $solicitacao->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Deseja realmente excluir esta solicitação?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="submit" class="text-sm text-red-600 font-medium">Excluir</x-button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                        <div class="text-center text-gray-500 py-6 bg-white rounded-lg shadow-md">
                            <p class="text-gray-500">Nenhuma solicitação encontrada.</p>
                            @if(auth()->user()->nivel == \App\Models\User::NIVEL_PROFESSOR)
                            <a href="{{ route('solicitacoes.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                Faça sua primeira solicitação
                            </a>
                            @endif
                        </div>
                        @endforelse
                        </div>

                        {{-- Paginação (somente se a variável $solicitacoes for uma instância de Paginator) --}}
                        @if (method_exists($solicitacoes, 'links'))
                            <div class="mt-4">
                                {{ $solicitacoes->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>