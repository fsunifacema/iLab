<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendário de Práticas') }}
        </h2>
    </x-slot>

    {{-- BLOCO DE ESTILO PARA AJUSTAR O FULLCALENDAR EM MOBILE E CAPITALIZAR O MÊS --}}
    <style>
        @media (max-width: 767px) {
            /* Reduz o padding e o tamanho da fonte nos botões do cabeçalho do FullCalendar */
            .fc .fc-toolbar-chunk button {
                font-size: 0.75rem !important; /* Texto menor */
                padding: 0.3rem 0.5rem !important; /* Padding reduzido */
            }
            /* Reduz o tamanho da fonte do título do calendário */
            .fc .fc-toolbar-title {
                font-size: 1.2rem !important;
            }
            /* Reduz o espaçamento geral do cabeçalho em mobile */
            .fc .fc-toolbar {
                flex-wrap: wrap;
                gap: 5px;
            }
            
        }

        /* CORREÇÃO: Remove a capitalização global de palavras (que causava "De") */
        .fc .fc-toolbar-title {
            text-transform: none; /* Permite que o texto venha em minúsculo */
        }
        /* NOVA REGRA: Força a capitalização APENAS da primeira letra (o Mês) */
        .fc .fc-toolbar-title::first-letter {
            text-transform: uppercase;
        }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                {{-- Div onde o FullCalendar será renderizado --}}
                <div id='calendar'></div>
            </div>
        </div>
    </div>

    {{-- O Modal de Detalhes da Reserva (Responsivo) --}}
    <div id="detailsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        {{-- Ajusta a largura máxima para mobile (mais estreita) e desktop (mais larga) --}}
        <div id="modalContent" class="bg-white rounded-lg shadow-2xl p-6 w-11/12 max-w-sm sm:max-w-lg transform scale-95 opacity-0 transition-all duration-300">
            <h3 class="text-xl font-bold mb-4 text-[#0C2E5C]" id="modalTitle">Detalhes do Roteiro</h3>
            
            <div id="modalBody" class="space-y-2 text-gray-700">
                </div>

            {{-- Botões: Flex-col em mobile, Flex-row e Justify-end em desktop --}}
            <div class="flex flex-col sm:flex-row sm:justify-end gap-3 mt-6">
                <a id="viewRoteiroPdfBtn" href="#" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md font-semibold text-sm hover:bg-green-700 transition">
                    {{-- Ícone: w-3 h-3 em mobile, w-4 h-4 em desktop (sm:w-4 sm:h-4) --}}
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Ver Roteiro (PDF)
                </a>
                
