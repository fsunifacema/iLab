<x-app-layout>
    {{-- Exibição de Erros no Topo --}}
<!--     @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 mx-auto max-w-4xl mt-4" role="alert">
            <strong class="font-bold">Opa! Algo deu errado:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif -->

    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">Criar Roteiro da Solicitação #{{ $solicitacao->codigo }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
            
            {{-- Informações Visuais (Não editáveis) --}}
            <div class="mb-6 p-4 bg-blue-50 rounded border border-blue-200">
                <h3 class="font-semibold text-blue-900 mb-3">Informações da Solicitação</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Professor:</span>
                        <p class="font-medium">{{ $solicitacao->professor->name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Laboratório:</span>
                        <p class="font-medium">{{ $solicitacao->laboratorio->nome }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Data:</span>
                        <p class="font-medium">{{ $solicitacao->data_solicitacao ? \Carbon\Carbon::parse($solicitacao->data_solicitacao)->format('d/m/Y') : 'Não definida' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Horário:</span>
                        <p class="font-medium">
                            {{ $solicitacao->horario_inicio ? \Carbon\Carbon::parse($solicitacao->horario_inicio)->format('H:i') : '--' }} - 
                            {{ $solicitacao->horario_fim ? \Carbon\Carbon::parse($solicitacao->horario_fim)->format('H:i') : '--' }}
                        </p>
                    </div>
                </div>
            </div>

            <form id="createForm" action="{{ route('roteiros.armazenar-solicitacao') }}" method="POST" onsubmit="return trimFormInputs(this)">
                @csrf
                <input type="hidden" name="codigo_solicitacao" value="{{ $solicitacao->codigo }}" />

                {{-- ========================================================================= --}}
                {{-- CORREÇÃO: Formatando os valores nos Inputs Ocultos --}}
                {{-- O Laravel exige formato Y-m-d para datas e H:i para horas --}}
                {{-- ========================================================================= --}}

                {{-- Data (Formato Y-m-d) --}}
                <input type="hidden" name="data" 
                       value="{{ $solicitacao->data_solicitacao ? \Carbon\Carbon::parse($solicitacao->data_solicitacao)->format('Y-m-d') : ($solicitacao->data ? \Carbon\Carbon::parse($solicitacao->data)->format('Y-m-d') : '') }}" />
                
                {{-- Horário Início (Formato H:i - Sem segundos) --}}
                <input type="hidden" name="horario_inicio" 
                       value="{{ $solicitacao->horario_inicio ? \Carbon\Carbon::parse($solicitacao->horario_inicio)->format('H:i') : '' }}" />
                
                {{-- Horário Fim (Formato H:i - Sem segundos) --}}
                <input type="hidden" name="horario_fim" 
                       value="{{ $solicitacao->horario_fim ? \Carbon\Carbon::parse($solicitacao->horario_fim)->format('H:i') : '' }}" />
                
                {{-- Laboratório --}}
                <input type="hidden" name="laboratorio" value="{{ $solicitacao->laboratorio->nome }}" />

                {{-- ========================================================================= --}}
                {{-- Campos Editáveis do Roteiro --}}
                {{-- ========================================================================= --}} 

                {{-- Curso --}}
                <div class="mb-4">
                    <x-label for="curso" value="Curso *" class="block text-sm font-medium text-gray-700" />
                    {{-- Tenta pegar da solicitacao->curso se não tiver roteiro --}}
                    <x-input id="curso" type="text" name="curso" 
                        :value="old('curso', $solicitacao->roteiro?->curso ?? $solicitacao->curso ?? '')" 
                        required class="w-full" />
                    <x-input-error for="curso" :messages="$errors->get('curso')" class="mt-2" />
                </div>

                {{-- Unidade Curricular --}}
                <div class="mb-4">
                    <x-label for="unidade_curricular" value="Unidade Curricular *" class="block text-sm font-medium text-gray-700" />
                    <x-input id="unidade_curricular" type="text" name="unidade_curricular" 
                        :value="old('unidade_curricular', $solicitacao->roteiro?->unidade_curricular ?? $solicitacao->unidade_curricular ?? '')" 
                        required class="w-full" />
                    <x-input-error for="unidade_curricular" :messages="$errors->get('unidade_curricular')" class="mt-2" />
                </div>

                {{-- Período --}}
                <div class="mb-4">
                    <x-label for="periodo" value="Período *" class="block text-sm font-medium text-gray-700" />
                    <x-input id="periodo" type="text" name="periodo" 
                        :value="old('periodo', $solicitacao->roteiro?->periodo ?? $solicitacao->periodo ?? '')" 
                        required class="w-full" />
                    <x-input-error for="periodo" :messages="$errors->get('periodo')" class="mt-2" />
                </div>

                {{-- Quantidade de Alunos --}}
                <div class="mb-4">
                    <x-label for="quantidade_alunos" value="Quantidade de Alunos *" class="block text-sm font-medium text-gray-700" />
                    <x-input id="quantidade_alunos" type="number" name="quantidade_alunos" 
                        :value="old('quantidade_alunos', $solicitacao->roteiro?->quantidade_alunos ?? $solicitacao->quantidade_alunos ?? '')" 
                        required class="w-full" />
                    <x-input-error for="quantidade_alunos" :messages="$errors->get('quantidade_alunos')" class="mt-2" />
                </div>

                {{-- Carga Horária --}}
                <div class="mb-4">
                    <x-label for="carga_horaria" value="Carga Horária *" class="block text-sm font-medium text-gray-700" />
                    <x-input id="carga_horaria" type="text" name="carga_horaria" 
                        :value="old('carga_horaria', $solicitacao->roteiro?->carga_horaria ?? $solicitacao->carga_horaria ?? '')" 
                        required class="w-full" placeholder="Ex: 2 horas" />
                    <x-input-error for="carga_horaria" :messages="$errors->get('carga_horaria')" class="mt-2" />
                </div>

                {{-- Número de Práticas --}}
                <div class="mb-4">
                    <x-label for="numero_praticas" value="Número de Práticas *" class="block text-sm font-medium text-gray-700" />
                    <x-input id="numero_praticas" type="number" name="numero_praticas" 
                        :value="old('numero_praticas', $solicitacao->roteiro?->numero_praticas ?? $solicitacao->numero_praticas ?? 1)" 
                        required class="w-full" />
                    <x-input-error for="numero_praticas" :messages="$errors->get('numero_praticas')" class="mt-2" />
                </div>

                {{-- Tema --}}
                <div class="mb-4">
                    <x-label for="tema" value="Tema *" class="block text-sm font-medium text-gray-700" />
                    <x-input id="tema" type="text" name="tema" 
                        :value="old('tema', $solicitacao->roteiro?->tema ?? $solicitacao->tema ?? '')" 
                        required class="w-full" />
                    <x-input-error for="tema" :messages="$errors->get('tema')" class="mt-2" />
                </div>

                {{-- Conteúdo e outros textareas --}}
                <div class="mb-4">
                    <x-label for="conteudo" value="Conteúdo" class="block text-sm font-medium text-gray-700" />
                    <x-textarea id="conteudo" name="conteudo" :value="old('conteudo', $solicitacao->roteiro?->conteudo ?? '')" class="w-full" rows="3"></x-textarea>
                     <x-input-error for="conteudo" :messages="$errors->get('conteudo')" class="mt-2" />
                </div>
                 <div class="mb-4">
                    <x-label for="objetivos" value="Objetivos" class="block text-sm font-medium text-gray-700" />
                    <x-textarea id="objetivos" name="objetivos" :value="old('objetivos', $solicitacao->roteiro?->objetivos ?? '')" class="w-full" rows="3"></x-textarea>
                     <x-input-error for="objetivos" :messages="$errors->get('objetivos')" class="mt-2" />
                </div>
                 <div class="mb-4">
                    <x-label for="metodologia" value="Metodologia" class="block text-sm font-medium text-gray-700" />
                    <x-textarea id="metodologia" name="metodologia" :value="old('metodologia', $solicitacao->roteiro?->metodologia ?? '')" class="w-full" rows="3"></x-textarea>
                     <x-input-error for="metodologia" :messages="$errors->get('metodologia')" class="mt-2" />
                </div>
                 <div class="mb-4">
                    <x-label for="recursos" value="Recursos" class="block text-sm font-medium text-gray-700" />
                    <x-textarea id="recursos" name="recursos" :value="old('recursos', $solicitacao->roteiro?->recursos ?? '')" class="w-full" rows="3"></x-textarea>
                     <x-input-error for="recursos" :messages="$errors->get('recursos')" class="mt-2" />
                </div>
                 <div class="mb-4">
                    <x-label for="avaliacao" value="Avaliação" class="block text-sm font-medium text-gray-700" />
                    <x-textarea id="avaliacao" name="avaliacao" :value="old('avaliacao', $solicitacao->roteiro?->avaliacao ?? '')" class="w-full" rows="3"></x-textarea>
                     <x-input-error for="avaliacao" :messages="$errors->get('avaliacao')" class="mt-2" />
                </div>
                 <div class="mb-4">
                    <x-label for="observacoes" value="Observações" class="block text-sm font-medium text-gray-700" />
                    <x-textarea id="observacoes" name="observacoes" :value="old('observacoes', $solicitacao->roteiro?->observacoes ?? '')" class="w-full" rows="3"></x-textarea>
                     <x-input-error for="observacoes" :messages="$errors->get('observacoes')" class="mt-2" />
                </div>

                {{-- Botões --}}
                <div class="flex justify-end space-x-2 mt-4">
                    <a href="{{ route('roteiros.index') }}" class="inline-flex items-center px-4 py-2 text-white bg-gray-800 rounded-md hover:bg-red-600 transition">Cancelar</a>
                    <x-button type="submit" class="bg-[#0C2E5C]">Salvar</x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>