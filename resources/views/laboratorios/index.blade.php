<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">
            Gerenciamento de Laboratórios
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_TECNICO, \App\Models\User::NIVEL_COORDENADOR_CURSO, \App\Models\User::NIVEL_COORDENADOR_LABORATORIO]))
                <a href="{{ route('laboratorios.create') }}" class="fixed bottom-6 right-6 bg-[#0C2E5C] text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-blue-800 transition duration-200">
                    + Novo Laboratório
                </a>
            @endif

            <div class="p-3 overflow-x-auto">
                <div class="w-full">
                    <!-- Controles: busca -->
                        <div class="mb-4" x-data="{ 
                            query: '',
                            matches(el){
                                const q = this.query.toLowerCase().trim();
                                if(q === '') return true;
                                const qDigits = q.replace(/\D/g, '');
                                const nome = el.dataset.nome || '';
                                const tipo = el.dataset.tipo || '';
                                const local = el.dataset.localizacao || '';
                                const capacidade = (el.dataset.capacidade || '').toString();
                                if(nome.includes(q) || tipo.includes(q) || local.includes(q) || capacidade.includes(q)) return true;
                                if(qDigits !== '' && capacidade.replace(/\D/g, '').includes(qDigits)) return true;
                                return false;
                            }
                        }">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <input x-model="query" type="text" placeholder="Buscar por nome, tipo ou localização..." class="border rounded px-3 py-2 text-sm w-full md:w-80" />
                                <button type="button" @click="query=''" class="px-3 py-2 bg-gray-100 rounded text-sm">Limpar</button>
                            </div>
                        </div>

                        <!-- Cabeçalho (desktop) -->
                        <div class="hidden md:grid grid-cols-5 bg-[#0C2E5C] text-white px-4 py-2 rounded-t-md text-sm font-semibold mt-3">
                            <div>Nome</div>
                            <div>Capacidade</div>
                            <div>Tipo</div>
                            <div>Localização</div>
                            <div class="text-center">Ações</div>
                        </div>

                        <!-- Lista -->
                        <div class="space-y-3 mt-2">
                        @forelse($laboratorios as $lab)
                            @php
                                $nome = strtolower($lab->nome);
                                $tipo = strtolower($lab->tipo);
                                $local = strtolower($lab->localizacao);
                                $capacidade = strtolower((string) $lab->capacidade);
                            @endphp
                            <!-- Desktop: linha -->
                            <div data-nome="{{ $nome }}" data-tipo="{{ $tipo }}" data-localizacao="{{ $local }}" data-capacidade="{{ $capacidade }}" x-show="matches($el)" class="hidden md:grid grid-cols-5 items-center bg-white px-4 py-2 rounded shadow border border-gray-200 hover:shadow-md transition">
                                <div class="text-sm text-gray-700">{{ $lab->nome}}</div>
                                <div class="text-sm text-gray-700">{{ $lab->capacidade}}</div>
                                <div class="text-sm text-gray-700">{{ $lab->tipo}}</div>
                                <div class="text-sm text-gray-700">{{ $lab->localizacao}}</div>
                                <div class="text-center flex justify-center gap-2">
                                    @if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_TECNICO, \App\Models\User::NIVEL_COORDENADOR_CURSO, \App\Models\User::NIVEL_COORDENADOR_LABORATORIO]))
                                        <a href="{{ route('laboratorios.edit', $lab->id) }}" class="me-2">
                                            <x-icons-logo name="edit"/>
                                        </a>

                                        <form action="{{ route('laboratorios.destroy', $lab->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Deseja excluir?')" type="submit">
                                                <x-icons-logo name="delete"/>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Mobile: card -->
                            <div data-nome="{{ $nome }}" data-tipo="{{ $tipo }}" data-localizacao="{{ $local }}" data-capacidade="{{ $capacidade }}" x-show="matches($el)" class="md:hidden bg-white px-4 py-3 rounded shadow border border-gray-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="text-xs text-gray-500">Nome</div>
                                        <div class="text-sm text-gray-700 font-medium">{{ $lab->nome }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Capacidade</div>
                                        <div class="text-sm text-gray-700">{{ $lab->capacidade }}</div>
                                    </div>
                                </div>

                                <div class="mt-2 grid grid-cols-2 gap-2 text-sm text-gray-700">
                                    <div>
                                        <div class="text-xs text-gray-500">Tipo</div>
                                        <div>{{ $lab->tipo }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Localização</div>
                                        <div>{{ $lab->localizacao }}</div>
                                    </div>
                                </div>

                                @if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_TECNICO, \App\Models\User::NIVEL_COORDENADOR_CURSO, \App\Models\User::NIVEL_COORDENADOR_LABORATORIO]))
                                    <div class="mt-3 flex items-center gap-3">
                                        <a href="{{ route('laboratorios.edit', $lab->id) }}" class="text-sm text-[#0C2E5C] font-medium">Editar</a>
                                        <form action="{{ route('laboratorios.destroy', $lab->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Deseja excluir?')" type="submit" class="text-sm text-red-600">Excluir</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-4">
                                Nenhum laboratório encontrado.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>