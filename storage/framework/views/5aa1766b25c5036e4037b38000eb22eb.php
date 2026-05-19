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
            Gerenciamento de roteiros
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <?php if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_PROFESSOR])): ?>
                <a href="<?php echo e(route('roteiros.create')); ?>" class="fixed bottom-6 right-6 bg-[#0C2E5C] text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-blue-800 transition duration-200">
                    + Novo roteiro
                </a>
            <?php endif; ?>

            <div class="p-3 overflow-x-auto">
                <div class="w-full">
                    <!-- Controles: busca -->
                    <div class="mb-4" x-data="{
                        query: '',
                        matches(el){
                            const q = this.query.toLowerCase().trim();
                            if(q === '') return true;
                            const qDigits = q.replace(/\D/g, '');
                            const curso = el.dataset.curso || '';
                            const professor = el.dataset.professor || '';
                            const unidade = el.dataset.unidade || '';
                            const periodo = (el.dataset.periodo || '').toString();
                            if(curso.includes(q) || professor.includes(q) || unidade.includes(q) || periodo.includes(q)) return true;
                            if(qDigits !== '' && periodo.replace(/\D/g, '').includes(qDigits)) return true;
                            return false;
                        }
                    }">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <input x-model="query" type="text" placeholder="Buscar por curso, professor ou unidade..." class="border rounded px-3 py-2 text-sm w-full md:w-80" />
                                <button type="button" @click="query=''" class="px-3 py-2 bg-gray-100 rounded text-sm">Limpar</button>
                            </div>
                        </div>

                        <?php if($errors->has('assinatura')): ?>
                            <div class="p-4 bg-red-100 text-red-800 rounded mb-4">
                                <?php echo e($errors->first('assinatura')); ?>

                            </div>
                        <?php endif; ?>

                        <!-- Cabeçalho (desktop) -->
                        <div class="hidden md:grid grid-cols-7 space-x-2 flex bg-[#0C2E5C] text-white px-4 py-2 rounded-t-md text-sm font-semibold mt-3">
                            <div class="text-left">Curso</div>
                            <div class="text-center">Unidade curricular</div>
                            <div class="text-center">Professor</div>
                            <div class="text-center">Período</div>
                            <div class="text-center">Qtd. Alunos</div>
                            <div class="text-center">Data</div>
                            <div class="text-center">Ações</div>
                        </div>

                        <!-- Lista -->
                        <div class="space-y-3 mt-2">
                            <?php $__empty_1 = true; $__currentLoopData = $roteiros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roteiro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $curso = strtolower($roteiro->curso);
                                    $unidade = strtolower($roteiro->unidade_curricular);
                                    $professor = strtolower($roteiro->professor);
                                    $periodo = strtolower($roteiro->periodo);
                                ?>
                                    <!-- Desktop: linha -->
                                <div data-curso="<?php echo e($curso); ?>" data-professor="<?php echo e($professor); ?>" data-unidade="<?php echo e($unidade); ?>" data-periodo="<?php echo e($periodo); ?>" x-show="matches($el)" class="hidden md:grid grid-cols-7 space-x-2 items-center bg-white px-4 py-2 rounded shadow border border-gray-200 hover:shadow-md transition">

                                    <div class="text-sm text-gray-700 text-left"><?php echo e($roteiro->curso); ?></div>
                                    <div class="text-sm text-gray-700 text-center"><?php echo e($roteiro->unidade_curricular); ?></div>
                                    <div class="text-sm text-gray-700 text-center"><?php echo e($roteiro->professor); ?></div>
                                    <div class="text-sm text-gray-700 text-center"><?php echo e($roteiro->periodo); ?></div>
                                    <div class="text-sm text-gray-700 text-center"><?php echo e($roteiro->quantidade_alunos); ?></div>
                                    <div class="text-sm text-gray-700 text-center"><?php echo e(\Carbon\Carbon::parse($roteiro->data)->format('d/m/Y')); ?></div>
                                    <div class="text-center flex justify-center gap-2">
                                        <?php if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_PROFESSOR, \App\Models\User::NIVEL_COORDENADOR_LABORATORIO])): ?>
                                            <a href="<?php echo e(route('roteiros.show', $roteiro->id)); ?>" class="text-sm text-yellow-600 font-medium" title="Visualizar Roteiro">
                                                <?php if (isset($component)) { $__componentOriginalb5e85d280b48bb7948b8219d5dd98762 = $component; } ?>
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
<?php endif; ?>
                                            </a>
                                            <a href="<?php echo e(route('roteiros.edit', $roteiro->id)); ?>" class="" title="Editar Roteiro">
                                                <?php if (isset($component)) { $__componentOriginalb5e85d280b48bb7948b8219d5dd98762 = $component; } ?>
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
<?php endif; ?>
                                            </a>
                                            <form action="<?php echo e(route('roteiros.destroy', $roteiro->id)); ?>" method="POST" class="inline-block">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button onclick="return confirm('Deseja excluir?')" type="submit" title="Excluir roteiro">
                                                    <?php if (isset($component)) { $__componentOriginalb5e85d280b48bb7948b8219d5dd98762 = $component; } ?>
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
<?php endif; ?>
                                                </button>
                                            </form>
                                            <a href="<?php echo e(route('roteiros.pdf', $roteiro->id)); ?>" class="text-sm text-blue-600 font-medium" target="_blank" title="Criar PDF">
                                                <?php if (isset($component)) { $__componentOriginalb5e85d280b48bb7948b8219d5dd98762 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb5e85d280b48bb7948b8219d5dd98762 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons-logo','data' => ['name' => 'document']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icons-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'document']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb5e85d280b48bb7948b8219d5dd98762)): ?>
<?php $attributes = $__attributesOriginalb5e85d280b48bb7948b8219d5dd98762; ?>
<?php unset($__attributesOriginalb5e85d280b48bb7948b8219d5dd98762); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb5e85d280b48bb7948b8219d5dd98762)): ?>
<?php $component = $__componentOriginalb5e85d280b48bb7948b8219d5dd98762; ?>
<?php unset($__componentOriginalb5e85d280b48bb7948b8219d5dd98762); ?>
<?php endif; ?>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo e(route('roteiros.show', $roteiro->id)); ?>" class="text-sm text-yellow-600 font-medium" title="Visualizar Roteiro">
                                                <?php if (isset($component)) { $__componentOriginalb5e85d280b48bb7948b8219d5dd98762 = $component; } ?>
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
<?php endif; ?>
                                            </a>
                                            <a href="<?php echo e(route('roteiros.pdf', $roteiro->id)); ?>" class="text-sm text-blue-600 font-medium" target="_blank" title="Criar PDF">
                                                <?php if (isset($component)) { $__componentOriginalb5e85d280b48bb7948b8219d5dd98762 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb5e85d280b48bb7948b8219d5dd98762 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons-logo','data' => ['name' => 'document']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icons-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'document']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb5e85d280b48bb7948b8219d5dd98762)): ?>
<?php $attributes = $__attributesOriginalb5e85d280b48bb7948b8219d5dd98762; ?>
<?php unset($__attributesOriginalb5e85d280b48bb7948b8219d5dd98762); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb5e85d280b48bb7948b8219d5dd98762)): ?>
<?php $component = $__componentOriginalb5e85d280b48bb7948b8219d5dd98762; ?>
<?php unset($__componentOriginalb5e85d280b48bb7948b8219d5dd98762); ?>
<?php endif; ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Mobile: card -->
                                <div data-curso="<?php echo e($curso); ?>" data-professor="<?php echo e($professor); ?>" data-unidade="<?php echo e($unidade); ?>" data-periodo="<?php echo e($periodo); ?>" x-show="matches($el)" class="md:hidden bg-white px-4 py-3 rounded shadow border border-gray-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="text-xs text-gray-500">Curso</div>
                                            <div class="text-sm text-gray-700 font-medium"><?php echo e($roteiro->curso); ?></div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500">Data</div>
                                            <div class="text-sm text-gray-700"><?php echo e(\Carbon\Carbon::parse($roteiro->data)->format('d/m/Y')); ?></div>
                                        </div>
                                    </div>

                                    <div class="mt-2 grid grid-cols-2 gap-2 text-sm text-gray-700">
                                        <div>
                                            <div class="text-xs text-gray-500">Professor</div>
                                            <div><?php echo e($roteiro->professor); ?></div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Período</div>
                                            <div><?php echo e($roteiro->periodo); ?></div>
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <div class="text-xs text-gray-500">Quantidade de alunos</div>
                                        <div class="text-sm text-gray-700"><?php echo e($roteiro->quantidade_alunos); ?></div>
                                    </div>

                                    <div class="mt-3 flex items-center gap-2">
                                        <?php if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_PROFESSOR, \App\Models\User::NIVEL_COORDENADOR_LABORATORIO])): ?>
                                        <!-- Botão para Gerar PDF -->
                                        <a href="<?php echo e(route('roteiros.pdf', $roteiro->id)); ?>" class="text-sm text-blue-600 font-medium" target="_blank" title="Criar PDF">Criar PDF</a>
                                        <a href="<?php echo e(route('roteiros.edit', $roteiro->id)); ?>" class="text-sm text-[#0C2E5C] font-medium" title="Editar roteiro">Editar</a>
                                        <form action="<?php echo e(route('roteiros.destroy', $roteiro->id)); ?>" method="POST" class="inline-block">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button onclick="return confirm('Deseja excluir?')" type="submit" class="cursor-pointer text-sm text-red-600 font-medium" title="Excluir roteiro">Excluir</button>
                                        </form>
                                        <!-- Ícone de Visualizar (Olho) -->
                                        <a href="<?php echo e(route('roteiros.show', $roteiro->id)); ?>" class="text-sm text-yellow-600 font-medium size-4" title="Visualizar roteiro">
                                            <?php if (isset($component)) { $__componentOriginalb5e85d280b48bb7948b8219d5dd98762 = $component; } ?>
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
<?php endif; ?>
                                        </a>
                                        <?php else: ?>
                                        <a href="<?php echo e(route('roteiros.pdf', $roteiro->id)); ?>" class="text-sm text-blue-600 font-medium" target="_blank" title="Criar PDF">Criar PDF</a>
                                        <!-- Ícone de Visualizar (Olho) -->
                                        <a href="<?php echo e(route('roteiros.show', $roteiro->id)); ?>" class="text-sm text-yellow-600 font-medium size-4" title="Visualizar roteiro">
                                            <?php if (isset($component)) { $__componentOriginalb5e85d280b48bb7948b8219d5dd98762 = $component; } ?>
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
<?php endif; ?>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center text-gray-500 py-4">
                                    Nenhum roteiro encontrado.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
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
<?php endif; ?>
<?php /**PATH C:\laragon\www\ilab\resources\views/roteiros/index.blade.php ENDPATH**/ ?>