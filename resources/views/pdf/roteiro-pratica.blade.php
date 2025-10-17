<x-app-layout>
    <!-- Logo e Missão fora do cabeçalho -->
    <div  class="flex justify-between items-center py-8">
        <div class="max-w-6xl mx-auto sm:px-4 lg:px-9">
        <!-- Logo à esquerda -->
        <img src="{{ asset('img/logo-facema-azul.png') }}" alt="Timbre UniFacema" class="w-[300px]">
        </div>

        <!-- Missão à direita -->
        <div class="max-w-6xl mx-auto sm:px-4 lg:px-9">
        <div class="text-right text-sm text-[#0C2E5C]">
            Missão: <span  class="font-semibold">"Contribuir para o desenvolvimento sustentável por meio do ensino superior</span>
            <br>
            <span  class="font-semibold ">inovador em um ambiente de qualidade."</span>
        </div>
        </div>
    </div>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="p-3 overflow-x-auto">
                <div class="w-full">
                    <div class="space-y-4">
                        <div class="flex">
                            <strong class="w-1/3">CURSO/ CAMPUS:</strong>
                            <span class="flex-1">{{ $curso ?? '______________________________________________' }}</span>
                        </div>
                        <div class="flex">
                            <strong class="w-1/3">UNIDADE CURRICULAR:</strong>
                            <span class="flex-1">{{ $unidade ?? '______________________________________________' }}</span>
                        </div>
                        <div class="flex">
                            <strong class="w-1/3">PROFESSOR:</strong>
                            <span class="flex-1">{{ $professor ?? '______________________________________________' }}</span>
                        </div>
                        <div class="flex">
                            <strong class="w-1/3">PERÍODO:</strong>
                            <span class="flex-1">{{ $periodo ?? '____________________' }}</span>
                        </div>
                        <div class="flex">
                            <strong class="w-1/3">QUANT. DE ALUNOS:</strong>
                            <span class="flex-1">{{ $quantidade_alunos ?? '________' }}</span>
                        </div>
                        <div class="flex">
                            <strong class="w-1/3">DATA:</strong>
                            <span class="flex-1">{{ $data ?? '____/____/________' }}</span>
                        </div>
                        <div class="flex">
                            <strong class="w-1/3">HORÁRIO:</strong>
                            <span class="flex-1">{{ $horario ?? '____________________' }}</span>
                        </div>
                        <div class="flex">
                            <strong class="w-1/3">CARGA HORÁRIA DA AULA PRÁTICA:</strong>
                            <span class="flex-1">{{ $carga_horaria ?? '________' }}</span>
                        </div>
                        <div class="flex">
                            <strong class="w-1/3">Nº DE PRÁTICAS:</strong>
                            <span class="flex-1">{{ $numero_praticas ?? '________' }}</span>
                        </div>
                        <div class="flex">
                            <strong class="w-1/3">LABORATÓRIO / CENÁRIO DE PRÁTICA:</strong>
                            <span class="flex-1">{{ $laboratorio ?? '____________________________' }}</span>
                        </div>
                    </div>

                    <div class="mt-8 text-lg font-bold uppercase">CONTEÚDO DA AULA PRÁTICA (CONFORME PLANO DE ENSINO)</div>
                    <div class="border border-black p-2 mt-2 min-h-[90px]">{{ $conteudo ?? '' }}</div>

                    <div class="mt-8 text-lg font-bold uppercase">TEMA DA AULA PRÁTICA</div>
                    <div class="border border-black p-2 mt-2 min-h-[90px]">{{ $tema ?? '' }}</div>

                    <div class="mt-8 text-lg font-bold uppercase">OBJETIVO(S)</div>
                    <div class="border border-black p-2 mt-2 min-h-[90px]">{{ $objetivos ?? '' }}</div>

                    <div class="mt-8 text-lg font-bold uppercase">METODOLOGIA DA AULA PRÁTICA</div>
                    <div class="border border-black p-2 mt-2 min-h-[90px]">{{ $metodologia ?? '' }}</div>

                    <div class="mt-8 text-lg font-bold uppercase">RECURSOS E MATERIAIS UTILIZADOS</div>
                    <div class="border border-black p-2 mt-2 min-h-[90px]">{{ $recursos ?? '' }}</div>

                    <div class="mt-8 text-lg font-bold uppercase">FORMA DE AVALIAÇÃO DA ATIVIDADE PRÁTICA REALIZADA PELO ALUNO</div>
                    <div class="border border-black p-2 mt-2 min-h-[90px]">{{ $avaliacao ?? '' }}</div>

                    <div class="mt-8 text-lg font-bold uppercase">OBSERVAÇÕES</div>
                    <div class="border border-black p-2 mt-2 min-h-[90px]">{{ $observacoes ?? '' }}</div>

                    <footer class="mt-12 text-center text-sm">
                        Cidade/MA, {{ $data_assinatura ?? '____ de __________________________ de ________' }}.
                    </footer>

                    <div class="mt-16 text-center">
                        <div class="inline-block mt-16">
                            <div class="border-t border-black w-60 mx-auto mb-2"></div>
                            <div>Professor(a) da UC</div>
                        </div>

                        <div class="inline-block mt-16">
                            <div class="border-t border-black w-60 mx-auto mb-2"></div>
                            <div>Coordenação do Curso</div>
                        </div>

                        <div class="inline-block mt-16">
                            <div class="border-t border-black w-60 mx-auto mb-2"></div>
                            <div>Coordenação de Laboratórios</div>
                        </div>

                        <div class="inline-block mt-16">
                            <div class="border-t border-black w-60 mx-auto mb-2"></div>
                            <div>Técnico de Laboratório</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
