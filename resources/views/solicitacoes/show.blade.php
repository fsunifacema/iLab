<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">
            Detalhes da Solicitação #{{ $solicitacao->id }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensagens de Sucesso/Erro --}}
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

            {{-- Variáveis de Controle --}}
            @php
                $podeAprovarCurso = $solicitacao->status == \App\Models\Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO 
                                    && auth()->user()->nivel == \App\Models\User::NIVEL_COORDENADOR_CURSO;
                
                $podeAprovarLab = $solicitacao->status == \App\Models\Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB 
                                    && auth()->user()->nivel == \App\Models\User::NIVEL_COORDENADOR_LABORATORIO;

                $statusFinalizado = in_array($solicitacao->status, [\App\Models\Solicitacao::STATUS_APROVADA, \App\Models\Solicitacao::STATUS_RECUSADA]);
            @endphp
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- COLUNA PRINCIPAL --}}
                <div class="lg:col-span-2 space-y-6 bg-white shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-700 border-b pb-3 mb-4">
                        Detalhes da Solicitação
                    </h3>
                    
                    <div class="flex justify-between items-center bg-gray-50 p-3 rounded-md border">
                        <div class="p-1">
                            <span class="text-sm font-medium text-gray-500 block">Status Atual:</span>
                            <span class="px-2 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if ($solicitacao->status == \App\Models\Solicitacao::STATUS_APROVADA) bg-green-100 text-green-800
                                @elseif ($solicitacao->status == \App\Models\Solicitacao::STATUS_RECUSADA) bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $solicitacao->status_texto }}
                            </span>
                        </div>
                        <div class="p-1 text-right">
                            <span class="text-sm font-medium text-gray-500 block">Data de Criação:</span>
                            <span class="text-gray-800 font-semibold">{{ $solicitacao->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>

                    <h4 class="text-lg font-bold text-gray-700 pt-4 border-b pb-2">Informações do Laboratório</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between border-b pb-1">
                            <span class="font-medium text-gray-700">Laboratório:</span>
                            <span class="text-gray-900">{{ $solicitacao->laboratorio->nome ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-1">
                            <span class="font-medium text-gray-700">Localização:</span>
                            <span class="text-gray-900">{{ $solicitacao->laboratorio->localizacao ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Data Solicitada:</span>
                            <span class="text-gray-900">{{ $solicitacao->data_solicitacao ? \Carbon\Carbon::parse($solicitacao->data_solicitacao)->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Horário:</span>
                            <span class="text-gray-900">
                                {{ $solicitacao->horario_inicio ? \Carbon\Carbon::parse($solicitacao->horario_inicio)->format('H:i') : '--' }} às 
                                {{ $solicitacao->horario_fim ? \Carbon\Carbon::parse($solicitacao->horario_fim)->format('H:i') : '--' }}
                            </span>
                        </div>
                        {{-- Insumos --}}
                        <div class="flex justify-between border-b pb-2 items-start">
                            <span class="font-medium text-gray-700">Insumos Solicitados:</span>
                            <div class="flex flex-col text-right">
                                @forelse ($solicitacao->insumos as $insumo)
                                    <span class="text-gray-900 bg-gray-100 px-2 py-0.5 rounded text-start mb-1 text-xs font-semibold border border-gray-200">
                                        {{ $insumo->nome }}
                                    </span>
                                @empty
                                    <span class="text-gray-400 italic">Nenhum</span>
                                @endforelse
                            </div>
                        </div>

                        {{-- Equipamentos --}}
                        <div class="flex justify-between border-b pb-2 items-start mt-2">
                            <span class="font-medium text-gray-700">Equipamentos Solicitados:</span>
                            <div class="flex flex-col text-right">
                                @forelse ($solicitacao->equipamentos as $equipamento)
                                    <span class="text-gray-900 bg-gray-100 px-2 py-0.5 rounded mb-1 texte-start text-xs font-semibold border border-gray-200">
                                        {{ $equipamento->nome }}
                                    </span>
                                @empty
                                    <span class="text-gray-400 italic">Nenhum</span>
                                @endforelse
                            </div>
                        </div>

                    </div>
                    
                    <h4 class="text-lg font-bold text-gray-700 pt-4 border-b pb-2">Detalhes do Roteiro</h4>
                    
                    @if ($solicitacao->roteiro)
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                            <p class="font-bold text-blue-700">Roteiro Vinculado</p>
                            <a href="{{ route('roteiros.pdf', $solicitacao->roteiro->id) }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 underline font-semibold mt-1 inline-block">
                                📄 Baixar/Visualizar Roteiro em PDF
                            </a>
                        </div>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between border-b pb-1">
                                <span class="font-medium text-gray-700">Unidade Curricular:</span>
                                <span class="text-gray-900">{{ $solicitacao->roteiro->unidade_curricular ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-1">
                                <span class="font-medium text-gray-700">Curso:</span>
                                <span class="text-gray-900">{{ $solicitacao->roteiro->curso ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="pt-4">
                                <span class="font-medium text-gray-700 block mb-1">Conteúdo Detalhado:</span>
                                <div class="bg-gray-50 p-3 rounded border text-gray-900 whitespace-pre-wrap">
                                    {{ $solicitacao->roteiro->conteudo ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded">
                            <p class="text-sm">Roteiro ainda não foi criado para esta solicitação.</p>
                        </div>
                    @endif
                </div>

                {{-- COLUNA LATERAL: AÇÕES --}}
                <div class="lg:col-span-1 space-y-6">
                    
                    @if ($podeAprovarCurso || $podeAprovarLab)
                        <div class="bg-white shadow-xl sm:rounded-lg p-6 border-t-4 border-blue-500">
                            <h3 class="text-xl font-bold text-gray-700 border-b pb-3 mb-4">
                                Minhas Ações
                            </h3>
                            
                            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4">
                                <span class="font-bold block mb-1">Aguardando sua Aprovação</span>
                                <p class="text-sm">Esta solicitação exige sua ação como:
                                    <span class="font-medium">{{ $podeAprovarCurso ? 'Coordenador de Curso' : 'Coordenador de Laboratório' }}</span>
                                </p>
                            </div>
                            
                            <div class="space-y-3 pt-4">
                                {{-- COORDENADOR DE CURSO --}}
                                @if($podeAprovarCurso)
                                    <form action="{{ route('solicitacoes.aprovar.coordenador.curso', $solicitacao->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="cursor-pointer w-full bg-green-600 text-white font-bold text-sm px-4 py-3 rounded-md hover:bg-green-700 transition">
                                            APROVAR E AVANÇAR
                                        </button>
                                    </form>
                                    
                                    {{-- CORREÇÃO AQUI: Usando data-url para evitar erro no editor --}}
                                    <button type="button" 
                                            data-url="{{ route('solicitacoes.recusar.coordenador.curso', $solicitacao->id) }}"
                                            onclick="openRecusarModal(this.dataset.url)"
                                            class="w-full bg-red-600 text-white font-bold text-sm cursor-pointer px-4 py-3 rounded-md hover:bg-red-700 transition">
                                        RECUSAR SOLICITAÇÃO
                                    </button>

                                {{-- COORDENADOR DE LABORATÓRIO --}}
                                @elseif($podeAprovarLab)
                                    <form action="{{ route('solicitacoes.aprovar.coordenador.lab', $solicitacao->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="cursor-pointer w-full bg-green-600 text-white font-bold text-sm px-4 py-3 rounded-md hover:bg-green-700 transition">
                                            APROVAR E AVANÇAR
                                        </button>
                                    </form>

                                    {{-- CORREÇÃO AQUI: Usando data-url para evitar erro no editor --}}
                                    <button type="button" 
                                            data-url="{{ route('solicitacoes.recusar.coordenador.lab', $solicitacao->id) }}"
                                            onclick="openRecusarModal(this.dataset.url)"
                                            class="w-full bg-red-600 text-white font-bold text-sm cursor-pointer px-4 py-3 rounded-md hover:bg-red-700 transition">
                                        RECUSAR SOLICITAÇÃO
                                    </button>
                                @endif
                            </div>
                        </div>

                    @elseif (!$statusFinalizado)
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4">
                            <span class="font-bold">Em Análise</span>
                            <p class="text-sm">Esta solicitação está {{ $solicitacao->status_texto }}. Não exige sua ação neste momento.</p>
                        </div>
                    @endif

                    {{-- BOTÃO CRIAR ROTEIRO --}}
                    @if ($solicitacao->status === \App\Models\Solicitacao::STATUS_APROVADA 
                        && (Auth::id() === $solicitacao->professor_id || Auth::user()->nivel === \App\Models\User::NIVEL_PROFESSOR)
                        && !$solicitacao->roteiro) 
                    
                        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded shadow-lg">
                            <h4 class="font-bold text-green-900 mb-3">Próximo Passo</h4>
                            <p class="text-sm text-green-800 mb-4">A solicitação foi aprovada! Você pode agora criar o roteiro de prática.</p>
                            <a href="{{ route('roteiros.criar-solicitacao', $solicitacao->codigo) }}" class="inline-block w-full text-center bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition">
                                + Criar Roteiro
                            </a>
                        </div>
                    @endif

                    {{-- RESULTADO DA ANÁLISE --}}
                    @if ($solicitacao->autorizante || $solicitacao->parecer || $statusFinalizado)
                        <div class="bg-white shadow-xl sm:rounded-lg p-6">
                            <h4 class="font-bold text-gray-700 border-b pb-2 mb-2">Resultado da Análise</h4>
                            
                            <div class="space-y-3 pt-2">
                                <div class="flex justify-between border-b pb-1">
                                    <span class="font-medium text-gray-700">Status Final:</span>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $solicitacao->status == \App\Models\Solicitacao::STATUS_APROVADA ? 'bg-green-100 text-green-800' :
                                          ($solicitacao->status == \App\Models\Solicitacao::STATUS_RECUSADA ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $solicitacao->status_texto }}
                                    </span>
                                </div>
                                @if($solicitacao->autorizante)
                                <div class="flex justify-between border-b pb-1">
                                    <span class="font-medium text-gray-700">Último Autorizante:</span>
                                    <span class="text-gray-900">{{ $solicitacao->autorizante->name ?? 'N/A' }}</span>
                                </div>
                                @endif
                                @if($solicitacao->parecer)
                                <div>
                                    <span class="font-medium text-gray-700 block mb-2">Parecer/Motivo:</span>
                                    <div class="bg-gray-50 p-3 rounded border text-gray-900">
                                        {{ $solicitacao->parecer }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- CRIADOR --}}
                    <div class="bg-white shadow-xl sm:rounded-lg p-6">
                        <h4 class="font-bold text-gray-700 border-b pb-2 mb-2">Criador da Solicitação</h4>
                        <p class="text-sm text-gray-600">Nome: {{ $solicitacao->professor->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">Nível ID: {{ $solicitacao->professor->nivel ?? 'N/A' }}</p> 
                    </div>
                </div>
            </div>
            
            {{-- Botão Voltar/Excluir --}}
            <div class="mt-8 flex justify-end space-x-2">
                <a href="{{ route('solicitacoes.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                    Voltar para a Lista
                </a>
                
                @if(auth()->user()->nivel == \App\Models\User::NIVEL_PROFESSOR 
                    && $solicitacao->professor_id == auth()->id() 
                    && !$statusFinalizado)
                    <form action="{{ route('solicitacoes.destroy', $solicitacao->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 cursor-pointer rounded-md hover:bg-red-700 transition"
                                onclick="return confirm('Tem certeza que deseja excluir esta solicitação?')">
                            Excluir Solicitação
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>

    {{-- MODAL RECUSAR --}}
    <div id="recusarModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <form id="recusarForm" method="POST">
                @csrf
                @method('POST') 
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recusar Solicitação</h3>
                    <div class="mt-2 px-2 py-3">
                        <label class="block text-sm font-medium text-gray-700 text-left mb-1">Parecer (obrigatório)</label>
                        <textarea name="parecer" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-red-500 focus:border-red-500" rows="4" required placeholder="Informe o motivo da recusa..."></textarea>
                    </div>
                    <div class="items-center px-2 py-3 flex justify-end space-x-2">
                        <button type="button" onclick="closeRecusarModal()" class="px-4 py-2 bg-gray-200 cursor-pointer text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none transition">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-base cursor-pointer font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none transition">
                            Confirmar Recusa
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRecusarModal(urlAction) {
            const form = document.getElementById('recusarForm');
            form.action = urlAction;
            document.getElementById('recusarModal').classList.remove('hidden');
        }

        function closeRecusarModal() {
            document.getElementById('recusarModal').classList.add('hidden');
        }

        document.getElementById('recusarModal').addEventListener('click', function(e) {
            if (e.target.id === 'recusarModal') {
                closeRecusarModal();
            }
        });
    </script>
</x-app-layout>