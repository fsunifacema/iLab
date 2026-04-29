<!-- resources/views/solicitacoes/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar solicitação
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">

                    @php
                        // Usa a mesma lógica de permissão calculada no controlador.
                        $podeEditarTudo = $podeEditarTudo ?? false;
                        $podeEditarLaboratorio = $podeEditarLaboratorio ?? false;
                        $podeEditar = $podeEditar ?? false;
                    @endphp

                    @if(!$podeEditar)
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">Você não tem permissão para editar esta solicitação.</span>
                        </div>
                    @else
                        {{-- Exibir erros de validação --}}
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <strong>Erros de validação:</strong>
                                <ul class="mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('solicitacoes.update', $solicitacao->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Seleção de Coordenadores --}}
                            @if($podeEditarTudo)
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                                    <h3 class="text-lg font-semibold text-blue-800 mb-4">Selecionar coordenadores para aprovação</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Coordenador de Curso</label>
                                            <select name="coordenador_curso_id" required {{ $solicitacao->coordenador_curso_id ? 'disabled' : '' }} class="block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Selecione o coordenador de curso</option>
                                                @foreach($coordenadoresCurso as $coord)
                                                    <option value="{{ $coord->id }}" {{ $coord->id == $solicitacao->coordenador_curso_id ? 'selected' : '' }}>{{ $coord->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Coordenador de laboratório</label>
                                            <select name="coordenador_lab_id" required {{ $solicitacao->coordenador_lab_id ? 'disabled' : '' }} class="block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Selecione o coordenador de laboratório</option>
                                                @foreach($coordenadoresLab as $coord)
                                                    <option value="{{ $coord->id }}" {{ $coord->id == $solicitacao->coordenador_lab_id ? 'selected' : '' }}>{{ $coord->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="md:col-span-2 space-y-4">
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-bold text-gray-800">Datas, horários e laboratórios (agendamentos)</label>
                                    @if($podeEditarTudo)
                                        <button type="button" onclick="addAgendamento()" class="text-blue-600 hover:text-blue-800 text-sm font-bold">+ Adicionar outra data/horário</button>
                                    @endif
                                </div>

                                <div id="agendamentos-container" class="space-y-6">
                                    {{-- Para edição, assumindo que há apenas um agendamento por solicitação --}}
                                    <div class="agendamento-item border p-4 rounded bg-gray-50 flex flex-col gap-6 relative">

                                        <!-- Data, Horários e Laboratório -->
                                        <div class="flex flex-col md:flex-row gap-4">
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-gray-700">Laboratório</label>
                                                <select name="laboratorio_id" {{ $podeEditarTudo || $podeEditarLaboratorio ? '' : 'disabled' }} required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                                    <option value="">Selecione o laboratório</option>
                                                    @foreach($laboratorios as $lab)
                                                        <option value="{{ $lab->id }}" {{ $lab->id == $solicitacao->laboratorio_id ? 'selected' : '' }}>{{ $lab->nome }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if($podeEditarTudo)
                                                <div class="flex-1">
                                                    <label class="block text-sm font-medium text-gray-700">Unidade curricular</label>
                                                    <input type="text" name="unidade_curricular" value="{{ $solicitacao->unidade_curricular }}" required maxlength="255" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" placeholder="Informe a unidade curricular" />
                                                </div>
                                                <div class="flex-1">
                                                    <label class="block text-sm font-medium text-gray-700">Data da prática</label>
                                                    <input type="date" name="data_solicitacao" value="{{ $solicitacao->data_solicitacao }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                                </div>
                                                <div class="flex-1">
                                                    <label class="block text-sm font-medium text-gray-700">Horário de início</label>
                                                    <input type="time" name="horario_inicio" value="{{ $solicitacao->horario_inicio }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                                </div>
                                                <div class="flex-1">
                                                    <label class="block text-sm font-medium text-gray-700">Horário de fim</label>
                                                    <input type="time" name="horario_fim" value="{{ $solicitacao->horario_fim }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                                </div>
                                            @else
                                                <div class="flex-1">
                                                    <label class="block text-sm font-medium text-gray-700">Unidade curricular</label>
                                                    <input type="text" value="{{ $solicitacao->unidade_curricular }}" disabled class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-gray-100" />
                                                </div>
                                                <div class="flex-1">
                                                    <label class="block text-sm font-medium text-gray-700">Data da prática</label>
                                                    <input type="date" value="{{ $solicitacao->data_solicitacao }}" disabled class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-gray-100">
                                                </div>
                                                <div class="flex-1">
                                                    <label class="block text-sm font-medium text-gray-700">Horário de início</label>
                                                    <input type="time" value="{{ $solicitacao->horario_inicio }}" disabled class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-gray-100">
                                                </div>
                                                <div class="flex-1">
                                                    <label class="block text-sm font-medium text-gray-700">Horário de fim</label>
                                                    <input type="time" value="{{ $solicitacao->horario_fim }}" disabled class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-gray-100">
                                                </div>
                                            @endif
                                        </div>

                                        @if($podeEditarTudo)
                                            <!-- Insumos e Equipamentos -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-4 border rounded shadow-inner">
                                                <!-- Insumos -->
                                                <div class="space-y-2">
                                                    <div class="flex items-center justify-between">
                                                        <label class="block text-sm font-medium text-gray-700">Insumos (opcional)</label>
                                                        <button type="button" onclick="addInput(this)" class="text-blue-600 hover:text-blue-800 text-xs font-bold">+ Adicionar Insumo</button>
                                                    </div>
                                                    <div class="items-container space-y-2">
                                                        @php
                                                            $insumosSelecionados = $solicitacao->insumos->pluck('id')->toArray();
                                                        @endphp
                                                        @if(empty($insumosSelecionados))
                                                            <div class="flex gap-2">
                                                                <select name="insumos_id[]" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm">
                                                                    <option value="">Selecione o insumo...</option>
                                                                    @foreach($insumos as $insumo)
                                                                        <option value="{{ $insumo->id }}">{{ $insumo->nome }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <button type="button" onclick="removeInput(this)" class="text-red-500 px-2 font-bold" title="Remover">✕</button>
                                                            </div>
                                                        @else
                                                            @foreach($insumosSelecionados as $insumoId)
                                                                <div class="flex gap-2">
                                                                    <select name="insumos_id[]" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm">
                                                                        <option value="">Selecione o insumo...</option>
                                                                        @foreach($insumos as $insumo)
                                                                            <option value="{{ $insumo->id }}" {{ $insumo->id == $insumoId ? 'selected' : '' }}>{{ $insumo->nome }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <button type="button" onclick="removeInput(this)" class="text-red-500 px-2 font-bold" title="Remover">✕</button>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Equipamentos -->
                                                <div class="space-y-2">
                                                    <div class="flex items-center justify-between">
                                                        <label class="block text-sm font-medium text-gray-700">Equipamentos (opcional)</label>
                                                        <button type="button" onclick="addInput(this)" class="text-blue-600 hover:text-blue-800 text-xs font-bold">+ Adicionar equipamento</button>
                                                    </div>
                                                    <div class="items-container space-y-2">
                                                        @php
                                                            $equipamentosSelecionados = $solicitacao->equipamentos->pluck('id')->toArray();
                                                        @endphp
                                                        @if(empty($equipamentosSelecionados))
                                                            <div class="flex gap-2">
                                                                <select name="equipamentos_id[]" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm">
                                                                    <option value="">Selecione o equipamento...</option>
                                                                    @foreach($equipamentos as $equipamento)
                                                                        <option value="{{ $equipamento->id }}">{{ $equipamento->nome }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <button type="button" onclick="removeInput(this)" class="text-red-500 px-2 font-bold" title="Remover">✕</button>
                                                            </div>
                                                        @else
                                                            @foreach($equipamentosSelecionados as $equipamentoId)
                                                                <div class="flex gap-2">
                                                                    <select name="equipamentos_id[]" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm">
                                                                        <option value="">Selecione o equipamento...</option>
                                                                        @foreach($equipamentos as $equipamento)
                                                                            <option value="{{ $equipamento->id }}" {{ $equipamento->id == $equipamentoId ? 'selected' : '' }}>{{ $equipamento->nome }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <button type="button" onclick="removeInput(this)" class="text-red-500 px-2 font-bold" title="Remover">✕</button>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="cursor-pointer bg-[#0C2E5C] text-white px-6 py-3 rounded-md hover:bg-blue-800 font-bold transition">
                                    Salvar alterações
                                </button>
                            </div>

                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @if($podeEditarTudo)
        <script>
            function addAgendamento() {
                // Similar ao create, mas para edição, talvez não necessário se apenas um
                alert("Funcionalidade para múltiplos agendamentos não implementada na edição.");
            }

            function removeAgendamento(button) {
                // Similar
            }

            function addInput(button) {
                const container = button.closest('.space-y-2').querySelector('.items-container');
                const div = container.querySelector('div.flex').cloneNode(true);

                // Limpa o valor selecionado no novo campo
                div.querySelector('select').value = "";

                // Adiciona o novo campo ao final da lista de itens
                container.appendChild(div);
            }

            function removeInput(button) {
                const container = button.closest('.items-container');
                // Evita remover se for o único campo
                if (container.querySelectorAll('.flex').length > 1) {
                    button.closest('.flex').remove();
                } else {
                    // Se for o único, apenas reseta o valor para que não mande nada se não quiser
                    button.closest('.flex').querySelector('select').value = "";
                }
            }
        </script>
    @endif
</x-app-layout>