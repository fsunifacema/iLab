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
        <h2 class="text-xl font-semibold text-[#0C2E5C]">
            Gerenciamento do Quadro de Práticas
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            <?php if(session('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo e(session('error')); ?></span>
                </div>
            <?php endif; ?>

            
            <?php if(Auth::user()->nivel == \App\Models\User::NIVEL_PROFESSOR): ?>
                <a href="<?php echo e(route('solicitacoes.create')); ?>" class="fixed bottom-6 right-6 bg-[#0C2E5C] text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-blue-800 transition duration-200 z-10">
                    + Nova solicitação
                </a>
            <?php endif; ?>

            <div class="p-3">
                
                <div class="mb-4" x-data="{ 
                    query: '',
                    matches(professorName, disciplinaName, solicitacaoJson) {
                        const q = this.query.toLowerCase().trim();
                        if(q === '') return true;
                        
                        const prof = professorName.toLowerCase();
                        const disc = disciplinaName.toLowerCase();
                        
                        // Busca por professor ou disciplina
                        if (prof.includes(q) || disc.includes(q)) {
                            return true;
                        }
                        
                        // Se tem solicitação, busca também por laboratório e status
                        if (solicitacaoJson) {
                            try {
                                const sol = JSON.parse(solicitacaoJson);
                                const laboratorio = (sol.laboratorio?.nome || '').toLowerCase();
                                const status = (sol.status_texto || '').toLowerCase();
                                return laboratorio.includes(q) || status.includes(q);
                            } catch (e) {
                                return false;
                            }
                        }
                        
                        return false;
                    }
                }">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
                        <div class="flex items-center gap-2 w-full md:w-auto">
                            <input x-model="query" type="text" placeholder="Buscar por professor..." class="border rounded px-3 py-2 text-sm flex-1 md:flex-none md:w-80" />
                            <button type="button" @click="query=''" class="px-3 py-2 bg-gray-100 rounded text-sm whitespace-nowrap">Limpar</button>
                        </div>
                    </div>

                    
                    <?php if(Auth::user()->nivel != \App\Models\User::NIVEL_PROFESSOR): ?>

                        
                        <?php $__empty_1 = true; $__currentLoopData = $solicitacoesPorProfessor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $professorId => $professorData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $professor = $professorData['professor'];
                                $disciplinas = $professorData['disciplinas'];
                                $totalSolicitacoes = $disciplinas->reduce(function($carry, $item) { 
                                    return $carry + $item['solicitacoes']->count(); 
                                }, 0);
                                $professorKey = 'prof_' . $professorId;
                            ?>

                            
                            <div class="mb-6 bg-white rounded-lg shadow border border-gray-200 overflow-hidden"
                                x-show="matches('<?php echo e($professor->name); ?>', '', null)">
                                <div class="bg-[#0C2E5C] text-white p-4 cursor-pointer hover:bg-blue-900 transition" 
                                    @click="$el.nextElementSibling.classList.toggle('hidden')">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" :class="{'rotate-90': !$el.nextElementSibling.classList.contains('hidden')}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                            <div>
                                                <h3 class="font-bold text-lg"><?php echo e($professor->name); ?></h3>
                                                <p class="text-xs text-gray-200"><?php echo e($totalSolicitacoes); ?> solicitação(ões)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="hidden">
                                    
                                    <?php $__currentLoopData = $disciplinas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $disciplinaKey => $disciplinaData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $solicitacoes = $disciplinaData['solicitacoes'];
                                            $nomeDisciplina = $disciplinaData['nome_disciplina'];
                                            $disciplinaKey = 'disc_' . $professorId . '_' . md5($disciplinaKey);
                                        ?>

                                        
                                        <div class="border-t"
                                            x-show="matches('', '<?php echo e($nomeDisciplina); ?>', null)">
                                            <div class="bg-gray-50 p-4 cursor-pointer hover:bg-gray-100 transition border-l-4 border-blue-400" 
                                                @click="$el.nextElementSibling.classList.toggle('hidden')">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition-transform text-gray-700" :class="{'rotate-90': !$el.nextElementSibling.classList.contains('hidden')}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                        <span class="font-semibold text-gray-800"><?php echo e($nomeDisciplina); ?></span>
                                                        <span class="text-xs bg-gray-300 text-gray-700 px-2 py-1 rounded"><?php echo e($solicitacoes->count()); ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <div class="hidden">
                                                <div class="p-4 space-y-3">
                                                    <?php $__currentLoopData = $solicitacoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $solicitacao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $requerAcao = (
                                                                (auth()->user()->nivel == \App\Models\User::NIVEL_COORDENADOR_CURSO && $solicitacao->status == \App\Models\Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO) ||
                                                                (auth()->user()->nivel == \App\Models\User::NIVEL_COORDENADOR_LABORATORIO && $solicitacao->status == \App\Models\Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB)
                                                            );
                                                            $dataDisplay = $solicitacao->created_at ? $solicitacao->created_at->format('d/m/Y') : 'N/A';
                                                        ?>

                                                        <div class="bg-white border rounded-lg p-3 <?php echo e($requerAcao ? 'border-l-4 border-yellow-500' : 'border-gray-200'); ?> hover:shadow-md transition"
                                                            x-show="matches('', '', <?php echo e($solicitacao->toJson()); ?>)">
                                                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                                                <div class="flex-1">
                                                                    <div class="flex items-center justify-start mb-1">
                                                                        <div class="text-sm mr-4 font-semibold text-gray-800">
                                                                            <?php echo e(\Carbon\Carbon::parse($solicitacao->horario_inicio)->format('H:i')); ?> - 
                                                                            <?php echo e(\Carbon\Carbon::parse($solicitacao->horario_fim)->format('H:i')); ?>

                                                                        </div>
                                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                                            <?php if($solicitacao->status === \App\Models\Solicitacao::STATUS_APROVADA): ?> bg-green-100 text-green-800 
                                                                            <?php elseif($solicitacao->status === \App\Models\Solicitacao::STATUS_RECUSADA): ?> bg-red-100 text-red-800
                                                                            <?php else: ?> bg-yellow-100 text-yellow-800 <?php endif; ?>">
                                                                            <?php echo e($solicitacao->status_texto); ?>

                                                                        </span>
                                                                    </div>
                                                                    <div class="text-sm text-gray-600">Lab: <?php echo e($solicitacao->laboratorio->nome ?? 'N/A'); ?></div>
                                                                    <div class="text-sm text-gray-600">Data solicitada: <?php echo e($solicitacao->data_solicitacao ? \Carbon\Carbon::parse($solicitacao->data_solicitacao)->format('d/m/Y') : 'N/A'); ?></div>
                                                                </div>
                                                                <div class="flex gap-2 items-center">
                                                                    <a href="<?php echo e(route('solicitacoes.show', $solicitacao->id)); ?>" class="text-[#0C2E5C] hover:text-blue-800 font-semibold text-sm">
                                                                        Ver detalhes
                                                                    </a>
                                                                    <?php
                                                                        $podeEditar = (
                                                                            (auth()->id() == $solicitacao->user_id && $solicitacao->status == \App\Models\Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO) ||
                                                                            (auth()->id() == $solicitacao->coordenador_curso_id && $solicitacao->status == \App\Models\Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO) ||
                                                                            (auth()->id() == $solicitacao->coordenador_lab_id && $solicitacao->status == \App\Models\Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB)
                                                                        );
                                                                    ?>
                                                                    <?php if($podeEditar): ?>
                                                                        <a href="<?php echo e(route('solicitacoes.edit', $solicitacao->id)); ?>" class="text-green-600 hover:text-green-800 font-semibold text-sm">
                                                                            Editar
                                                                        </a>
                                                                    <?php endif; ?>

                                                                    
                                                                    <?php if($requerAcao): ?>
                                                                        <?php if(auth()->user()->nivel == \App\Models\User::NIVEL_COORDENADOR_CURSO): ?>
                                                                            <form action="<?php echo e(route('solicitacoes.aprovar.coordenador.curso', $solicitacao->id)); ?>" method="POST" class="inline-block" onsubmit="return confirm('Aprovar e encaminhar para o coordenador do laboratório?');">
                                                                                <?php echo csrf_field(); ?>
                                                                                <?php echo method_field('POST'); ?>
                                                                                <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition">
                                                                                    Aprovar
                                                                                </button>
                                                                            </form>
                                                                        <?php elseif(auth()->user()->nivel == \App\Models\User::NIVEL_COORDENADOR_LABORATORIO): ?>
                                                                            <form action="<?php echo e(route('solicitacoes.aprovar.coordenador.lab', $solicitacao->id)); ?>" method="POST" class="inline-block" onsubmit="return confirm('Aprovar definitivamente esta solicitação?');">
                                                                                <?php echo csrf_field(); ?>
                                                                                <?php echo method_field('POST'); ?>
                                                                                <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition">
                                                                                    Aprovar
                                                                                </button>
                                                                            </form>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>

                                                                    <?php if(Auth::user()->nivel == \App\Models\User::NIVEL_PROFESSOR && $solicitacao->professor_id == Auth::id() && $solicitacao->status != \App\Models\Solicitacao::STATUS_APROVADA && $solicitacao->status != \App\Models\Solicitacao::STATUS_RECUSADA): ?>
                                                                        <form action="<?php echo e(route('solicitacoes.destroy', $solicitacao->id)); ?>" method="POST" class="inline-block" onsubmit="return confirm('Deseja realmente excluir esta solicitação?');">
                                                                            <?php echo csrf_field(); ?>
                                                                            <?php echo method_field('DELETE'); ?>
                                                                            <button type="submit" title="Excluir solicitação" class="text-red-600 hover:text-red-800 font-semibold text-sm">
                                                                                Excluir
                                                                            </button>
                                                                        </form>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center text-gray-500 py-12 bg-white rounded-lg shadow-md">
                                <p class="text-gray-500 text-lg">Nenhuma solicitação encontrada para revisar.</p>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        
                            <div class="hidden md:grid grid-cols-6 space-x-2 bg-[#0C2E5C] text-white px-4 py-2 rounded-t-md text-sm font-semibold mt-3">
                                <div class="text-left">Horário</div>
                                <div class="text-center">Laboratório</div>
                                <div class="text-center">Unidade curricular</div>
                                <div class="text-center">Data solicitada</div>
                                <div class="text-center">Status</div>
                                <div class="text-center">Ações</div>
                            </div>

                            <div class="space-y-3 mt-2">
                                <?php $__empty_1 = true; $__currentLoopData = $todasSolicitacoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $solicitacao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <!-- Desktop row -->
                                    <div class="hidden md:grid grid-cols-6 space-x-2 items-center bg-white px-4 py-2 rounded shadow border border-gray-200 hover:shadow-md transition">
                                        <div class="text-sm text-gray-700 text-left"><?php echo e(\Carbon\Carbon::parse($solicitacao->horario_inicio)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($solicitacao->horario_fim)->format('H:i')); ?></div>
                                        <div class="text-sm text-gray-700 text-center"><?php echo e($solicitacao->laboratorio->nome ?? 'N/A'); ?></div>
                                        <div class="text-sm text-gray-700 text-center"><?php echo e($solicitacao->unidade_curricular ?? $solicitacao->roteiro?->unidade_curricular ?? 'Sem unidade curricular'); ?></div>
                                        <div class="text-sm text-gray-700 text-center"><?php echo e($solicitacao->data_solicitacao ? \Carbon\Carbon::parse($solicitacao->data_solicitacao)->format('d/m/Y') : 'N/A'); ?></div>
                                        <div class="text-sm text-gray-700 text-center">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold 
                                                <?php if($solicitacao->status === \App\Models\Solicitacao::STATUS_APROVADA): ?> bg-green-100 text-green-800 
                                                <?php elseif($solicitacao->status === \App\Models\Solicitacao::STATUS_RECUSADA): ?> bg-red-100 text-red-800 
                                                <?php else: ?> bg-yellow-100 text-yellow-800 <?php endif; ?>">
                                                <?php echo e($solicitacao->status_texto); ?>

                                            </span>
                                        </div>
                                        <div class="text-center flex justify-center gap-2">
                                            <a href="<?php echo e(route('solicitacoes.show', $solicitacao->id)); ?>" class="text-blue-600 hover:text-blue-800 font-semibold text-sm" title="Visualizar solicitação"><?php if (isset($component)) { $__componentOriginalb5e85d280b48bb7948b8219d5dd98762 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb5e85d280b48bb7948b8219d5dd98762 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons-logo','data' => ['name' => 'show']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icons-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'show']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb5e85d280b48bb7948b8219d5dd98762)): ?>
<?php $attributes = $__attributesOriginalb5e85d280b48bb7948b8219d5dd98762; ?>
<?php unset($__attributesOriginalb5e85d280b48bb7948b8219d5dd98762); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb5e85d280b48bb7948b8219d5dd98762)): ?>
<?php $component = $__componentOriginalb5e85d280b48bb7948b8219d5dd98762; ?>
<?php unset($__componentOriginalb5e85d280b48bb7948b8219d5dd98762); ?>
<?php endif; ?></a>
                                            <?php
                                                $podeEditar = (
                                                    (auth()->id() == $solicitacao->user_id && $solicitacao->status == \App\Models\Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO) ||
                                                    (auth()->id() == $solicitacao->coordenador_curso_id && $solicitacao->status == \App\Models\Solicitacao::STATUS_AGUARDANDO_COORDENADOR_CURSO) ||
                                                    (auth()->id() == $solicitacao->coordenador_lab_id && $solicitacao->status == \App\Models\Solicitacao::STATUS_AGUARDANDO_COORDENADOR_LAB)
                                                );
                                            ?>
                                            <?php if($podeEditar): ?>
                                                <a href="<?php echo e(route('solicitacoes.edit', $solicitacao->id)); ?>" class="text-green-600 hover:text-green-800 font-semibold text-sm " title="Editar solicitação"><?php if (isset($component)) { $__componentOriginalb5e85d280b48bb7948b8219d5dd98762 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb5e85d280b48bb7948b8219d5dd98762 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons-logo','data' => ['name' => 'edit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icons-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'edit']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb5e85d280b48bb7948b8219d5dd98762)): ?>
