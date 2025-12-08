<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Editar Insumo
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

            <form method="POST" action="{{ route('insumos.update', $insumo) }}" onsubmit="return trimFormInputs(this)">
                @csrf
                @method('PUT')

                <h3 class="text-xl font-bold mb-6 text-gray-800 text-center">Editar Insumo</h3>

                <div class="mb-4">
                    <x-label for="nome" value="Nome" class="block text-sm font-medium" />
                    <x-input id="nome" type="text" name="nome" value="{{ old('nome', $insumo->nome) }}" required autofocus class="w-full border border-gray-300 mt-1 p-2 rounded-md" />
                    <x-input-error :messages="$errors->get('nome')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="validade" value="Validade" class="block text-sm font-medium" />
                    <x-input id="validade" type="date" name="validade" value="{{ old('validade', $insumo->validade) }}" class="w-full border border-gray-300 mt-1 p-2 rounded-md" />
                    <x-input-error :messages="$errors->get('validade')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="marca" value="Marca" class="block text-sm font-medium" />
                    <x-input id="marca" type="text" name="marca" value="{{ old('marca', $insumo->marca) }}" required class="w-full border border-gray-300 mt-1 p-2 rounded-md" />
                    <x-input-error :messages="$errors->get('marca')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="modelo" value="Modelo" class="block text-sm font-medium" />
                    <x-input id="modelo" type="text" name="modelo" value="{{ old('modelo', $insumo->modelo) }}" required class="w-full border border-gray-300 mt-1 p-2 rounded-md" />
                    <x-input-error :messages="$errors->get('modelo')" class="mt-2" />
                </div>

                <div class="mb-6">
                    <x-label for="descricao" value="Descrição" class="block text-sm font-medium" />
                    <textarea id="descricao" name="descricao" rows="3" class="w-full border border-gray-300 mt-1 p-2 rounded-md focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">{{ old('descricao', $insumo->descricao) }}</textarea>
                    <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="quantidade" value="Quantidade" class="block text-sm font-medium" />
                    <x-input id="quantidade" type="number" name="quantidade" value="{{ old('quantidade', $insumo->quantidade) }}" required class="w-full border border-gray-300 mt-1 p-2 rounded-md" min="1" />
                    <x-input-error :messages="$errors->get('quantidade')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="drq" name="drq" value="1" @checked($insumo->drq) class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" />
                        <span class="ml-2 text-sm text-gray-700">{{ __('Possui DRQ?') }}</span>
                        <x-input-error for="drq" class="mt-2" />
                    </label>
                </div>



                <div class="flex justify-end space-x-2 mt-4">
                    <a href="{{ route('insumos.index') }}" class="px-4 py-2 text-white bg-red-700 border rounded hover:bg-red-600">Cancelar</a>
                    <x-button type="submit" class="bg-[#0C2E5C] hover:bg-blue-700">
                        {{ __('Salvar') }}
                    </x-button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
