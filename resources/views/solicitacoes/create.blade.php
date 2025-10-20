<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">
            Nova Solicitação de Reserva
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('solicitacoes.store') }}">
                    @csrf
                    
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Informações da Reserva</h3>

                    <!-- Campo 1: Roteiro Base -->
                    <div class="mb-4">
                        <label for="roteiro_id" class="block font-medium text-sm text-gray-700">Roteiro a ser utilizado</label>
                        <select name="roteiro_id" id="roteiro_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecione um Roteiro...</option>
                            @foreach ($roteiros as $roteiro)
                                <option value="{{ $roteiro->id }}" {{ old('roteiro_id') == $roteiro->id ? 'selected' : '' }}>
                                    {{ $roteiro->unidade_curricular }} (Prof: {{ $roteiro->professor }})
                                </option>
                            @endforeach
                        </select>
                        @error('roteiro_id')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo 2: Laboratório Solicitado -->
                    <div class="mb-4">
                        <label for="laboratorio_id" class="block font-medium text-sm text-gray-700">Laboratório Desejado</label>
                        <select name="laboratorio_id" id="laboratorio_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecione um Laboratório...</option>
                            @foreach ($laboratorios as $laboratorio)
                                <option value="{{ $laboratorio->id }}" {{ old('laboratorio_id') == $laboratorio->id ? 'selected' : '' }}>
                                    {{ $laboratorio->nome }} (Capacidade: {{ $laboratorio->capacidade }})
                                </option>
                            @endforeach
                        </select>
                        @error('laboratorio_id')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo 3: Data da Reserva -->
                    <div class="mb-6">
                        <label for="data_reserva" class="block font-medium text-sm text-gray-700">Data da Aula/Reserva</label>
                        <input type="date" name="data_reserva" id="data_reserva" value="{{ old('data_reserva') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('data_reserva')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botão de Envio -->
                    <div class="flex justify-end">
                        <button type="submit" class="bg-[#0C2E5C] text-white text-sm px-6 py-2 rounded-md shadow-md hover:bg-blue-800 transition duration-200">
                            Enviar para Aprovação (Técnico)
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
