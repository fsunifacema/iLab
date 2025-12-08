<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">
            Gerenciamento de Insumos
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_TECNICO, \App\Models\User::NIVEL_COORDENADOR_CURSO, \App\Models\User::NIVEL_COORDENADOR_LABORATORIO]))
                <a href="{{ route('insumos.create') }}" class="fixed bottom-6 right-6 bg-[#0C2E5C] text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-blue-800 transition duration-200">
                    + Novo Insumo
                </a>
            @endif

            <div class="p-3 overflow-x-auto">
                <div class="w-full">

                    <!-- Controles: busca + filtros -->
                    <div class="mb-4" x-data="{ filter: 'all', query: '' }">
                        <div class="mb-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <input x-model="query" type="text" placeholder="Buscar por nome, marca ou modelo..." class="border rounded px-3 py-2 text-sm w-full md:w-80" />
                                <button type="button" @click="query=''" class="cursor-pointer px-3 py-2 bg-gray-100 rounded text-sm">Limpar</button>
                            </div>


                            @php $nivel = Auth::user()->nivel; @endphp
                            @if($nivel != \App\Models\User::NIVEL_PROFESSOR)
                                <div class=" items-center text-center gap-2">
                                    <button type="button" @click="filter='all'" :class="filter==='all' ? 'px-3 py-1 rounded text-sm bg-[#0C2E5C] text-white' : 'px-3 py-1 rounded text-sm bg-gray-100 text-gray-700'">Todos</button>
                                    <button type="button" @click="filter='vencidos'" :class="filter==='vencidos' ? 'px-3 py-1 rounded text-sm bg-red-600 text-white' : 'px-3 py-1 rounded text-sm bg-gray-100 text-gray-700'">Vencidos</button>
                                    <button type="button" @click="filter='proximos'" :class="filter==='proximos' ? 'px-3 py-1 rounded text-sm bg-yellow-500 text-white' : 'px-3 py-1 rounded text-sm bg-gray-100 text-gray-700'">Próximos (7d)</button>
                                    <button type="button" @click="filter='sem'" :class="filter==='sem' ? 'px-3 py-1 rounded text-sm bg-indigo-600 text-white' : 'px-3 py-1 rounded text-sm bg-gray-100 text-gray-700'">Sem validade</button>
                                </div>
                            @endif
                        </div>

                        <!-- Cabeçalho da Tabela (apenas desktop) -->
                        @php $nivel = Auth::user()->nivel; @endphp
                        @php $desktopCols = $nivel != \App\Models\User::NIVEL_PROFESSOR ? 'grid-cols-6' : 'grid-cols-4'; @endphp
                        <div class="hidden md:grid {{ $desktopCols }} bg-[#0C2E5C] text-white px-4 py-2 rounded-t-md text-sm font-semibold">
                            <div class="text-center">Nome</div>
                            <div class="text-center">Marca</div>
                            <div class="text-center">Modelo</div>
                            @if($nivel != \App\Models\User::NIVEL_PROFESSOR)
                                <div class="text-center">Validade</div>
                                <div class="text-center">Descrição</div>
                                <div class="text-center">Ações</div>
                            @else
                                <div class="text-center">Descrição</div>
                            @endif
                        </div>

                        <!-- Lista (container) -->
                        <div class="space-y-3 mt-2">
                        @forelse ($insumos as $insumo)
                            @php
                                $validade = $insumo->validade ? \Carbon\Carbon::parse($insumo->validade) : null;
                                $vencido = $validade ? $validade->isPast() : false;
                                $proximo = $validade ? $validade->diffInDays(now()) <= 7 && $validade->isFuture() : false;
                            @endphp
                            <!-- Desktop: linha em grade -->
                            @php
                                $validade = $insumo->validade ? \Carbon\Carbon::parse($insumo->validade) : null;
                                $vencido = $validade ? $validade->isPast() : false;
                            @endphp
                            <div data-vencido="{{ $vencido ? 'true' : 'false' }}" data-proximo="{{ $proximo ? 'true' : 'false' }}" data-sem="{{ $validade ? 'false' : 'true' }}" data-nome="{{ strtolower($insumo->nome) }}" data-marca="{{ strtolower($insumo->marca) }}" data-modelo="{{ strtolower($insumo->modelo) }}" x-show="(filter==='all' || (filter==='vencidos' && $el.dataset.vencido === 'true') || (filter==='proximos' && $el.dataset.proximo === 'true') || (filter==='sem' && $el.dataset.sem === 'true')) && (query === '' || $el.dataset.nome.includes(query.toLowerCase().trim()) || $el.dataset.marca.includes(query.toLowerCase().trim()) || $el.dataset.modelo.includes(query.toLowerCase().trim()))" class="hidden md:grid {{ $desktopCols }} items-center bg-white px-4 py-2 rounded shadow border border-gray-200 hover:shadow-md transition">
                                <div class="text-sm text-center text-gray-700">{{ $insumo->nome }}</div>
                                <div class="text-sm text-center text-gray-700">{{ $insumo->marca }}</div>
                                <div class="text-sm text-center text-gray-700">{{ $insumo->modelo }}</div>
                                @if($nivel != \App\Models\User::NIVEL_PROFESSOR)
                                    @php
                                        $validade = $insumo->validade ? \Carbon\Carbon::parse($insumo->validade) : null;
                                        $vencido = $validade ? $validade->isPast() : false;
                                    @endphp
                                    <div class="text-sm text-center {{ $vencido ? 'text-red-600 font-semibold' : 'text-gray-700' }}">{{ $validade ? $validade->format('d/m/Y') : 'N/A' }}</div>
                                    <div class="text-sm text-center text-gray-700 truncate max-w-xs">{{ Str::limit($insumo->descricao, 50) }}</div>
                                    <div class="text-center flex justify-center gap-2">
                                        @if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_TECNICO, \App\Models\User::NIVEL_COORDENADOR_CURSO, \App\Models\User::NIVEL_COORDENADOR_LABORATORIO]))
                                            <a href="{{ route('insumos.edit', $insumo->id) }}" class="me-2">
                                                <x-icons-logo name="edit"/>
                                            </a>

                                            <form action="{{ route('insumos.destroy', $insumo->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Deseja excluir?')" type="submit">
                                                    <x-icons-logo name="delete"/>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-sm text-gray-700 truncate max-w-xs text-center">{{ Str::limit($insumo->descricao, 50) }}</div>
                                @endif
                            </div>

                            <!-- Mobile: bloco vertical com labels -->
                            <div data-vencido="{{ $vencido ? 'true' : 'false' }}" data-proximo="{{ $proximo ? 'true' : 'false' }}" data-sem="{{ $validade ? 'false' : 'true' }}" data-nome="{{ strtolower($insumo->nome) }}" data-marca="{{ strtolower($insumo->marca) }}" data-modelo="{{ strtolower($insumo->modelo) }}" x-show="(filter==='all' || (filter==='vencidos' && $el.dataset.vencido === 'true') || (filter==='proximos' && $el.dataset.proximo === 'true') || (filter==='sem' && $el.dataset.sem === 'true')) && (query === '' || $el.dataset.nome.includes(query.toLowerCase().trim()) || $el.dataset.marca.includes(query.toLowerCase().trim()) || $el.dataset.modelo.includes(query.toLowerCase().trim()))" class="md:hidden bg-white px-4 py-3 rounded shadow border border-gray-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="text-xs text-gray-500">Nome</div>
                                        <div class="text-sm text-gray-700 font-medium">{{ $insumo->nome }}</div>
                                    </div>
                                    <div class="text-right">
                                        @php
                                            $validadeMobile = $insumo->validade ? \Carbon\Carbon::parse($insumo->validade) : null;
                                            $vencidoMobile = $validadeMobile ? $validadeMobile->isPast() : false;
                                        @endphp
                                        @if($nivel != \App\Models\User::NIVEL_PROFESSOR)
                                            <div class="text-xs text-gray-500">Validade</div>
                                            <div class="text-sm {{ $vencidoMobile ? 'text-red-600 font-semibold' : 'text-gray-700' }}">{{ $validadeMobile ? $validadeMobile->format('d/m/Y') : 'N/A' }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-2 grid grid-cols-2 gap-2 text-sm text-gray-700">
                                    <div>
                                        <div class="text-xs text-gray-500">Marca</div>
                                        <div>{{ $insumo->marca }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Modelo</div>
                                        <div>{{ $insumo->modelo }}</div>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <div class="text-xs text-gray-500">Descrição</div>
                                    <div class="text-sm text-gray-700">{{ Str::limit($insumo->descricao, 120) }}</div>
                                </div>

                                @if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_TECNICO, \App\Models\User::NIVEL_COORDENADOR_CURSO, \App\Models\User::NIVEL_COORDENADOR_LABORATORIO]))
                                    <div class="mt-3 flex items-center gap-3">
                                        <a href="{{ route('insumos.edit', $insumo->id) }}" class="text-sm text-[#0C2E5C] font-medium">Editar</a>
                                        <a href="{{ route('insumos.estoque', $insumo) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                            Gerenciar Estoque
                                        </a>
                                        <form action="{{ route('insumos.destroy', $insumo->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Deseja excluir?')" type="submit" class="cursor-pointer text-sm text-red-600">Excluir</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-4">
                                Nenhum insumo encontrado.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
