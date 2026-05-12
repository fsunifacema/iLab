<!-- resources/views/solicitacoes/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nova Solicitação
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">

                    <x-validation-errors class="mb-4" />

                    <form action="{{ route('solicitacoes.store') }}" method="POST">
                        @csrf

                        {{-- Seleção de Coordenadores --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-blue-800 mb-4">Selecionar Coordenadores para Aprovação</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Coordenador de Curso</label>
                                    <select name="coordenador_curso_id" required class="block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Selecione o coordenador de curso</option>
                                        @foreach($coordenadoresCurso as $coord)
                                            <option value="{{ $coord->id }}" {{ old('coordenador_curso_id') == $coord->id ? 'selected' : '' }}>{{ $coord->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Coordenador de Laboratório</label>
                                    <select name="coordenador_lab_id" required class="block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Selecione o coordenador de laboratório</option>
                                        @foreach($coordenadoresLab as $coord)
                                            <option value="{{ $coord->id }}" {{ old('coordenador_lab_id') == $coord->id ? 'selected' : '' }}>{{ $coord->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                            <div class="md:col-span-2 space-y-4">
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-bold text-gray-800">Datas, Horários e Laboratórios (Agendamentos)</label>
                                    <button type="button" onclick="addAgendamento()" class="text-blue-600 hover:text-blue-800 text-sm font-bold">+ Adicionar outra Data/Horário</button>
                                </div>
                                
                                <div id="agendamentos-container" class="space-y-6">
@php
    $agendamentosOld = old('agendamentos', [0 => []]);
@endphp
@foreach($agendamentosOld as $index => $agendamentoOld)
                                    <div class="agendamento-item border p-4 rounded bg-gray-50 flex flex-col gap-6 relative">
                                        
                                        <!-- Data, Horários e Laboratório -->
                                        <div class="flex flex-col md:flex-row gap-4">
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-gray-700">Laboratório</label>
                                                <select name="agendamentos[{{ $index }}][laboratorio_id]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                                    <option value="">Selecione o laboratório</option>
                                                    @foreach($laboratorios as $lab)
                                                        <option value="{{ $lab->id }}" {{ (isset($agendamentoOld['laboratorio_id']) && $agendamentoOld['laboratorio_id'] == $lab->id) ? 'selected' : '' }}>{{ $lab->nome }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-gray-700">Unidade Curricular</label>
                                                <input type="text" name="agendamentos[{{ $index }}][unidade_curricular]" id="unidade_curricular_{{ $index }}" required maxlength="255" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 unidade-curricular-input" placeholder="Informe a unidade curricular" value="{{ $agendamentoOld['unidade_curricular'] ?? '' }}" />
                                            </div>
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-gray-700">Data da Prática</label>
                                                <input type="date" name="agendamentos[{{ $index }}][data_solicitacao]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" value="{{ $agendamentoOld['data_solicitacao'] ?? '' }}">
                                            </div>
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-gray-700">Horário de Início</label>
                                                <input type="time" name="agendamentos[{{ $index }}][horario_inicio]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" value="{{ $agendamentoOld['horario_inicio'] ?? '' }}">
                                            </div>
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-gray-700">Horário de Fim</label>
                                                <input type="time" name="agendamentos[{{ $index }}][horario_fim]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" value="{{ $agendamentoOld['horario_fim'] ?? '' }}">
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" onclick="removeAgendamento(this)" class="text-red-500 font-bold px-2 py-2 mb-1" style="{{ count($agendamentosOld) > 1 ? 'display: block;' : 'display: none;' }}" title="Remover este item">✕</button>
                                            </div>
                                        </div>

                                        <!-- Insumos e Equipamentos (Específicos deste agendamento) -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-4 border rounded shadow-inner">
                                            <!-- Insumos -->
                                            <div class="space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <label class="block text-sm font-medium text-gray-700">Insumos (Opcional)</label>
                                                    <button type="button" onclick="addInput(this)" class="text-blue-600 hover:text-blue-800 text-xs font-bold">+ Adicionar Insumo</button>
                                                </div>
                                                <div class="items-container space-y-2">
@php
    $insumosOld = isset($agendamentoOld['insumos_id']) ? $agendamentoOld['insumos_id'] : [0 => ''];
@endphp
@foreach($insumosOld as $insumoIndex => $insumoValue)
                                                    <div class="flex gap-2">
                                                        <select name="agendamentos[{{ $index }}][insumos_id][]" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm">
                                                            <option value="">Selecione o insumo...</option>
                                                            @foreach($insumos as $insumo)
                                                                <option value="{{ $insumo->id }}" {{ $insumoValue == $insumo->id ? 'selected' : '' }}>{{ $insumo->nome }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="button" onclick="removeInput(this)" class="text-red-500 px-2 font-bold" title="Remover">✕</button>
                                                    </div>
@endforeach
                                                </div>
                                            </div>

                                            <!-- Equipamentos -->
                                            <div class="space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <label class="block text-sm font-medium text-gray-700">Equipamentos (Opcional)</label>
                                                    <button type="button" onclick="addInput(this)" class="text-blue-600 hover:text-blue-800 text-xs font-bold">+ Adicionar Equipamento</button>
                                                </div>
                                                <div class="items-container space-y-2">
@php
    $equipamentosOld = isset($agendamentoOld['equipamentos_id']) ? $agendamentoOld['equipamentos_id'] : [0 => ''];
@endphp
@foreach($equipamentosOld as $equipIndex => $equipValue)
                                                    <div class="flex gap-2">
                                                        <select name="agendamentos[{{ $index }}][equipamentos_id][]" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm">
                                                            <option value="">Selecione o equipamento...</option>
                                                            @foreach($equipamentos as $equipamento)
                                                                <option value="{{ $equipamento->id }}" {{ $equipValue == $equipamento->id ? 'selected' : '' }}>{{ $equipamento->nome }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="button" onclick="removeInput(this)" class="text-red-500 px-2 font-bold" title="Remover">✕</button>
                                                    </div>
@endforeach
                                                </div>
                                            </div>
                                        </div>

                                    </div>
@endforeach
                                </div>
                            </div>

                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="cursor-pointer bg-[#0C2E5C] mb-3 mr-4 text-white px-6 py-3 rounded-md hover:bg-blue-800 font-bold transition">
                                Enviar Solicitação(ões)
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        let agendamentoIndex = {{ max(array_keys(old('agendamentos', [0 => []]))) }};

        function addAgendamento() {
            agendamentoIndex++;
            const container = document.getElementById('agendamentos-container');
            const template = container.querySelector('.agendamento-item');
            const item = template.cloneNode(true);
            
            // Limpa os valores copiados (datas/horas) e atualiza os 'name' attr
            item.querySelectorAll('input').forEach(input => {
                input.value = "";
                input.name = input.name.replace(/\[\d+\]/, `[${agendamentoIndex}]`);
                // Atualiza o ID do campo unidade_curricular
                if (input.classList.contains('unidade-curricular-input')) {
                    input.id = `unidade_curricular_${agendamentoIndex}`;
                }
            });

            // Atualiza os selects (laboratorio, roteiro, insumos, equipamentos)
            item.querySelectorAll('select').forEach(select => {
                select.value = "";
                select.name = select.name.replace(/\[\d+\]/, `[${agendamentoIndex}]`);
            });
            
            // Múltiplos selects (insumos e equipamentos) devem voltar a ter apenas 1 por padrão
            const itemsContainers = item.querySelectorAll('.items-container');
            itemsContainers.forEach(itemsContainer => {
                const firstRow = itemsContainer.querySelector('.flex');
                if (firstRow) {
                    const clonedRow = firstRow.cloneNode(true);
                    const select = clonedRow.querySelector('select');
                    select.value = "";
                    select.name = select.name.replace(/\[\d+\]/, `[${agendamentoIndex}]`);
                    itemsContainer.innerHTML = '';
                    itemsContainer.appendChild(clonedRow);
                }
            });
            
            // Exibe o botão de remover do novo agendamento
            const removeBtn = item.querySelector('button[onclick="removeAgendamento(this)"]');
            if(removeBtn) removeBtn.style.display = 'block';

            container.appendChild(item);
        }

        function removeAgendamento(button) {
            const container = document.getElementById('agendamentos-container');
            if (container.querySelectorAll('.agendamento-item').length > 1) {
                button.closest('.agendamento-item').remove();
            } else {
                alert("Pelo menos uma data e horário devem ser informados.");
            }
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
</x-app-layout>
