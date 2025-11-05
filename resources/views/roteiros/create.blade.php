<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">Adicionar Roteiro</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
            <form id="createForm" action="{{ route('roteiros.store') }}" method="POST" onsubmit="return trimFormInputs(this)">
                @csrf

                <div class="mb-4">
                    <x-label for="curso" value="Curso" class="block text-sm font-medium text-gray-700" />
                    <x-input id="curso" type="text" name="curso" :value="old('curso')" required class="w-full" />
                    <x-input-error for="curso" :messages="$errors->get('curso')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="unidade_curricular" value="Unidade Curricular" class="block text-sm font-medium text-gray-700" />
                    <x-input id="unidade_curricular" type="text" name="unidade_curricular" :value="old('unidade_curricular')" required class="w-full" />
                    <x-input-error for="unidade_curricular" :messages="$errors->get('unidade_curricular')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="professor" value="Professor" class="block text-sm font-medium text-gray-700" />
                    <x-input id="professor" type="text" name="professor"
                        value="{{ Auth::user()->name ?? 'Usuário Indefinido' }}"
                        readonly class="w-full bg-gray-100 cursor-not-allowed" />
                    <x-input-error for="professor" :messages="$errors->get('professor')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="periodo" value="Período" class="block text-sm font-medium text-gray-700" />
                    <x-input id="periodo" type="text" name="periodo" :value="old('periodo')" required class="w-full" />
                    <x-input-error for="periodo" :messages="$errors->get('periodo')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="quantidade_alunos" value="Quantidade de Alunos" class="block text-sm font-medium text-gray-700" />
                    <x-input id="quantidade_alunos" type="number" name="quantidade_alunos" :value="old('quantidade_alunos')" required min="0" step="1" class="w-full" />
                    <x-input-error for="quantidade_alunos" :messages="$errors->get('quantidade_alunos')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="data" value="Data" class="block text-sm font-medium text-gray-700" />
                    <x-input id="data" type="date" name="data" :value="old('data')" required class="w-full" />
                    <x-input-error for="data" :messages="$errors->get('data')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="horario_inicio" value="Horário Inicio" class="block text-sm font-medium text-gray-700" />
                    <x-input id="horario_inicio" type="time" name="horario_inicio" :value="old('horario_inicio')" required class="w-full" />
                    <x-input-error for="horario_inicio" :messages="$errors->get('horario_inicio')" class="mt-2" />
                </div>
                <div class="mb-4">
                    <x-label for="horario_fim" value="Horário Fim" class="block text-sm font-medium text-gray-700" />
                    <x-input id="horario_fim" type="time" name="horario_fim" :value="old('horario_fim')" required class="w-full" />
                    <x-input-error for="horario_fim" :messages="$errors->get('horario_fim')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="carga_horaria" value="Carga Horária" class="block text-sm font-medium text-gray-700" />
                    <x-input id="carga_horaria" type="text" name="carga_horaria" :value="old('carga_horaria')" required class="w-full" />
                    <x-input-error for="carga_horaria" :messages="$errors->get('carga_horaria')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="numero_praticas" value="Número de Práticas" class="block text-sm font-medium text-gray-700" />
                    <x-input id="numero_praticas" type="number" name="numero_praticas" :value="old('numero_praticas')" required min="1" step="1" class="w-full" />
                    <x-input-error for="numero_praticas" :messages="$errors->get('numero_praticas')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-label for="laboratorio" value="Laboratório" class="block text-sm font-medium text-gray-700" />
                    <select id="laboratorio" name="laboratorio" required
                        class="p-1 border-gray-300 focus:border-[#0C2E5C] focus:ring-[#0C2E5C] rounded-md shadow-sm w-full">
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
                    <x-input id="tema" type="text" name="tema" :value="old('tema')" required class="w-full" />
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

    <!-- Modal com animação -->
    <div id="continueModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 transition-opacity duration-300">
        <div id="modalContent"
            class="bg-white rounded-2xl shadow-2xl p-6 w-96 text-center transform scale-90 opacity-0 transition-all duration-300">
            <div class="flex justify-center mb-3">
                <div class="bg-[#0C2E5C] text-white rounded-full p-3 text-2xl">💾</div>
            </div>
            <h3 class="text-lg font-semibold mb-3 text-[#0C2E5C]">Atenção! Você tem dados não salvos. </h3>
            <p class="text-gray-700 mb-6">Deseja continuar de onde parou?</p>
            <div class="flex justify-center gap-4">
                <button id="continueYes"
                    class="px-4 py-2 bg-[#0C2E5C] text-white rounded-lg hover:bg-[#103A7A] transition cursor-pointer">Sim</button>
                <button id="continueNo"
                    class="px-4 py-2 bg-gray-300 text-white rounded-lg hover:bg-red-600 focus:bg:red-700 active bg-red-800 transition cursor-pointer">Não</button>
            </div>
        </div>
    </div>
    <script>
        const form = document.getElementById('createForm');
        const fields = form.querySelectorAll('input, textarea');
        const modal = document.getElementById('continueModal');
        const modalContent = document.getElementById('modalContent');
        const btnYes = document.getElementById('continueYes');
        const btnNo = document.getElementById('continueNo');

        // Salvar dados enquanto o usuário digita
        fields.forEach(field => {
            field.addEventListener('input', () => {
                localStorage.setItem(field.name, field.value);
            });
        });

        // Função que mostra o modal animado
        function showModal() {
            return new Promise(resolve => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    modalContent.classList.remove('opacity-0', 'scale-90');
                    modalContent.classList.add('opacity-100', 'scale-100');
                }, 10);

                btnYes.onclick = () => closeModal(true);
                btnNo.onclick = () => closeModal(false);

                function closeModal(result) {
                    modalContent.classList.remove('opacity-100', 'scale-100');
                    modalContent.classList.add('opacity-0', 'scale-90');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        resolve(result);
                    }, 250);
                }
            });
        }

        window.onload = async function () {
            if (Object.keys(localStorage).length > 0) {
                const continueSaved = await showModal();

                if (continueSaved) {
                    fields.forEach(field => {
                        const savedValue = localStorage.getItem(field.name);
                        if (savedValue) field.value = savedValue;
                    });
                } else {
                    localStorage.clear();
                }
            }
        };

        form.addEventListener('submit', () => localStorage.clear());
    </script>
</x-app-layout>
