<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">
            Gerenciamento de roteiros
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_PROFESSOR]))
                <a href="{{ route('roteiros.create') }}" class="fixed bottom-6 right-6 bg-[#0C2E5C] text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-blue-800 transition duration-200">
                    + Novo roteiro
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
                            const curso = el.dataset.curso || '';
                            const professor = el.dataset.professor || '';
                            const unidade = el.dataset.unidade || '';
                            const periodo = (el.dataset.periodo || '').toString();
                            if(curso.includes(q) || professor.includes(q) || unidade.includes(q) || periodo.includes(q)) return true;
                            if(qDigits !== '' && periodo.replace(/\D/g, '').includes(qDigits)) return true;
                            return false;
                        }
                    }">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <input x-model="query" type="text" placeholder="Buscar por curso, professor ou unidade..." class="border rounded px-3 py-2 text-sm w-full md:w-80" />
                                <button type="button" @click="query=''" class="px-3 py-2 bg-gray-100 rounded text-sm">Limpar</button>
                            </div>
                        </div>

                        <!-- Cabeçalho (desktop) -->
                        <div class="hidden md:grid grid-cols-7 space-x-2 flex bg-[#0C2E5C] text-white px-4 py-2 rounded-t-md text-sm font-semibold mt-3">
                            <div class="text-left">Curso</div>
                            <div class="text-center">Unidade curricular</div>
                            <div class="text-center">Professor</div>
                            <div class="text-center">Período</div>
                            <div class="text-center">Qtd. alunos</div>
                            <div class="text-center">Data</div>                  
                            <div class="text-center">Ações</div>
                        </div>

                        <!-- Lista -->
                        <div class="space-y-3 mt-2">
                            @forelse($roteiros as $roteiro)
                                @php
                                    $curso = strtolower($roteiro->curso);
                                    $unidade = strtolower($roteiro->unidade_curricular);
                                    $professor = strtolower($roteiro->professor);
                                    $periodo = strtolower($roteiro->periodo);
                                @endphp
                                    <!-- Desktop: linha -->
                                <div data-curso="{{ $curso }}" data-professor="{{ $professor }}" data-unidade="{{ $unidade }}" data-periodo="{{ $periodo }}" x-show="matches($el)" class="hidden md:grid grid-cols-7 space-x-2 items-center bg-white px-4 py-2 rounded shadow border border-gray-200 hover:shadow-md transition">
                                    
                                    <div class="text-sm text-gray-700 text-left">{{ $roteiro->curso }}</div>
                                    <div class="text-sm text-gray-700 text-center">{{ $roteiro->unidade_curricular }}</div>
                                    <div class="text-sm text-gray-700 text-center">{{ $roteiro->professor }}</div>
                                    <div class="text-sm text-gray-700 text-center">{{ $roteiro->periodo }}</div>
                                    <div class="text-sm text-gray-700 text-center">{{ $roteiro->quantidade_alunos }}</div>
                                    <div class="text-sm text-gray-700 text-center">{{ \Carbon\Carbon::parse($roteiro->data)->format('d/m/Y') }}</div>
                                    <div class="text-center flex justify-center gap-2">
                                        @if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_PROFESSOR, \App\Models\User::NIVEL_COORDENADOR_LABORATORIO]))
                                            <a href="{{ route('roteiros.show', $roteiro->id) }}" class="text-sm text-yellow-600 font-medium" title="Visualizar Roteiro">
                                                <x-icons-logo name="show"/>
                                            </a>
                                            <a href="{{ route('roteiros.edit', $roteiro->id) }}" class="" title="Editar Roteiro">
                                                <x-icons-logo name="edit"/>
                                            </a>
                                            <form action="{{ route('roteiros.destroy', $roteiro->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Deseja excluir?')" type="submit" title="Excluir Roteiro">
                                                    <x-icons-logo name="delete"/>
                                                </button>
                                            </form>                                    
                                            <a href="{{ route('roteiros.pdf', $roteiro->id) }}" class="text-sm text-blue-600 font-medium" target="_blank" title="Criar PDF">
                                                <x-icons-logo name="document"/>
                                            </a>
                                        @else
                                            <a href="{{ route('roteiros.show', $roteiro->id) }}" class="text-sm text-yellow-600 font-medium" title="Visualizar Roteiro">
                                                <x-icons-logo name="show"/>
                                            </a>
                                            <a href="{{ route('roteiros.pdf', $roteiro->id) }}" class="text-sm text-blue-600 font-medium" target="_blank" title="Criar PDF">
                                                <x-icons-logo name="document"/>
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <!-- Mobile: card -->
                                <div data-curso="{{ $curso }}" data-professor="{{ $professor }}" data-unidade="{{ $unidade }}" data-periodo="{{ $periodo }}" x-show="matches($el)" class="md:hidden bg-white px-4 py-3 rounded shadow border border-gray-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="text-xs text-gray-500">Curso</div>
                                            <div class="text-sm text-gray-700 font-medium">{{ $roteiro->curso }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500">Data</div>
                                            <div class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($roteiro->data)->format('d/m/Y') }}</div>
                                        </div>
                                    </div>

                                    <div class="mt-2 grid grid-cols-2 gap-2 text-sm text-gray-700">
                                        <div>
                                            <div class="text-xs text-gray-500">Professor</div>
                                            <div>{{ $roteiro->professor }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Período</div>
                                            <div>{{ $roteiro->periodo }}</div>
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <div class="text-xs text-gray-500">Quantidade de alunos</div>
                                        <div class="text-sm text-gray-700">{{ $roteiro->quantidade_alunos }}</div>
                                    </div>
                                    
                                    <div class="mt-3 flex items-center gap-2">
                                        @if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_PROFESSOR, \App\Models\User::NIVEL_COORDENADOR_LABORATORIO]))
                                        <!-- Botão para Gerar PDF -->
                                        <a href="{{ route('roteiros.pdf', $roteiro->id) }}" class="text-sm text-blue-600 font-medium" target="_blank" title="Criar PDF">Criar PDF</a>
                                        <a href="{{ route('roteiros.edit', $roteiro->id) }}" class="text-sm text-[#0C2E5C] font-medium" title="Editar roteiro">Editar</a>
                                        <form action="{{ route('roteiros.destroy', $roteiro->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Deseja excluir?')" type="submit" class="cursor-pointer text-sm text-red-600 font-medium" title="Excluir roteiro">Excluir</button>
                                        </form>
                                        <!-- Ícone de Visualizar (Olho) -->
                                        <a href="{{ route('roteiros.show', $roteiro->id) }}" class="text-sm text-yellow-600 font-medium size-4" title="Visualizar roteiro">
                                            <x-icons-logo name="show"/>
                                        </a> 
                                        @else
                                        <a href="{{ route('roteiros.pdf', $roteiro->id) }}" class="text-sm text-blue-600 font-medium" target="_blank" title="Criar PDF">Criar PDF</a>
                                        <!-- Ícone de Visualizar (Olho) -->
                                        <a href="{{ route('roteiros.show', $roteiro->id) }}" class="text-sm text-yellow-600 font-medium size-4" title="Visualizar roteiro">
                                            <x-icons-logo name="show"/>
                                        </a> 
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-gray-500 py-4">
                                    Nenhum roteiro encontrado.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
