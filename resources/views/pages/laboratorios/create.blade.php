<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Adicionar Laboratório</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
            <form action="{{ route('laboratorios.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700">Nome</label>
                    <input type="text" name="nome_laboratorio" class="form-input w-full" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Capacidade</label>
                    <input type="number" name="capacidade_laboratorio" class="form-input w-full" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Tipo</label>
                    <input type="text" name="tipo_laboratorio" class="form-input w-full" required>
                </div>
                <div>
                    <label class="">Localização</label>
                </div>
                <div class="flex justify-end space-x-2 mt-4">
                    <a href="{{ route('laboratorios.index') }}" class="px-4 py-2 text-white bg-red-700 border rounded hover:bg-red-600">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-[#0C2E5C] text-white rounded hover:bg-blue-700">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>