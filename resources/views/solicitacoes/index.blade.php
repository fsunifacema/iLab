<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">
            Gerenciamento de Quadro de Praticas
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

            <div class="p-3">
                {{-- Controles: busca com Alpine.js --}}
                <div class="mb-4" x-data="{ 
                    query: '',
                    matches(professorName, disciplinaName, solicitacaoJson) {
                        const q = this.query.toLowerCase().trim();
                        if(q === '') return true;
                        
                        const prof = professorName.toLowerCase();
                        const disc = disciplinaName.toLowerCase();
                        
                        // Busca por professor ou disciplina
                        if (prof.includes(q) || disc.includes(q)) {
                            return true;
                        }
                        
                        // Se tem solicitação, busca também por laboratório e status
                        if (solicitacaoJson) {
                            try {
                                const sol = JSON.parse(solicitacaoJson);
                                const laboratorio = (sol.laboratorio?.nome || '').toLowerCase();
                                const status = (sol.status_texto || '').toLowerCase();
                                return laboratorio.includes(q) || status.includes(q);
                            } catch (e) {
                                return false;
                            }
                        }
                        
                        return false;
                    }
                }">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
                        <div class="flex items-center gap-2 w-full md:w-auto">
                            <input x-model="query" type="text" placeholder="Buscar por professor..." class="border rounded px-3 py-2 text-sm flex-1 md:flex-none md:w-80" />
                            <button type="button" @click="query=''" class="px-3 py-2 bg-gray-100 rounded text-sm whitespace-nowrap">Limpar</button>
                        </div>
                    </div>

                    {{-- QUANDO NÃO É PROFESSOR: Mostrar Vista Agrupada --}}
                    @if(Auth::user()->nivel != \App\Models\User::NIVEL_PROFESSOR)

                        {{-- Lista de Professores --}}
                        @forelse($solicitacoesPorProfessor as $professorId => $professorData)
                            @php
                                $professor = $professorData['professor'];
                                $disciplinas = $professorData['disciplinas'];
                                $totalSolicitacoes = $disciplinas->reduce(function($carry, $item) { 
                                    return $carry + $item['solicitacoes']->count(); 
                                }, 0);
                                $professorKey = 'prof_' . $professorId;
                            @endphp

                            {{-- Grupo Professor --}}
                            <div class="mb-6 bg-white rounded-lg shadow border border-gray-200 overflow-hidden"
                                x-show="matches('{{ $professor->name }}', '', null)">
                                <div class="bg-[#0C2E5C] text-white p-4 cursor-pointer hover:bg-blue-900 transition" 
                                    @click="$el.nextElementSibling.classList.toggle('hidden')">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" :class="{'rotate-90': !$el.nextElementSibling.classList.contains('hidden')}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                            <div>
                                                <h3 class="font-bold text-lg">{{ $professor->name }}</h3>
                                                <p class="text-xs text-gray-200">{{ $totalSolicitacoes }} solicitação(ões)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Conteúdo Expandível do Professor --}}
                                <div class="hidden">
                                    {{-- Disciplinas dentro do Professor --}}
                                    @foreach($disciplinas as $disciplinaKey => $disciplinaData)
                                        @php
                                            $solicitacoes = $disciplinaData['solicitacoes'];
                                            $nomeDisciplina = $disciplinaData['nome_disciplina'];
                                            $disciplinaKey = 'disc_' . $professorId . '_' . md5($disciplinaKey);
                                        @endphp

                                        {{-- Subarquivo Disciplina --}}
                                        <div class="border-t"
                                            x-show="matches('', '{{ $nomeDisciplina }}', null)">
                                            <div class="bg-gray-50 p-4 cursor-pointer hover:bg-gray-100 transition border-l-4 border-blue-400" 
                                                @click="$el.nextElementSibling.classList.toggle('hidden')">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition-transform text-gray-700" :class="{'rotate-90': !$el.nextElementSibling.classList.contains('hidden')}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                        <span class="font-semibold text-gray-800">{{ $nomeDisciplina }}</span>
                                                        <span class="text-xs bg-gray-300 text-gray-700 px-2 py-1 rounded">{{ $solicitacoes->count() }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Solicitações dentro da Disciplina --}}
                                            <div class="hidden">
                                                <div class="p-4 space-y-3">
                                                    @foreach($solicitacoes as $solicitacao)
                                                        @php
                                                            $requerAcao = (
                                                                (auth()->user()->nivel == \App\Models\User::NIVEL_COORDENADOR_CURSO && $solicitacao->status == \App\Models\Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO) ||
                                                                (auth()->user()->nivel == \App\Models\User::NIVEL_COORDENADOR_LABORATORIO && $solicitacao->status == \App\Models\Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB)
                                                            );
                                                            $dataDisplay = $solicitacao->created_at ? $solicitacao->created_at->format('d/m/Y') : 'N/A';
                                                        @endphp

                                                        <div class="bg-white border rounded-lg p-3 {{ $requerAcao ? 'border-l-4 border-yellow-500' : 'border-gray-200' }} hover:shadow-md transition"
                                                            x-show="matches('', '', {{ $solicitacao->toJson() }})">
                                                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                                                <div class="flex-1">
                                                                    <div class="flex items-center justify-start mb-1">
                                                                        <div class="text-sm mr-4 font-semibold text-gray-800">
                                                                            {{ \Carbon\Carbon::parse($solicitacao->horario_inicio)->format('H:i') }} - 
                                                                            {{ \Carbon\Carbon::parse($solicitacao->horario_fim)->format('H:i') }}
                                                                        </div>
                                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                                            @if ($solicitacao->status === \App\Models\Solicitacao::STATUS_APROVADA) bg-green-100 text-green-800 
                                                                            @elseif ($solicitacao->status === \App\Models\Solicitacao::STATUS_RECUSADA) bg-red-100 text-red-800
                                                                            @else bg-yellow-100 text-yellow-800 @endif">
                                                                            {{ $solicitacao->status_texto }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="text-sm text-gray-600">Lab: {{ $solicitacao->laboratorio->nome ?? 'N/A' }}</div>
                                                                    <div class="text-sm text-gray-600">Data Solicitada: {{ $solicitacao->data_solicitacao ? \Carbon\Carbon::parse($solicitacao->data_solicitacao)->format('d/m/Y') : 'N/A' }}</div>
                                                                </div>
                                                                <div class="flex gap-2 items-center">
                                                                    <a href="{{ route('solicitacoes.show', $solicitacao->id) }}" class="text-[#0C2E5C] hover:text-blue-800 font-semibold text-sm">
                                                                        Ver Detalhes
                                                                    </a>

                                                                    {{-- Botões de Aprovação Rápida --}}
                                                                    @if($requerAcao)
                                                                        @if(auth()->user()->nivel == \App\Models\User::NIVEL_COORDENADOR_CURSO)
                                                                            <form action="{{ route('solicitacoes.aprovar.coordenador.curso', $solicitacao->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Aprovar e encaminhar para o coordenador do laboratório?');">
                                                                                @csrf
                                                                                @method('POST')
                                                                                <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition">
                                                                                    Aprovar
                                                                                </button>
                                                                            </form>
                                                                        @elseif(auth()->user()->nivel == \App\Models\User::NIVEL_COORDENADOR_LABORATORIO)
                                                                            <form action="{{ route('solicitacoes.aprovar.coordenador.lab', $solicitacao->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Aprovar definitivamente esta solicitação?');">
                                                                                @csrf
                                                                                @method('POST')
                                                                                <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition">
                                                                                    Aprovar
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                    @endif

                                                                    @if(Auth::user()->nivel == \App\Models\User::NIVEL_PROFESSOR && $solicitacao->professor_id == Auth::id() && $solicitacao->status != \App\Models\Solicitacao::STATUS_APROVADA && $solicitacao->status != \App\Models\Solicitacao::STATUS_RECUSADA)
                                                                        <form action="{{ route('solicitacoes.destroy', $solicitacao->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Deseja realmente excluir esta solicitação?');">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" title="Excluir Solicitação" class="text-red-600 hover:text-red-800 font-semibold text-sm">
                                                                                Excluir
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        @empty
                            <div class="text-center text-gray-500 py-12 bg-white rounded-lg shadow-md">
                                <p class="text-gray-500 text-lg">Nenhuma solicitação encontrada para revisar.</p>
                            </div>
                        @endforelse

                    @else
                        {{-- PARA PROFESSOR: --}}
                        <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-[#0C2E5C] text-white">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Horário</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Laboratório</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Unidade Curricular</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Data Solicitada</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($todasSolicitacoes as $solicitacao)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ \Carbon\Carbon::parse($solicitacao->horario_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($solicitacao->horario_fim)->format('H:i') }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ $solicitacao->laboratorio->nome ?? 'N/A' }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ $solicitacao->unidade_curricular ?? $solicitacao->roteiro?->unidade_curricular ?? 'Sem unidade curricular' }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ $solicitacao->data_solicitacao ? \Carbon\Carbon::parse($solicitacao->data_solicitacao)->format('d/m/Y') : 'N/A' }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold 
                                                    @if ($solicitacao->status === \App\Models\Solicitacao::STATUS_APROVADA) bg-green-100 text-green-800 
                                                    @elseif ($solicitacao->status === \App\Models\Solicitacao::STATUS_RECUSADA) bg-red-100 text-red-800 
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ $solicitacao->status_texto }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                                <div class="flex flex-col gap-2">
                                                    <a href="{{ route('solicitacoes.show', $solicitacao->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                                        Ver Detalhes
                                                    </a>
                                                    @if($solicitacao->status != \App\Models\Solicitacao::STATUS_APROVADA && $solicitacao->status != \App\Models\Solicitacao::STATUS_RECUSADA)
                                                        <form action="{{ route('solicitacoes.destroy', $solicitacao->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Deseja realmente excluir esta solicitação?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm">Excluir</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                                Nenhuma solicitação encontrada.
                                                <div class="mt-2">
                                                    <a href="{{ route('solicitacoes.create') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                                        Faça sua primeira solicitação
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>