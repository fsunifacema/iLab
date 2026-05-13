<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">
            Estoque do Insumo: {{ $insumo->nome }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- Informações do Insumo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-[#0C2E5C] mb-4">Informações do Insumo</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nome</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $insumo->nome }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Marca</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $insumo->marca ?: 'Não informado' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Modelo</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $insumo->modelo ?: 'Não informado' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Descrição</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $insumo->descricao ?: 'Não informado' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-[#0C2E5C] mb-4">Controle de Estoque</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Quantidade Atual</label>
                                <p class="mt-1 text-2xl font-bold text-[#0C2E5C]">{{ $insumo->quantidade }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Validade</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($insumo->validade)
                                        {{ \Carbon\Carbon::parse($insumo->validade)->format('d/m/Y') }}
                                        @if(\Carbon\Carbon::parse($insumo->validade)->isPast())
                                            <span class="text-red-600 font-semibold">(Vencido)</span>
                                        @elseif(\Carbon\Carbon::parse($insumo->validade)->diffInDays() <= 30)
                                            <span class="text-yellow-600 font-semibold">(Vence em breve)</span>
                                        @endif
                                    @else
                                        Não informado
                                    @endif
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">DRQ (Documento de Recebimento de Qualidade)</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $insumo->drq ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $insumo->drq ? 'Possui' : 'Não possui' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('insumos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        ← Voltar para Lista
                    </a>

                    <a href="{{ route('insumos.edit', $insumo->id) }}" class="inline-flex items-center px-4 py-2 bg-[#0C2E5C] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Editar Insumo
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>