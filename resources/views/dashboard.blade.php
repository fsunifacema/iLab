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
            {{-- NOVO CONTAINER DE LAYOUT: Grid para 1 coluna em mobile e 3 colunas em desktop --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- COLUNA 1: CALENDÁRIO (Ocupa 2/3 em telas grandes) --}}
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div id='calendar'></div>
                </div>

                {{-- COLUNA 2: PAINEL DE NOTIFICAÇÕES (Ocupa 1/3 em telas grandes) --}}
                <div class="lg:col-span-1 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4 text-[#0C2E5C] border-b pb-2">Feed de Atividades</h3>

                    {{-- O feed de atividades com rolagem limitada --}}
                    <div id="notification-list" class="space-y-3 max-h-[700px] overflow-y-auto pr-2">

                        {{-- Loop para exibir as notificações reais passadas pelo DashboardController --}}
                        @forelse ($notifications as $notification)
                            @php
                                // Define as classes de cor com base no 'status' que você definiu no Controller
                                $status_classes = match ($notification['status']) {
                                    'approved' => 'border-green-500 bg-green-50 hover:bg-green-100',
                                    'review' => 'border-yellow-500 bg-yellow-50 hover:bg-yellow-100',
                                    'rejected' => 'border-red-500 bg-red-50 hover:bg-red-100',
                                    default => 'border-blue-500 bg-blue-50 hover:bg-blue-100', // Status 'pending' ou desconhecido
                                };
                            @endphp

                            {{-- Cada notificação é um link <a> que usa a chave 'link' do array --}}
                            <a href="{{ $notification['link'] ?? '#' }}"
                               class="block p-3 border-l-4 {{ $status_classes }} rounded-r-md transition duration-150 cursor-pointer">

                                <p class="text-sm font-semibold text-gray-900">{{ $notification['title'] }}</p>
                                <p class="text-xs text-gray-700 mt-0.5">{{ $notification['message'] }}</p>

                                {{-- LINHA CORRIGIDA: Exibe o tempo relativo (à esquerda) e o tempo exato (à direita) --}}
                                <p class="text-xs text-gray-500 mt-1 italic flex justify-between">
                                    <span>{{ $notification['time_ago'] }}</span>
                                    <span class="font-normal text-gray-600">{{ $notification['time_exact'] }}</span>
                                </p>
                            </a>
                        @empty
                            <div class="text-center p-4 text-gray-500 bg-gray-50 rounded-md">
                                <p class="font-semibold">Nenhuma notificação nos últimos 7 dias.</p>
                                <p class="text-sm mt-1">No momento não há atividades recentes que se apliquem ao seu perfil.</p>
                            </div>
                        @endforelse

                    </div>
                </div>

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

                <button id="closeModalBtn" class="cursor-pointer px-4 py-2 bg-gray-500 text-white rounded-md font-semibold text-sm hover:bg-gray-600 transition">
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
            if (hexcolor.startsWith('#')) {
                hexcolor = hexcolor.substring(1);
            }

            // Converte as componentes R, G, B de hexadecimal para decimal
            const r = parseInt(hexcolor.substring(0, 2), 16);
            const g = parseInt(hexcolor.substring(2, 4), 16);
            const b = parseInt(hexcolor.substring(4, 6), 16);

            // Calcula a Luminosidade (usando a fórmula de Perceived Brightness)
            const brightness = (r * 299 + g * 587 + b * 114) / 1000;

            // Retorna a cor de contraste
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

                // Define altura fixa (700px) para desktop para garantir visualização completa
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

                    // Rotas
                    viewPdfBtn.href = `/roteiro-pratica/${roteiroId}`;

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

                }
            });
        });
    </script>
</x-app-layout>
