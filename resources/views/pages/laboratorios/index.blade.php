<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">
            Laboratórios
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_TECNICO, \App\Models\User::NIVEL_COORDENADOR_CURSO, \App\Models\User::NIVEL_COORDENADOR_LABORATORIO]))
                <a href="{{ route('laboratorios.create') }}" class="fixed bottom-6 right-6 bg-[#0C2E5C] text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-blue-800 transition duration-200">
                    + Novo laboratório
                </a>
            @endif

            <div class="p-6 overflow-x-auto">
                <div class="w-full">
                    <!-- Cabeçalho -->
                    <div class="grid grid-cols-5 bg-[#0C2E5C] text-white px-4 py-2 rounded-t-md text-sm font-semibold">
                        <div>Nome</div>
                        <div>Capacidade</div>
                        <div>Tipo</div>
                        <div>Localização</div>
                        <div class="text-center">Ações</div>
                    </div>

                    <!-- Corpo da Tabela -->
                    <div class="space-y-1 mt-2">
                        @foreach($laboratorios as $lab)
                            <div class="grid grid-cols-5 items-center bg-white px-4 py-2 rounded shadow border border-gray-200 hover:shadow-md transition">
                                <div class="text-sm text-gray-700">{{ $lab->nome}}</div>
                                <div class="text-sm text-gray-700">{{ $lab->capacidade}}</div>
                                <div class="text-sm text-gray-700">{{ $lab->tipo}}</div>
                                <div class="text-sm text-gray-700">{{ $lab->localizacao}}</div>
                                <div class="text-center flex justify-center gap-2">
                                    <!-- Editar -->
                                    <a href="{{ route('laboratorios.edit', parameters: $lab->id) }}">
                                        <div class="relative group inline-block">
                                            <x-icons-logo name="edit"/>

                                            <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition duration-200 pointer-events-none">
                                                Editar
                                            </div>
                                        </div>
                                    </a>
                                   
                                    <!-- Ícone de Excluir -->
                                    <form action="{{ route('laboratorios.destroy', $lab->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Deseja excluir?')" type="submit">
                                            <div class="cursor-pointer relative group inline-block">
                                                <x-icons-logo name="delete"/>
                                                <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition duration-200 pointer-events-none">
                                                    Excluir
                                                </div>
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>