<!--                 <a id="editRoteiroBtn" href="#" class="inline-flex items-center justify-center px-4 py-2 bg-yellow-500 text-white rounded-md font-semibold text-sm hover:bg-yellow-600 transition">
                    {{-- Ícone: w-3 h-3 em mobile, w-4 h-4 em desktop (sm:w-4 sm:h-4) --}}
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar Roteiro
                </a> -->

                <button id="closeModalBtn" class="px-4 py-2 bg-gray-500 text-white rounded-md font-semibold text-sm hover:bg-gray-600 transition">
                    Fechar
                </button>
            </div>
        </div>
    </div>
    
    {{-- Scripts do FullCalendar --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        // Função para gerar uma cor hexadecimal aleatória
        function getRandomColor() {
            // Gera um número hexadecimal de 6 dígitos
            return '#' + Math.floor(Math.random()*16777215).toString(16).padStart(6, '0');
        }

        // Função para determinar a cor do texto (preto ou branco) com base no fundo
        function getContrastColor(hexcolor) {
            // Se o # ainda estiver na frente, remove.
            if (hexcolor.startsWith('#')) {
                hexcolor = hexcolor.substring(1);
            }
            
            // Converte as componentes R, G, B de hexadecimal para decimal
            const r = parseInt(hexcolor.substring(0, 2), 16);
            const g = parseInt(hexcolor.substring(2, 4), 16);
            const b = parseInt(hexcolor.substring(4, 6), 16);
            
            // Calcula a Luminosidade (usando a fórmula de Perceived Brightness)
            // A taxa de 255 é para normalizar o valor (0 a 255)
            const brightness = (r * 299 + g * 587 + b * 114) / 1000;

            // Define o limite de contraste. Geralmente 128 é o ponto médio (metade de 255).
            // Se o brilho for alto, usamos texto preto; se for baixo, usamos texto branco.
            return brightness > 140 ? '#000000' : '#FFFFFF';
        }

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var modal = document.getElementById('detailsModal');
            var modalContent = document.getElementById('modalContent');
            
            function closeModal() {
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 300);
            }

            // Detecta se é mobile para ajustar a visualização do FullCalendar
            var isMobile = window.innerWidth < 768;

            var calendar = new FullCalendar.Calendar(calendarEl, {
                // Vista inicial adaptável (listWeek para mobile, month para desktop)
                initialView: isMobile ? 'listWeek' : 'dayGridMonth',
                locale: 'pt-br',
                
                // CORREÇÃO: Define altura fixa (700px) para desktop para garantir visualização completa
                height: isMobile ? 'auto' : 700, 
                
                // NOVA OPÇÃO: Tradução do "All Day" para "Dia Inteiro" ou similar
                allDayText: 'Dia Inteiro',

                // TRADUÇÃO DOS BOTÕES (CUSTOM BUTTON TEXT)
                buttonText: {
                    today: 'Hoje',
                    month: 'Mês',
                    week: 'Semana',
                    day: 'Dia',
                    list: 'Lista' // Adicionado para a view de lista em mobile
                },

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    // AGORA MOSTRA TODAS AS VIEWS (Mês, Semana, Dia) em TODAS as telas
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                
                // Aponta para a Rota API recém-criada
                events: '{{ route("api.roteiros.eventos") }}', 
                
                eventClick: function(info) {
                    const roteiro = info.event.extendedProps;
                    const roteiroId = info.event.id;
                    
                    // Formata a data e o horário
                    const dataFormatada = info.event.start.toLocaleDateString('pt-BR');
                    
                    let horarioDisplay = info.event.start.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
                    if(info.event.end) {
                        horarioDisplay += ' - ' + info.event.end.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
                    } else if (info.event.allDay) {
                        horarioDisplay = 'Dia Todo';
                    } else {
                        // Se for um evento pontual com hora (sem hora final)
                        horarioDisplay = info.event.start.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
                    }
                    
                    // Preencher o Modal
                    document.getElementById('modalTitle').textContent = `Roteiro: ${roteiro.curso} - ${roteiro.laboratorio}`;
                    
                    let htmlBody = `
                        <p><strong class="font-semibold">Unidade Curricular:</strong> ${roteiro.unidade_curricular || 'N/A'}</p>
                        <p><strong class="font-semibold">Tema:</strong> ${roteiro.tema || 'N/A'}</p>
                        <p><strong class="font-semibold">Professor:</strong> ${roteiro.professor || 'N/A'}</p>
                        <hr class="my-2 border-gray-200">
                        <p><strong class="font-semibold">Laboratório:</strong> ${roteiro.laboratorio || 'N/A'}</p>
                        <p><strong class="font-semibold">Data:</strong> ${dataFormatada}</p>
                        <p><strong class="font-semibold">Horário:</strong> ${horarioDisplay}</p>
                        <p><strong class="font-semibold">Alunos:</strong> ${roteiro.quantidade_alunos}</p>
                        <p><strong class="font-semibold">Carga Horária:</strong> ${roteiro.carga_horaria}</p>
                        <p><strong class="font-semibold">Período:</strong> ${roteiro.periodo}</p>
                    `;
                    document.getElementById('modalBody').innerHTML = htmlBody;

                    // Configurar os botões de Ação
                    const viewPdfBtn = document.getElementById('viewRoteiroPdfBtn');
                    //const editBtn = document.getElementById('editRoteiroBtn');

                    // Rotas
                    viewPdfBtn.href = `/roteiro-pratica/${roteiroId}`; 
                    //editBtn.href = `/roteiros/${roteiroId}/edit`; 

                    // Exibir o Modal com animação
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    setTimeout(() => {
                        modalContent.classList.remove('scale-95', 'opacity-0');
                        modalContent.classList.add('scale-100', 'opacity-100');
                    }, 10);
                },
                
                eventDidMount: function(info) {
                    const randomColor = getRandomColor();
                    const contrastColor = getContrastColor(randomColor);

                    // Aplica a cor aleatória ao fundo
                    info.el.style.backgroundColor = randomColor; 
                    // Aplica a cor de contraste ao texto
                    info.el.style.color = contrastColor; 
                    // Mantém a cor da borda um pouco mais escura ou sólida
                    info.el.style.borderColor = '#0C2E5C'; 
                }
            });

            calendar.render();

            // Eventos para fechar o modal
            document.getElementById('closeModalBtn').addEventListener('click', closeModal);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Re-renderiza o calendário ao mudar o tamanho da tela para ajustar a visualização
            window.addEventListener('resize', () => {
                var newIsMobile = window.innerWidth < 768;

                if (newIsMobile !== isMobile) {
                    isMobile = newIsMobile;
                    // Aplica a altura fixa/auto dependendo do tamanho da tela
                    calendar.setOption('height', isMobile ? 'auto' : 700);
                    calendar.setOption('initialView', isMobile ? 'listWeek' : 'dayGridMonth');
                    
                    // Não alteramos o headerToolbar, pois ele é estático agora.
                }
            });
        });
    </script>
</x-app-layout>
