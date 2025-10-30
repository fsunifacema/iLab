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
                    <input 
                        type="text" 
                        name="nome" 
                        class="w-full border border-gray-300 mt-1 p-1 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500" 
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Capacidade</label>
                    <input 
                        type="number" 
                        name="capacidade" 
                        class="w-full border border-gray-300 mt-1 p-1 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500" 
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Tipo</label>
                    <input 
                        type="text" 
                        name="tipo" 
                        class="w-full border border-gray-300 mt-1 p-1 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500" 
                        required
                    >
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700">Localização</label>
                    <input 
                        type="text" 
                        name="localizacao" 
                        class="w-full border border-gray-300 mt-1 p-1 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500" 
                        required
                    >
                </div>
                
                <div class="flex justify-end space-x-2 mt-4">
                    <a href="{{ route('laboratorios.index') }}" class="px-4 py-2 text-white bg-red-700 border rounded hover:bg-red-600">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-[#0C2E5C] text-white rounded hover:bg-blue-700">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>