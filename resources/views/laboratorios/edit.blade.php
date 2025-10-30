<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-[#0C2E5C]">
            Editar Laboratório
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
            
            <form method="POST" action="{{ route('laboratorios.update', $laboratorio->id) }}" onsubmit="return trimFormInputs(this)">
                @csrf
                @method('PUT')

                <h3 class="text-xl font-bold mb-4 text-gray-800 text-center">Editar dados do laboratório</h3>

                <div class="mb-5">
                    <x-label for="nome" value="Nome" class="block text-sm font-medium text-gray-700" />
                    <x-input id="nome" type="text" name="nome" :value="old('nome', $laboratorio->nome)" required class="w-full" />
                    <x-input-error for="nome" :messages="$errors->get('nome')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="capacidade" value="Capacidade" class="block text-sm font-medium text-gray-700" />
                    <x-input id="capacidade" type="number" name="capacidade" :value="old('capacidade', $laboratorio->capacidade)" required min="0" step="1" class="w-full" />
                    <x-input-error for="capacidade" :messages="$errors->get('capacidade')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="tipo" value="Tipo" class="block text-sm font-medium text-gray-700" />
                    <x-input id="tipo" type="text" name="tipo" :value="old('tipo', $laboratorio->tipo)" required class="w-full" />
                    <x-input-error for="tipo" :messages="$errors->get('tipo')" class="mt-2" />
                </div>
                
                <div class="mb-4">
                    <x-label for="localizacao" value="Localização" class="block text-sm font-medium text-gray-700" />
                    <x-input id="localizacao" type="text" name="localizacao" :value="old('localizacao', $laboratorio->localizacao)" required class="w-full" />
                    <x-input-error for="localizacao" :messages="$errors->get('localizacao')" class="mt-2" />
                </div>
                
                <div class="flex justify-end space-x-2 mt-6">
                    <a href="{{ route('laboratorios.index') }}" class="px-4 py-2 text-white bg-red-700 border rounded hover:bg-red-600">Cancelar</a>
                    <x-button type="submit" class="bg-[#0C2E5C] hover:bg-blue-700">
                        {{ __('Salvar') }}
                    </x-button>
                </div>
                
            </form>
        </div>
    </div>
</x-app-layout>