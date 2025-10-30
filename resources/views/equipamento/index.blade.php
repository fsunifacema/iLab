<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">
            Gerenciamento de Equipamentos
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_TECNICO, \App\Models\User::NIVEL_COORDENADOR_CURSO, \App\Models\User::NIVEL_COORDENADOR_LABORATORIO]))
                <a href="{{ route('equipamentos.create') }}" class="fixed bottom-6 right-6 bg-[#0C2E5C] text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-blue-800 transition duration-200 z-30">
                    + Novo Equipamento
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
                            const nome = el.dataset.nome || '';
                            const marca = el.dataset.marca || '';
                            const modelo = el.dataset.modelo || '';
                            return nome.includes(q) || marca.includes(q) || modelo.includes(q);
                        }
                    }">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <input x-model="query" type="text" placeholder="Buscar por nome, marca ou modelo..." class="border rounded px-3 py-2 text-sm w-full md:w-80" />
                                <button type="button" @click="query=''" class="px-3 py-2 bg-gray-100 rounded text-sm">Limpar</button>
                            </div> 
                        </div>
                        <!-- Cabeçalho (desktop) --> 
                        @php $nivel = Auth::user()->nivel; @endphp
                        @php $desktopCols = $nivel != \App\Models\User::NIVEL_PROFESSOR ? 'grid-cols-4' : 'grid-cols-3'; @endphp
                        <div class="hidden md:grid {{ $desktopCols }} bg-[#0C2E5C] text-white px-4 py-2 rounded-t-md text-sm font-semibold mt-3">
                            <div>Nome</div>
                            <div>Marca</div>
                            <div>Modelo</div>
                            @if($nivel != \App\Models\User::NIVEL_PROFESSOR)
                                <div class="text-center">Ações</div>
                            @endif
                        </div>

                        <!-- Lista -->
                        <div class="space-y-3 mt-2">
                            @forelse($equipamentos as $equipamento)
                                @php
                                    $nome = strtolower($equipamento->nome);
                                    $marca = strtolower($equipamento->marca);
                                    $modelo = strtolower($equipamento->modelo);
                                @endphp
                                <!-- Desktop: linha -->
                                <div data-nome="{{ $nome }}" data-marca="{{ $marca }}" data-modelo="{{ $modelo }}" x-show="matches($el)" class="hidden md:grid {{ $desktopCols }} items-center bg-white px-4 py-2 rounded shadow border border-gray-200 hover:shadow-md transition">
                                    <div class="text-sm text-gray-700 truncate">{{ $equipamento->nome }}</div>
                                    <div class="text-sm text-gray-700 truncate">{{ $equipamento->marca }}</div>
                                    <div class="text-sm text-gray-700 truncate">{{ $equipamento->modelo }}</div>

                                    @if($nivel != \App\Models\User::NIVEL_PROFESSOR)
                                        <div class="text-center flex justify-center gap-2">
                                            @if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_TECNICO, \App\Models\User::NIVEL_COORDENADOR_CURSO, \App\Models\User::NIVEL_COORDENADOR_LABORATORIO]))
                                                <a href="{{ route('equipamentos.edit', $equipamento->id) }}" class="me-2">
                                                    <x-icons-logo name="edit"/>
                                                </a>

                                                <form action="{{ route('equipamentos.destroy', $equipamento->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button onclick="return confirm('Deseja excluir?')" type="submit">
                                                        <x-icons-logo name="delete"/>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Mobile: cartão -->
                                <div data-nome="{{ $nome }}" data-marca="{{ $marca }}" data-modelo="{{ $modelo }}" x-show="matches($el)" class="md:hidden bg-white px-4 py-3 rounded shadow border border-gray-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="text-xs text-gray-500">Nome</div>
                                            <div class="text-sm text-gray-700 font-medium">{{ $equipamento->nome }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500">Modelo</div>
                                            <div class="text-sm text-gray-700">{{ $equipamento->modelo }}</div>
                                        </div>
                                    </div>
                                    <div class="mt-2 grid grid-cols-2 gap-2 text-sm text-gray-700">
                                        <div>
                                            <div class="text-xs text-gray-500">Marca</div>
                                            <div>{{ $equipamento->marca }}</div>
                                        </div>
                                        @if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_TECNICO, \App\Models\User::NIVEL_COORDENADOR_CURSO, \App\Models\User::NIVEL_COORDENADOR_LABORATORIO]))
                                            <div class="mt-3 flex items-center gap-3">
                                                <a href="{{ route('equipamentos.edit', $equipamento->id) }}" class="text-sm text-[#0C2E5C] font-medium">Editar</a>
                                                <form action="{{ route('equipamentos.destroy', $equipamento->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button onclick="return confirm('Deseja excluir?')" type="submit" class="text-sm text-red-600 font-medium">Excluir</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-gray-500 py-4">
                                    Nenhum equipamento encontrado.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>