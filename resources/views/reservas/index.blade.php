<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">
            Gerenciamento de Reservas
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <a class="fixed bottom-6 right-6 bg-[#0C2E5C] text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-blue-800 transition duration-200">
                + Nova Reserva
            </a>


            <div class="p-3 overflow-x-auto">
                <div class="w-full">
                    <!-- Controles: busca -->
                    
                        <div class="p-1 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="query" placeholder="Buscar por nome, tipo ou localização..." class="border rounded px-3 py-2 text-sm w-full md:w-80" />
                                <button type="button" @click="query=''" class="px-3 py-2 bg-gray-100 rounded text-sm">Limpar</button>
                            </div>
                        </div>

                        <!-- Cabeçalho (desktop) -->
                        <div class="hidden md:grid grid-cols-5 bg-[#0C2E5C] text-white px-4 py-2 rounded-t-md text-sm font-semibold mt-3">
                            <div>Laboratório</div>
                            <div>Data</div>
                            <div>Horário</div>
                            <div>Professor</div>
                            <div class="text-center">Ações</div>
                        </div>

                        <!-- Lista -->
                        <div class="space-y-3 mt-2">
                            <!-- Desktop: linha -->
                            <div class="hidden md:grid grid-cols-5 items-center bg-white px-4 py-2 rounded shadow border border-gray-200 hover:shadow-md transition">
                                <div class="text-sm text-gray-700">Laboratorio de Informatica II</div>
                                <div class="text-sm text-gray-700">13/03</div>
                                <div class="text-sm text-gray-700">18:30</div>
                                <div class="text-sm text-gray-700">Marcos Rocha</div>
                                <div class="text-center flex justify-center gap-2">
                                        <div class="relative group inline-block">
                                            <!-- Ícone -->
                                            <x-icons-logo name="edit"/>

                                            <!-- Tooltip -->
                                            <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition duration-200 pointer-events-none">
                                                Editar
                                            </div>
                                        </div>
                                        <div class="relative group inline-block">   
                                            <x-icons-logo name="delete"/>
                                            <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition duration-200 pointer-events-none">
                                                Excluir
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div class="hidden md:grid grid-cols-5 items-center bg-white px-4 py-2 rounded shadow border border-gray-200 hover:shadow-md transition">
                                <div class="text-sm text-gray-700">Laboratorio de Informatica II</div>
                                <div class="text-sm text-gray-700">13/03</div>
                                <div class="text-sm text-gray-700">18:30</div>
                                <div class="text-sm text-gray-700">Marcos Rocha</div>
                                <div class="text-center flex justify-center gap-2">
                                        <div class="relative group inline-block">
                                            <!-- Ícone -->
                                            <x-icons-logo name="edit"/>

                                            <!-- Tooltip -->
                                            <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition duration-200 pointer-events-none">
                                                Editar
                                            </div>
                                        </div>
                                        <div class="relative group inline-block">   
                                            <x-icons-logo name="delete"/>
                                            <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition duration-200 pointer-events-none">
                                                Excluir
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div class="hidden md:grid grid-cols-5 items-center bg-white px-4 py-2 rounded shadow border border-gray-200 hover:shadow-md transition">
                                <div class="text-sm text-gray-700">Laboratorio de Informatica II</div>
                                <div class="text-sm text-gray-700">13/03</div>
                                <div class="text-sm text-gray-700">18:30</div>
                                <div class="text-sm text-gray-700">Marcos Rocha</div>
                                <div class="text-center flex justify-center gap-2">
                                        <div class="relative group inline-block">
                                            <!-- Ícone -->
                                            <x-icons-logo name="edit"/>

                                            <!-- Tooltip -->
                                            <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition duration-200 pointer-events-none">
                                                Editar
                                            </div>
                                        </div>
                                        <div class="relative group inline-block">   
                                            <x-icons-logo name="delete"/>
                                            <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition duration-200 pointer-events-none">
                                                Excluir
                                            </div>
                                        </div>
                                </div>
                            </div>                            

                            <!-- Mobile: card -->
                            <div class="md:hidden bg-white px-4 py-3 rounded shadow border border-gray-200">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <div class="text-xs text-gray-500">Laboratório</div>
                                        <div class="text-sm text-gray-700 font-medium">Laboratorio de Informatica II</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Professor</div>
                                        <div class="text-sm text-gray-700">Marcos Rocha</div>
                                    </div>
                                </div>

                                <div class="flex text-gray-700 items-start">
                                    <div class="mr-15">
                                        <div class="text-xs text-gray-500">Data</div>
                                        <div class="text-sm text-gray-700 font-medium">13/03</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Horário</div>
                                        <div class="text-sm text-gray-700 font-medium">18:30</div>
                                    </div>
                                </div>
                                    <div class="mt-3 flex items-center gap-3">
                                        <a class="text-sm text-[#0C2E5C] font-medium">Editar</a>
                                        <button class="cursor-pointer text-sm text-red-600 font-medium">Excluir</button>
                                    </div>
                                
                            </div>
                            <div class="md:hidden bg-white px-4 py-3 rounded shadow border border-gray-200">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <div class="text-xs text-gray-500">Laboratório</div>
                                        <div class="text-sm text-gray-700 font-medium">Laboratorio de Informatica II</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Professor</div>
                                        <div class="text-sm text-gray-700">Marcos Rocha</div>
                                    </div>
                                </div>

                                <div class="flex text-gray-700 items-start">
                                    <div class="mr-15">
                                        <div class="text-xs text-gray-500">Data</div>
                                        <div class="text-sm text-gray-700 font-medium">13/03</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Horário</div>
                                        <div class="text-sm text-gray-700 font-medium">18:30</div>
                                    </div>
                                </div>
                                    <div class="mt-3 flex items-center gap-3">
                                        <a class="text-sm text-[#0C2E5C] font-medium">Editar</a>
                                        <button class="cursor-pointer text-sm text-red-600 font-medium">Excluir</button>
                                    </div>
                                
                            </div>
                            <div class="md:hidden bg-white px-4 py-3 rounded shadow border border-gray-200">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <div class="text-xs text-gray-500">Laboratório</div>
                                        <div class="text-sm text-gray-700 font-medium">Laboratorio de Informatica II</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Professor</div>
                                        <div class="text-sm text-gray-700">Marcos Rocha</div>
                                    </div>
                                </div>

                                <div class="flex text-gray-700 items-start">
                                    <div class="mr-15">
                                        <div class="text-xs text-gray-500">Data</div>
                                        <div class="text-sm text-gray-700 font-medium">13/03</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Horário</div>
                                        <div class="text-sm text-gray-700 font-medium">18:30</div>
                                    </div>
                                </div>
                                    <div class="mt-3 flex items-center gap-3">
                                        <a class="text-sm text-[#0C2E5C] font-medium">Editar</a>
                                        <button class="cursor-pointer text-sm text-red-600 font-medium">Excluir</button>
                                    </div>
                                
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>