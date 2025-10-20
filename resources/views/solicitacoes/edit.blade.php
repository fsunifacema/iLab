<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">
            {{ __('Aprovação de Solicitação') }} #{{ $solicitacao->id }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- 
                Verificação principal: O formulário de ação só é visível/submissível se:
                1. A solicitação estiver pendente.
                2. O nível do usuário logado (nivelUsuario) for igual ao nível que precisa aprovar (nivel_atual). 
            --}}
            @php
                $podeAgir = $solicitacao->status === 'pendente' && $nivelUsuario === $solicitacao->nivel_atual;
            @endphp

            <form method="POST" action="{{ route('solicitacoes.update', $solicitacao) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- COLUNA PRINCIPAL: DETALHES DO ROTEIRO (EDITÁVEIS) --}}
                    <div class="lg:col-span-2 space-y-6 bg-white shadow-xl sm:rounded-lg p-6">
                        <h3 class="text-xl font-bold text-gray-700 border-b pb-3 mb-4">
                            Detalhes do Roteiro Anexado
                        </h3>
                        
                        <!-- Status e Nível Atual (Visualização) -->
                        <div class="flex justify-between mb-4">
                            <div class="p-3 border rounded">
                                <span class="text-sm font-medium text-gray-500 block">Status Atual:</span>
                                <span class="px-2 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                    @if ($solicitacao->status === 'aprovado') bg-green-100 text-green-800
                                    @elseif ($solicitacao->status === 'rejeitado') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($solicitacao->status) }}
                                </span>
                            </div>
                            <div class="p-3 border rounded text-right">
                                <span class="text-sm font-medium text-gray-500 block">Aguardando Aprovação:</span>
                                <span class="text-lg font-bold text-[#0C2E5C]">
                                    Nível {{ $solicitacao->nivel_atual }}
                                </span>
                            </div>
                        </div>

                        {{-- Campos do Roteiro (Todos editáveis, pois a regra é permitir edição) --}}
                        <input type="hidden" name="roteiro_id" value="{{ $solicitacao->roteiro->id }}">
                        
                        <!-- Rota para visualização completa do PDF -->
                        <a href="{{ route('roteiros.pdf', $solicitacao->roteiro->id) }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 font-semibold block mb-4">
                            Ver Roteiro em PDF (Visualização Completa)
                        </a>

                        <!-- Exemplo de Campo Editável: Unidade Curricular -->
                        <div class="mb-4">
                            <label for="roteiro_unidade_curricular" class="block font-medium text-sm text-gray-700">Unidade Curricular</label>
                            <input type="text" name="roteiro[unidade_curricular]" id="roteiro_unidade_curricular" 
                                   value="{{ old('roteiro.unidade_curricular', $solicitacao->roteiro->unidade_curricular) }}"
                                   {{-- Desabilita a edição se não puder agir --}}
                                   @disabled(!$podeAgir)
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @if(!$podeAgir) bg-gray-100 @endif">
                            @error('roteiro.unidade_curricular') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                        </div>

                        <!-- Exemplo de Campo Editável: Professor -->
                        <div class="mb-4">
                            <label for="roteiro_professor" class="block font-medium text-sm text-gray-700">Professor Solicitante</label>
                            <input type="text" name="roteiro[professor]" id="roteiro_professor" 
                                   value="{{ old('roteiro.professor', $solicitacao->roteiro->professor) }}"
                                   @disabled(!$podeAgir)
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @if(!$podeAgir) bg-gray-100 @endif">
                            @error('roteiro.professor') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Campo de Conteúdo (textarea) -->
                        <div class="mb-4">
                            <label for="roteiro_conteudo" class="block font-medium text-sm text-gray-700">Conteúdo Detalhado</label>
                            <textarea name="roteiro[conteudo]" id="roteiro_conteudo" rows="5"
                                      @disabled(!$podeAgir)
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @if(!$podeAgir) bg-gray-100 @endif">{{ old('roteiro.conteudo', $solicitacao->roteiro->conteudo) }}</textarea>
                            @error('roteiro.conteudo') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                        </div>

                        {{-- NOTA: Adicionar AQUI todos os outros campos do seu Model Roteiro --}}
                        
                        <hr class="mt-8">
                        
                        <h4 class="text-lg font-medium text-gray-700 mt-6">Informações da Solicitação</h4>
                        
                        <!-- Data e Laboratório (Campos da Solicitacao) -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="data_reserva" class="block font-medium text-sm text-gray-700">Data Solicitada</label>
                                <input type="date" name="data_reserva" id="data_reserva" 
                                       value="{{ old('data_reserva', $solicitacao->data_reserva?->format('Y-m-d')) }}"
                                       @disabled(!$podeAgir)
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @if(!$podeAgir) bg-gray-100 @endif">
                                @error('data_reserva') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-4">
                                <label for="laboratorio_id" class="block font-medium text-sm text-gray-700">Laboratório</label>
                                {{-- Laboratório é apenas visual aqui, mas pode ser um campo editável se necessário --}}
                                <p class="mt-1 block w-full text-gray-800 border rounded-md shadow-sm p-2 bg-gray-50">
                                    {{ $solicitacao->laboratorio->nome ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                    </div>


                    {{-- COLUNA LATERAL: AÇÕES E HISTÓRICO --}}
                    <div class="lg:col-span-1 space-y-6">
                        @if ($podeAgir)
                            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                                <h3 class="text-xl font-bold text-gray-700 border-b pb-3 mb-4">
                                    Minhas Ações (Nível {{ $nivelUsuario }})
                                </h3>
                                
                                <!-- Campo de Comentários (Para Aprovação ou Rejeição) -->
                                <div class="mb-4">
                                    <label for="comentarios" class="block font-medium text-sm text-gray-700">Comentários (Opcional)</label>
                                    <textarea name="comentarios" id="comentarios" rows="3"
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('comentarios') }}</textarea>
                                    @error('comentarios') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>

                                <!-- Botões de Ação -->
                                <div class="space-y-3 pt-4">
                                    
                                    {{-- APROVAR: Ação principal que move o fluxo --}}
                                    <button type="submit" name="action" value="aprovar"
                                            class="w-full bg-green-600 text-white font-bold text-sm px-4 py-3 rounded-md hover:bg-green-700 transition">
                                        APROVAR E AVANÇAR
                                    </button>
                                    
                                    {{-- REJEITAR: Finaliza o fluxo --}}
                                    <button type="submit" name="action" value="rejeitar"
                                            class="w-full bg-red-600 text-white font-bold text-sm px-4 py-3 rounded-md hover:bg-red-700 transition"
                                            onclick="return confirm('ATENÇÃO: Deseja realmente REJEITAR esta solicitação? Esta ação é final.')">
                                        REJEITAR SOLICITAÇÃO
                                    </button>

                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4">
                                <span class="font-bold">Aguardando Nível {{ $solicitacao->nivel_atual }}</span>
                                <p class="text-sm">Esta solicitação não exige sua ação neste momento.</p>
                            </div>
                        @endif

                        {{-- VISUALIZAÇÃO DO CRIADOR --}}
                        <div class="bg-white shadow-xl sm:rounded-lg p-6">
                            <h4 class="font-bold text-gray-700 border-b pb-2 mb-2">Criador da Solicitação</h4>
                            <p class="text-sm text-gray-600">Nome: {{ $solicitacao->criador->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">Nível: {{ $solicitacao->criador->getTypeAttribute() ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </form>
            
            {{-- Se já foi finalizada, mostra apenas o histórico (visualização) --}}
            @if ($solicitacao->status !== 'pendente')
                <div class="mt-6 p-6 bg-gray-100 border border-gray-300 rounded-lg">
                    <h4 class="text-lg font-bold text-gray-700">Status: {{ ucfirst($solicitacao->status) }}</h4>
                    @if ($solicitacao->autorizante)
                        <p class="text-sm text-gray-600">Finalizada por: {{ $solicitacao->autorizante->name }}</p>
                    @endif
                    <p class="text-sm text-gray-600">Motivo: {{ $solicitacao->motivo_rejeicao ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">Observações: {{ $solicitacao->comentarios ?? 'N/A' }}</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
