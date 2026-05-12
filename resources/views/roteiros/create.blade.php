<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">Adicionar Roteiro</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
            <form id="createForm" action="{{ route('roteiros.store') }}" method="POST" onsubmit="return trimFormInputs(this)" 
                x-data="roteirosForm()" @submit="onSubmit">
                @csrf

                {{-- Seleção de Solicitação (MANTÉM IGUAL) --}}
                <div class="mb-6 p-4 bg-blue-50 rounded border border-blue-200">
                    <x-label for="codigo_solicitacao" value="Solicitação Aprovada *" class="block text-sm font-medium text-gray-700" />
                    
                    <select id="codigo_solicitacao" name="codigo_solicitacao" required x-model="selectedSolicitacao" @change="preencherDados()"
                        class="p-2 border-gray-300 focus:border-[#0C2E5C] focus:ring-[#0C2E5C] rounded-md shadow-sm w-full mt-1">
                        <option value="">Selecione uma solicitação aprovada</option>
                        @foreach ($solicitacoes as $sol)
                             {{-- (Seu código PHP do option aqui continua igual...) --}}
                             @php
                                $dataFormatada = $sol->data_solicitacao ? \Carbon\Carbon::parse($sol->data_solicitacao)->format('Y-m-d') : ($sol->data ? \Carbon\Carbon::parse($sol->data)->format('Y-m-d') : '');
                                $inicioFormatado = $sol->horario_inicio ? \Carbon\Carbon::parse($sol->horario_inicio)->format('H:i') : '';
                                $fimFormatado = $sol->horario_fim ? \Carbon\Carbon::parse($sol->horario_fim)->format('H:i') : '';
                            @endphp
                            <option value="{{ $sol->codigo }}" data-json="{{ json_encode([
                                'data' => $dataFormatada,
                                'horario_inicio' => $inicioFormatado,
                                'horario_fim' => $fimFormatado,
                                'laboratorio' => $sol->laboratorio->nome ?? '',
                                'unidade_curricular' => $sol->unidade_curricular ?? ($sol->roteiro?->unidade_curricular ?? ''),
                                'curso' => $sol->curso ?? ($sol->roteiro?->curso ?? ''),
                                'periodo' => $sol->periodo ?? ($sol->roteiro?->periodo ?? ''),
                                'quantidade_alunos' => $sol->quantidade_alunos ?? ($sol->roteiro?->quantidade_alunos ?? 0),
                            ]) }}">
                                #{{ $sol->codigo }} - {{ $sol->unidade_curricular ?? ($sol->roteiro?->unidade_curricular ?? 'Sem unidade') }} ({{ $sol->laboratorio->nome }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error for="codigo_solicitacao" :messages="$errors->get('codigo_solicitacao')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="curso" value="Curso" class="block text-sm font-medium text-gray-700" />
                    <x-input id="curso" type="text" name="curso" :value="old('curso')" x-model="dados.curso" class="w-full" />
                    <x-input-error for="curso" :messages="$errors->get('curso')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="unidade_curricular" value="Unidade Curricular" class="block text-sm font-medium text-gray-700" />
                    <x-input id="unidade_curricular" type="text" name="unidade_curricular" :value="old('unidade_curricular')" x-model="dados.unidade_curricular" class="w-full" />
                    <x-input-error for="unidade_curricular" :messages="$errors->get('unidade_curricular')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="professor" value="Professor" class="block text-sm font-medium text-gray-700" />
                    <x-input id="professor" type="text" name="professor"
                        value="{{ Auth::user()->name ?? 'Usuário Indefinido' }}"
                        readonly class="w-full bg-gray-100 cursor-not-allowed" />
                </div>

                {{-- PERIODO: Bloqueado --}}
                <div class="mb-4">
                    <x-label for="periodo" value="Período" class="block text-sm font-medium text-gray-700" />
                    <x-input id="periodo" type="text" name="periodo" :value="old('periodo')" x-model="dados.periodo" class="w-full" />
                    <x-input-error for="periodo" :messages="$errors->get('periodo')" class="mt-2" />
                </div>

                {{-- QUANTIDADE ALUNOS --}}
                <div class="mb-4">
                    <x-label for="quantidade_alunos" value="Quantidade de Alunos" class="block text-sm font-medium text-gray-700" />
                    <x-input id="quantidade_alunos" type="number" name="quantidade_alunos" :value="old('quantidade_alunos')" x-model="dados.quantidade_alunos" min="0" step="1" class="w-full" />
                    <x-input-error for="quantidade_alunos" :messages="$errors->get('quantidade_alunos')" class="mt-2" />
                </div>

                {{-- DATA: Bloqueado --}}
                <div class="mb-4">
                    <x-label for="data" value="Data" class="block text-sm font-medium text-gray-700" />
                    <x-input id="data" type="date" name="data" :value="old('data')" x-model="dados.data" 
                        readonly class="w-full bg-gray-100 cursor-not-allowed text-gray-500 focus:ring-0" />
                    <x-input-error for="data" :messages="$errors->get('data')" class="mt-2" />
                </div>

                {{-- HORARIOS: Bloqueados --}}
                <div class="mb-4">
                    <x-label for="horario_inicio" value="Horário Inicio" class="block text-sm font-medium text-gray-700" />
                    <x-input id="horario_inicio" type="time" name="horario_inicio" :value="old('horario_inicio')" x-model="dados.horario_inicio" 
                        readonly class="w-full bg-gray-100 cursor-not-allowed text-gray-500 focus:ring-0" />
                    <x-input-error for="horario_inicio" :messages="$errors->get('horario_inicio')" class="mt-2" />
                </div>
                <div class="mb-4">
                    <x-label for="horario_fim" value="Horário Fim" class="block text-sm font-medium text-gray-700" />
                    <x-input id="horario_fim" type="time" name="horario_fim" :value="old('horario_fim')" x-model="dados.horario_fim" 
                        readonly class="w-full bg-gray-100 cursor-not-allowed text-gray-500 focus:ring-0" />
                    <x-input-error for="horario_fim" :messages="$errors->get('horario_fim')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="carga_horaria" value="Carga Horária" class="block text-sm font-medium text-gray-700" />
                    <x-input id="carga_horaria" type="text" name="carga_horaria" :value="old('carga_horaria')" class="w-full" placeholder="Ex: 2 horas" />
                    <x-input-error for="carga_horaria" :messages="$errors->get('carga_horaria')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="numero_praticas" value="Número de Práticas" class="block text-sm font-medium text-gray-700" />
                    <x-input id="numero_praticas" type="number" name="numero_praticas" :value="old('numero_praticas')" min="1" step="1" class="w-full" />
                    <x-input-error for="numero_praticas" :messages="$errors->get('numero_praticas')" class="mt-2" />
                </div>

                {{-- LABORATORIO: Bloqueado (Select precisa de tratamento especial) --}}
                <div class="mb-4">
                    <x-label for="laboratorio" value="Laboratório" class="block text-sm font-medium text-gray-700" />
                    <select id="laboratorio" name="laboratorio" x-model="dados.laboratorio" tabindex="-1"
                        class="p-2 border-gray-300 focus:border-[#0C2E5C] focus:ring-[#0C2E5C] rounded-md shadow-sm w-full pointer-events-none bg-gray-100 text-gray-500 cursor-not-allowed">
                        <option value="">Selecione um laboratório</option>
                        @foreach ($laboratorios as $lab)
                            <option value="{{ $lab->nome }}" {{ old('laboratorio') == $lab->nome ? 'selected' : '' }}>
                                {{ $lab->nome }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error for="laboratorio" :messages="$errors->get('laboratorio')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="tema" value="Tema" class="block text-sm font-medium text-gray-700" />
                    <x-input id="tema" type="text" name="tema" :value="old('tema')" class="w-full" />
                    <x-input-error for="tema" :messages="$errors->get('tema')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="conteudo" value="Conteúdo" class="block text-sm font-medium text-gray-700" />
                    <x-textarea id="conteudo" name="conteudo" :value="old('conteudo')" class="w-full" rows="4"></x-textarea>
                    <x-input-error for="conteudo" :messages="$errors->get('conteudo')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="objetivos" value="Objetivos" class="block text-sm font-medium text-gray-700" />
                    <x-textarea id="objetivos" name="objetivos" :value="old('objetivos')" class="w-full" rows="4"></x-textarea>
                    <x-input-error for="objetivos" :messages="$errors->get('objetivos')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="metodologia" value="Metodologia" class="block text-sm font-medium text-gray-700" />
                    <x-textarea id="metodologia" name="metodologia" :value="old('metodologia')" class="w-full" rows="4"></x-textarea>
                    <x-input-error for="metodologia" :messages="$errors->get('metodologia')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="recursos" value="Recursos" class="block text-sm font-medium text-gray-700" />
                    <x-textarea id="recursos" name="recursos" :value="old('recursos')" class="w-full" rows="4"></x-textarea>
                    <x-input-error for="recursos" :messages="$errors->get('recursos')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="avaliacao" value="Avaliação" class="block text-sm font-medium text-gray-700" />
                    <x-textarea id="avaliacao" name="avaliacao" :value="old('avaliacao')" class="w-full" rows="4"></x-textarea>
                    <x-input-error for="avaliacao" :messages="$errors->get('avaliacao')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="observacoes" value="Observações" class="block text-sm font-medium text-gray-700" />
                    <x-textarea id="observacoes" name="observacoes" :value="old('observacoes')" class="w-full" rows="4"></x-textarea>
                    <x-input-error for="observacoes" :messages="$errors->get('observacoes')" class="mt-2" />
                </div>

                <div class="flex justify-end space-x-2 mt-4">
                    <a href="{{ route('roteiros.index') }}" class="inline-flex items-center px-4 py-2 text-white bg-gray-800 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-red-600 focus:bg:red-700 active bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">Cancelar</a>
                    <x-button type="submit" class="bg-[#0C2E5C]">Salvar</x-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function roteirosForm() {
            return {
                selectedSolicitacao: `{{ old('codigo_solicitacao') }}`,
                dados: {
                    curso: `{{ old('curso') }}`,
                    unidade_curricular: `{{ old('unidade_curricular') }}`,
                    periodo: `{{ old('periodo') }}`,
                    quantidade_alunos: `{{ old('quantidade_alunos', 0) }}`,
                    data: `{{ old('data') }}`,
                    horario_inicio: `{{ old('horario_inicio') }}`,
                    horario_fim: `{{ old('horario_fim') }}`,
                    laboratorio: `{{ old('laboratorio') }}`,
                },
                preencherDados() {
                    if (!this.selectedSolicitacao) return;
                    
                    const select = document.querySelector(`option[value="${this.selectedSolicitacao}"]`);
                    if (!select || !select.dataset.json) return;
                    
                    try {
                        const dados = JSON.parse(select.dataset.json);
                        // Atualiza o objeto dados com o que veio do JSON
                        this.dados = { ...this.dados, ...dados };
                    } catch (e) {
                        console.error('Erro ao parsear dados:', e);
                    }
                },
                onSubmit(event) {
                    if (!trimFormInputs(document.getElementById('createForm'))) {
                        event.preventDefault();
                        return false;
                    }
                }
            };
        }
    </script>
</x-app-layout>