<?php $attributes = $__attributesOriginalb5e85d280b48bb7948b8219d5dd98762; ?>
<?php unset($__attributesOriginalb5e85d280b48bb7948b8219d5dd98762); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb5e85d280b48bb7948b8219d5dd98762)): ?>
<?php $component = $__componentOriginalb5e85d280b48bb7948b8219d5dd98762; ?>
<?php unset($__componentOriginalb5e85d280b48bb7948b8219d5dd98762); ?>
<?php endif; ?></a>
                                            <?php endif; ?>
                                            <?php if($solicitacao->status != \App\Models\Solicitacao::STATUS_APROVADA && $solicitacao->status != \App\Models\Solicitacao::STATUS_RECUSADA): ?>
                                                <form action="<?php echo e(route('solicitacoes.destroy', $solicitacao->id)); ?>" method="POST" class="inline-block" onsubmit="return confirm('Deseja realmente excluir esta solicitação?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm" title="Excluir solicitação"><?php if (isset($component)) { $__componentOriginalb5e85d280b48bb7948b8219d5dd98762 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb5e85d280b48bb7948b8219d5dd98762 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons-logo','data' => ['name' => 'delete']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icons-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'delete']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb5e85d280b48bb7948b8219d5dd98762)): ?>
<?php $attributes = $__attributesOriginalb5e85d280b48bb7948b8219d5dd98762; ?>
<?php unset($__attributesOriginalb5e85d280b48bb7948b8219d5dd98762); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb5e85d280b48bb7948b8219d5dd98762)): ?>
<?php $component = $__componentOriginalb5e85d280b48bb7948b8219d5dd98762; ?>
<?php unset($__componentOriginalb5e85d280b48bb7948b8219d5dd98762); ?>
<?php endif; ?></button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Mobile card -->
                                    <div class="md:hidden bg-white px-4 py-3 rounded shadow border border-gray-200">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="text-xs text-gray-500">Horário</div>
                                                <div class="text-sm text-gray-700 font-medium"><?php echo e(\Carbon\Carbon::parse($solicitacao->horario_inicio)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($solicitacao->horario_fim)->format('H:i')); ?></div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-xs text-gray-500">Data solicitada</div>
                                                <div class="text-sm text-gray-700"><?php echo e($solicitacao->data_solicitacao ? \Carbon\Carbon::parse($solicitacao->data_solicitacao)->format('d/m/Y') : 'N/A'); ?></div>
                                            </div>
                                        </div>

                                        <div class="mt-2 grid grid-cols-2 gap-2 text-sm text-gray-700">
                                            <div>
                                                <div class="text-xs text-gray-500">Laboratório</div>
                                                <div><?php echo e($solicitacao->laboratorio->nome ?? 'N/A'); ?></div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500">Status</div>
                                                <div>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold 
                                                        <?php if($solicitacao->status === \App\Models\Solicitacao::STATUS_APROVADA): ?> bg-green-100 text-green-800 
                                                        <?php elseif($solicitacao->status === \App\Models\Solicitacao::STATUS_RECUSADA): ?> bg-red-100 text-red-800 
                                                        <?php else: ?> bg-yellow-100 text-yellow-800 <?php endif; ?>">
                                                        <?php echo e($solicitacao->status_texto); ?>

                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-2">
                                            <div class="text-xs text-gray-500">Unidade curricular</div>
                                            <div class="text-sm text-gray-700"><?php echo e($solicitacao->unidade_curricular ?? $solicitacao->roteiro?->unidade_curricular ?? 'Sem unidade curricular'); ?></div>
                                        </div>
                                        
                                        <div class="mt-3 flex items-center gap-2">
                                            <a href="<?php echo e(route('solicitacoes.show', $solicitacao->id)); ?>" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">Ver detalhes</a>
                                            <?php if($podeEditar): ?>
                                                <a href="<?php echo e(route('solicitacoes.edit', $solicitacao->id)); ?>" class="text-green-600 hover:text-green-800 font-semibold text-sm">Editar</a>
                                            <?php endif; ?>
                                            <?php if($solicitacao->status != \App\Models\Solicitacao::STATUS_APROVADA && $solicitacao->status != \App\Models\Solicitacao::STATUS_RECUSADA): ?>
                                                <form action="<?php echo e(route('solicitacoes.destroy', $solicitacao->id)); ?>" method="POST" class="inline-block" onsubmit="return confirm('Deseja realmente excluir esta solicitação?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm">Excluir</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="text-center text-gray-500 py-4">
                                        Nenhuma solicitação encontrada.
                                        <div class="mt-2">
                                            <a href="<?php echo e(route('solicitacoes.create')); ?>" class="text-blue-600 hover:text-blue-800 font-semibold">
                                                Faça sua primeira solicitação
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                    
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\ilab\resources\views/solicitacoes/index.blade.php ENDPATH**/ ?>