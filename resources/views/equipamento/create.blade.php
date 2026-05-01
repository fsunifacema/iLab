<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Adicionar Novo Equipamento
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

            <form method="POST" action="{{ route('equipamentos.store') }}" onsubmit="return trimFormInputs(this)">
                @csrf

                <h3 class="text-xl font-bold mb-6 text-gray-800 text-center">Dados do Equipamento</h3>

                <div class="mb-4">
                    <x-label for="nome" value="Nome" class="block text-sm font-medium" />
                    <x-input id="nome" type="text" name="nome" :value="old('nome')" required autofocus class="w-full" />
                    <x-input-error for="nome" :messages="$errors->get('nome')" class="mt-2" />
                </div>
                <div class="mb-4">
                    <x-label for="marca" value="Marca" class="block text-sm font-medium" />
                    <x-input id="marca" type="text" name="marca" :value="old('marca')" required class="w-full" />
                    <x-input-error for="marca" :messages="$errors->get('marca')" class="mt-2" />
                </div>
                <div class="mb-4">
                    <x-label for="modelo" value="Modelo" class="block text-sm font-medium" />
                    <x-input id="modelo" type="text" name="modelo" :value="old('modelo')" required class="w-full" />
                    <x-input-error for="modelo" :messages="$errors->get('modelo')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="manutencao" name="manutencao" value="1" class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" />
                        <span class="ml-2 text-sm text-gray-700">{{ __('Está em Manutenção?') }}</span>
                    </label>
                </div>
                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="calibracao" name="calibracao" value="1" class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" />
                        <span class="ml-2 text-sm text-gray-700">{{ __('Necessita Calibração?') }}</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-2 mt-4">
                    <a href="{{ route('equipamentos.index') }}" class="inline-flex items-center px-4 py-2 text-white bg-gray-800 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-red-600 focus:bg:red-700 active bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">Cancelar</a>
                    <x-button type="submit" class="bg-[#0C2E5C] hover:bg-blue-700">
                        {{ __('Salvar') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
