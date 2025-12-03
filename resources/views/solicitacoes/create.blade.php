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
</x-app-layout>
