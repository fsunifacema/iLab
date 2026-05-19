<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Calendário de práticas')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    
    <?php $__env->startPush('styles'); ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.20/main.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.20/main.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.20/main.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.20/main.min.css" />
    <?php $__env->stopPush(); ?>

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
            
            <?php if(!auth()->user()->signature_path && auth()->user()->nivel !== \App\Models\User::NIVEL_ADMINISTRADOR): ?>
                <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <!-- Ícone de Alerta -->
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Ação Necessária:</strong> Você ainda não possui uma assinatura digital cadastrada. 
                                <a href="<?php echo e(route('profile.show')); ?>" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                                    Clique aqui para cadastrar sua assinatura agora
                                </a>,
                                pois ela é obrigatória para a geração dos roteiros em PDF.
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div id='calendar'></div>
                </div>

                
                <div class="lg:col-span-1 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-[#0C2E5C] border-b pb-2">Feed de atividades</h3>
                    </div>

                    
                    <div id="notification-list" class="space-y-3 max-h-[700px] overflow-y-auto pr-2">

                        
                        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                // Define as classes de cor com base no 'status' que você definiu no Controller
                                $status_classes = match ($notification['status']) {
                                    'approved' => 'border-green-500 bg-green-50 hover:bg-green-100',
                                    'review' => 'border-yellow-500 bg-yellow-50 hover:bg-yellow-100',
                                    'rejected' => 'border-red-500 bg-red-50 hover:bg-red-100',
                                    default => 'border-blue-500 bg-blue-50 hover:bg-blue-100', // Status 'pending' ou desconhecido
                                };
                            ?>

                            
                            <a href="<?php echo e($notification['link'] ?? '#'); ?>"
                               class="block p-3 border-l-4 <?php echo e($status_classes); ?> rounded-r-md transition duration-150 cursor-pointer">

                                <p class="text-sm font-semibold text-gray-900"><?php echo e($notification['title']); ?></p>
                                <p class="text-xs text-gray-700 mt-0.5"><?php echo e($notification['message']); ?></p>

                                
                                <p class="text-xs text-gray-500 mt-1 italic flex justify-between">
                                    <span><?php echo e($notification['time_ago']); ?></span>
                                    <span class="font-normal text-gray-600"><?php echo e($notification['time_exact']); ?></span>
                                </p>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center p-4 text-gray-500 bg-gray-50 rounded-md">
                                <p class="font-semibold">Nenhuma notificação nos últimos 7 dias.</p>
                                <p class="text-sm mt-1">No momento não há atividades recentes que se apliquem ao seu perfil.</p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

            </div>
        </div>
    </div>

    
    <div id="detailsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        
        <div id="modalContent" class="bg-white rounded-lg shadow-2xl p-6 w-11/12 max-w-sm sm:max-w-lg transform scale-95 opacity-0 transition-all duration-300">
            <h3 class="text-xl font-bold mb-4 text-[#0C2E5C]" id="modalTitle">Detalhes do roteiro</h3>

            <div id="modalBody" class="space-y-2 text-gray-700">
                </div>

            
            <div class="flex flex-col sm:flex-row sm:justify-end gap-3 mt-6">
                <a id="viewRoteiroPdfBtn" href="#" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md font-semibold text-sm hover:bg-green-700 transition">
                    
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Ver roteiro (PDF)
                </a>

                <button id="closeModalBtn" class="cursor-pointer px-4 py-2 bg-gray-500 text-white rounded-md font-semibold text-sm hover:bg-gray-600 transition">
                    Fechar
                </button>
            </div>
        </div>
    </div>

    
    
    <script>
        // Função para gerar uma cor constante (determinística) baseada em um texto (ex: nome da disciplina ou ID)
        function stringToColor(str) {
            let hash = 0;
            for (let i = 0; i < str.length; i++) {
                hash = str.charCodeAt(i) + ((hash << 5) - hash);
            }
            let color = '#';
            for (let i = 0; i < 3; i++) {
                // Traz os valores um pouco para o escuro/médio para dar contraste melhor
                let value = (hash >> (i * 8)) & 0xAA; 
                color += ('00' + value.toString(16)).substr(-2);
            }
            return color;
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

        function escapeHtml(text) {
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function renderTemaField(tema) {
            if (!tema) {
                return `<p><strong class="font-semibold">Tema:</strong> N/A</p>`;
            }

            const maxChars = 120;
            const escaped = escapeHtml(tema);
            if (tema.length <= maxChars) {
                return `<p><strong class="font-semibold">Tema:</strong> ${escaped}</p>`;
            }

            return `
                <div>
                    <p class="font-semibold">Tema:</p>
                    <div id="temaText" class="text-sm text-gray-700 whitespace-pre-line overflow-hidden max-h-20 transition-all duration-200 rounded border border-gray-200 bg-gray-50 p-3">${escaped}</div>
                    <button id="toggleTemaBtn" type="button" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm font-semibold mt-2">
                        Ver mais
                    </button>
                </div>`;
        }

        document.addEventListener('livewire:navigated', function() {
            var calendarEl = document.getElementById('calendar');
            if (!calendarEl) return; // Garante que só roda se o elemento existir
            
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
                plugins: [FullCalendar.dayGridPlugin, FullCalendar.timeGridPlugin, FullCalendar.listPlugin],
                // Vista inicial adaptável (listWeek para mobile, month para desktop)
                initialView: isMobile ? 'listWeek' : 'dayGridMonth',
                locale: FullCalendar.ptBrLocale,

                // Define altura fixa (700px) para desktop para garantir visualização completa
                height: isMobile ? 'auto' : 700,

                // Tradução de textos do FullCalendar
                allDayText: 'Dia inteiro',
                noEventsContent: 'Nenhum evento para exibir',

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

                // Define o formato base do horário para 2 dígitos sem ocultar os minutos (se preferir pode voltar o omitZeroMinute pra true)
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    omitZeroMinute: true, // Mantemos true para mostrar só "20" e não "20:00" e depois adicionarmos o "h" via JS
                    meridiem: false
                },

                // Aponta para a Rota API recém-criada
                events: '<?php echo e(route("api.roteiros.eventos")); ?>',

                eventClick: function(info) {
                    const roteiro = info.event.extendedProps;
                    const roteiroId = info.event.id;

                    // Formata a data e o horário
                    const dataFormatada = info.event.start.toLocaleDateString('pt-BR');

                    let horarioDisplay = info.event.start.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
                    if(info.event.end) {
                        horarioDisplay += ' - ' + info.event.end.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
                    } else if (info.event.allDay) {
                        horarioDisplay = 'Dia todo';
                    } else {
                        // Se for um evento pontual com hora (sem hora final)
                        horarioDisplay = info.event.start.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
                    }

                    // Preencher o Modal
                    document.getElementById('modalTitle').textContent = `Roteiro: ${roteiro.curso} - ${roteiro.laboratorio}`;

                    let htmlBody = `
                        <p><strong class="font-semibold">Unidade curricular:</strong> ${roteiro.unidade_curricular || 'N/A'}</p>
                        ${renderTemaField(roteiro.tema)}
                        <p><strong class="font-semibold">Professor:</strong> ${roteiro.professor || 'N/A'}</p>
                        <hr class="my-2 border-gray-200">
                        <p><strong class="font-semibold">Laboratório:</strong> ${roteiro.laboratorio || 'N/A'}</p>
                        <p><strong class="font-semibold">Data:</strong> ${dataFormatada}</p>
                        <p><strong class="font-semibold">Horário:</strong> ${horarioDisplay}</p>
                        <p><strong class="font-semibold">Alunos:</strong> ${roteiro.quantidade_alunos}</p>
                        <p><strong class="font-semibold">Carga horária:</strong> ${roteiro.carga_horaria}</p>
                        <p><strong class="font-semibold">Período:</strong> ${roteiro.periodo}</p>
                    `;
                    document.getElementById('modalBody').innerHTML = htmlBody;

                    const toggleBtn = document.getElementById('toggleTemaBtn');
                    if (toggleBtn) {
                        toggleBtn.addEventListener('click', function() {
                            const temaText = document.getElementById('temaText');
                            const isNowCollapsed = temaText.classList.contains('max-h-20');

                            if (isNowCollapsed) {
                                temaText.classList.remove('max-h-20', 'overflow-hidden');
                                temaText.classList.add('max-h-80', 'overflow-auto');
                                toggleBtn.textContent = 'Ver menos';
                            } else {
                                temaText.classList.remove('max-h-80', 'overflow-auto');
                                temaText.classList.add('max-h-20', 'overflow-hidden');
                                toggleBtn.textContent = 'Ver mais';
                            }
                        });
                    }

                    // Configurar os botões de ação
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
                    // Usa o nome da Unidade Curricular ou Título para gerar sempre a mesma cor
                    const eventTitle = info.event.title || info.event.id;
                    const consistentColor = stringToColor(eventTitle);
                    const contrastColor = getContrastColor(consistentColor);

                    // Aplica a cor consistente ao fundo
                    info.el.style.backgroundColor = consistentColor;
                    // Aplica a cor de contraste ao texto
                    info.el.style.color = contrastColor;
                    // Mantém a cor da borda igual à de fundo
                    info.el.style.borderColor = consistentColor;

                    // Formata a hora de forma limpa preservando o layout
                    let timeEl = info.el.querySelector('.fc-event-time');
                    if (timeEl && timeEl.textContent) {
                        let text = timeEl.textContent.trim();
                        if (/^\d+$/.test(text)) {
                            timeEl.textContent = text + 'h';
                        } else if (text.includes(':')) {
                            timeEl.textContent = text.replace(':', 'h');
                        }
                    }
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\ilab\resources\views/dashboard.blade.php ENDPATH**/ ?>