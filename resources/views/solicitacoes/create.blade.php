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

                    <form action="{{ route('solicitacoes.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Selecionar laboratório --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Laboratório
                                </label>
                                <select name="laboratorio_id" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                    <option value="">Selecione o laboratório</option>
                                    @foreach($laboratorios as $lab)
                                        <option value="{{ $lab->id }}">{{ $lab->nome }}</option>
                                    @endforeach
                                </select>
                                @error('laboratorio_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Data da solicitação --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Data da Solicitação
                                </label>
                                <input type="date" name="data_solicitacao" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                @error('data_solicitacao')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Horário de início --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Horário de Início
                                </label>
                                <input type="time" name="horario_inicio" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                @error('horario_inicio')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Horário de fim --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Horário de Fim
                                </label>
                                <input type="time" name="horario_fim" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                @error('horario_fim')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-4 mb-6">
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-medium text-gray-700">Insumos</label>
                                    <button type="button" onclick="addInput('insumos-container')" class="text-blue-600 hover:text-blue-800 text-sm font-bold">+ Adicionar Insumo</button>
                                </div>
                                
                                <div id="insumos-container" class="space-y-2">
                                    <div class="flex gap-2">
                                        <select name="insumos_id[]" required class="block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                            <option value="">Selecione o insumo</option>
                                            @foreach($insumos as $insumo)
                                                <option value="{{ $insumo->id }}">{{ $insumo->nome }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" onclick="removeInput(this)" class="text-red-500 px-2">✕</button>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-medium text-gray-700">Equipamentos</label>
                                    <button type="button" onclick="addInput('equipamentos-container')" class="text-blue-600 hover:text-blue-800 text-sm font-bold">+ Adicionar Equipamento</button>
                                </div>

                                <div id="equipamentos-container" class="space-y-2">
                                    <div class="flex gap-2">
                                        <select name="equipamentos_id[]" required class="block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                            <option value="">Selecione o equipamento</option>
                                            @foreach($equipamentos as $equipamento)
                                                <option value="{{ $equipamento->id }}">{{ $equipamento->nome }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" onclick="removeInput(this)" class="text-red-500 px-2">✕</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                Enviar Solicitação
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        function addInput(containerId) {
            const container = document.getElementById(containerId);
            const div = container.querySelector('div').cloneNode(true);
            
            // Limpa o valor selecionado no novo campo
            div.querySelector('select').value = "";
            
            // Adiciona o novo campo ao container
            container.appendChild(div);
        }

        function removeInput(button) {
            const container = button.parentNode.parentNode;
            // Evita remover se for o único campo
            if (container.querySelectorAll('.flex').length > 1) {
                button.parentNode.remove();
            } else {
                alert("Pelo menos um item deve ser selecionado.");
            }
        }
    </script>
</x-app-layout>
