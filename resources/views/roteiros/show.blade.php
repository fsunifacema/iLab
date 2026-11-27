<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">
            Detalhes do roteiro
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
            <div class="space-y-4">
                <!-- Exibindo os campos do Roteiro -->
                <div class="flex">
                    <strong class="w-1/3">Curso:</strong>
                    <span class="flex-1">{{ $roteiro->curso }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Unidade curricular:</strong>
                    <span class="flex-1">{{ $roteiro->unidade_curricular }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Professor:</strong>
                    <span class="flex-1">{{ $roteiro->professor }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Período:</strong>
                    <span class="flex-1">{{ $roteiro->periodo }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Quantidade de alunos:</strong>
                    <span class="flex-1">{{ $roteiro->quantidade_alunos }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Data:</strong>
                    <span class="flex-1">{{ \Carbon\Carbon::parse($roteiro->data)->format('d/m/Y') }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Horário de início:</strong>
                    <span class="flex-1">{{ $roteiro->horario_inicio }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Horário de fim:</strong>
                    <span class="flex-1">{{ $roteiro->horario_fim }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Carga horária:</strong>
                    <span class="flex-1">{{ $roteiro->carga_horaria }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Número de práticas:</strong>
                    <span class="flex-1">{{ $roteiro->numero_praticas }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Laboratório:</strong>
                    <span class="flex-1">{{ $roteiro->laboratorio }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Conteúdo:</strong>
                    <span class="flex-1">{{ $roteiro->conteudo }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Tema:</strong>
                    <span class="flex-1">{{ $roteiro->tema }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Objetivos:</strong>
                    <span class="flex-1">{{ $roteiro->objetivos }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Metodologia:</strong>
                    <span class="flex-1">{{ $roteiro->metodologia }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Recursos:</strong>
                    <span class="flex-1">{{ $roteiro->recursos }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Avaliação:</strong>
                    <span class="flex-1">{{ $roteiro->avaliacao }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Observações:</strong>
                    <span class="flex-1">{{ $roteiro->observacoes }}</span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Data de assinatura:</strong>
                    <span class="flex-1">{{ \Carbon\Carbon::parse($roteiro->data_assinatura)->format('d/m/Y') }}</span>
                </div>
            </div>

            <!-- Botões de ação: Editar e Excluir -->
            <div class="flex justify-end mt-6 space-x-4">
                @if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_PROFESSOR]))

                    <a href="{{ route('roteiros.index') }}" class="text-gray-600 font-medium px-4 py-2 border border-gray-600 rounded-md">
                        Voltar
                    </a>
                    <a href="{{ route('roteiros.edit', $roteiro->id) }}" class="text-[#0C2E5C] font-medium px-4 py-2 border border-[#0C2E5C] rounded-md">
                        Editar
                    </a>
                    <form action="{{ route('roteiros.destroy', $roteiro->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Deseja excluir esse roteiro?')" class="cursor-pointer text-red-600 font-medium px-4 py-2 border border-red-600 rounded-md">
                            Excluir
                        </button>
                    </form>
                    @else
                    <a href="{{ route('roteiros.index') }}" class="text-gray-600 font-medium px-4 py-2 border border-gray-600 rounded-md">
                        Voltar
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
