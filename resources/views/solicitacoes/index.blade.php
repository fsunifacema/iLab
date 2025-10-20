<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">
            {{ __('Solicitações Pendentes de Aprovação') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
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

            {{-- Botão para Criar Nova Solicitação --}}
            @if(Auth::user()->nivel == \App\Models\User::NIVEL_PROFESSOR)
                <a href="{{ route('solicitacoes.create') }}" class="fixed bottom-6 right-6 bg-green-600 text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-green-700 transition duration-200 z-10">
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
                            
                            return unidade.includes(q) || laboratorio.includes(q) || status.includes(q);
                        }
                    }">
                        <div class="p-1 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <input x-model="query" type="text" placeholder="Buscar por unidade, laboratório ou status..." class="border rounded px-3 py-2 text-sm w-full md:w-80" />
                                <button type="button" @click="query=''" class="px-3 py-2 bg-gray-100 rounded text-sm">Limpar</button>
                            </div>
                        </div>

                        {{-- Cabeçalho (desktop) --}}
                        <div class="hidden md:grid grid-cols-6 bg-[#0C2E5C] text-white px-4 py-3 rounded-t-md text-sm font-semibold mt-3">
                            <div>Data Solicitada</div>
                            <div>Unidade Curricular</div>
                            <div>Laboratório</div>
                            <div>Nível Atual</div>
                            <div>Status</div>
                            <div class="text-center">Ações</div>
                        </div>

                        {{-- Lista --}}
                        <div class="space-y-3 mt-2">
                        @forelse($solicitacoes as $solicitacao)
                            @php
                                // Prepara dados para busca em minúsculo
                                $unidade = strtolower($solicitacao->roteiro->unidade_curricular ?? '');
                                $laboratorio = strtolower($solicitacao->laboratorio->nome ?? '');
                                $status = strtolower($solicitacao->status);
                                $dataDisplay = $solicitacao->data_solicitada ? $solicitacao->data_solicitada->format('d/m/Y') : 'N/A';
                            @endphp

                            {{-- Desktop: linha --}}
                            <div data-unidade="{{ $unidade }}" data-laboratorio="{{ $laboratorio }}" data-status="{{ $status }}" x-show="matches($el)" class="hidden md:grid grid-cols-6 items-center bg-white px-4 py-3 rounded shadow border border-gray-200 hover:shadow-lg transition">
                                
                                <div class="text-sm text-gray-700">{{ $dataDisplay }}</div>
                                <div class="text-sm text-gray-700">{{ $solicitacao->roteiro->unidade_curricular ?? 'Roteiro Excluído' }}</div>
                                <div class="text-sm text-gray-700">{{ $solicitacao->laboratorio->nome ?? 'N/A' }}</div>
                                
                                <div class="text-sm font-semibold text-[#0C2E5C]">
                                    Nível {{ $solicitacao->nivel_atual }}
                                </div>

                                <div>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if ($solicitacao->status === 'aprovado') bg-green-100 text-green-800
                                        @elseif ($solicitacao->status === 'rejeitado') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($solicitacao->status) }}
                                    </span>
                                </div>
                                
                                <div class="text-center">
                                    {{-- Botão de Visualização/Aprovação --}}
                                    <a href="{{ route('solicitacoes.edit', $solicitacao->id) }}" class="text-[#0C2E5C] hover:text-blue-800">
                                        {{-- Ícone de Olho SVG para Ação --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            {{-- Mobile: card --}}
                            <div data-unidade="{{ $unidade }}" data-laboratorio="{{ $laboratorio }}" data-status="{{ $status }}" x-show="matches($el)" class="md:hidden bg-white px-4 py-3 rounded shadow border border-gray-200">
                                <div class="flex justify-between items-center mb-2">
                                    <div class="text-xs text-gray-500">Solicitação #{{ $solicitacao->id }}</div>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if ($solicitacao->status === 'aprovado') bg-green-100 text-green-800
                                        @elseif ($solicitacao->status === 'rejeitado') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($solicitacao->status) }}
                                    </span>
                                </div>
                                <div class="text-base text-gray-800 font-semibold mb-1">{{ $solicitacao->roteiro->unidade_curricular ?? 'Roteiro Excluído'}}</div>
                                <div class="text-sm text-gray-600 mb-2">Lab: {{ $solicitacao->laboratorio->nome ?? 'N/A' }}</div>

                                <div class="grid grid-cols-3 gap-2 text-sm text-gray-700 border-t pt-2">
                                    <div>
                                        <div class="text-xs text-gray-500">Data</div>
                                        <div>{{ $dataDisplay }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Criador</div>
                                        <div>{{ $solicitacao->criador->name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Ação no Nível</div>
                                        <div class="font-bold text-[#0C2E5C]">Nível {{ $solicitacao->nivel_atual }}</div>
                                    </div>
                                </div>
                                
                                <div class="mt-3 text-center">
                                    <a href="{{ route('solicitacoes.edit', $solicitacao->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                        Revisar e Aprovar
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-6 bg-white rounded-lg shadow-md">
                                Não há solicitações pendentes no seu nível de aprovação.
                            </div>
                        @endforelse
                        </div>

                        {{-- Paginação --}}
                        <div class="mt-4">
                            {{ $solicitacoes->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
