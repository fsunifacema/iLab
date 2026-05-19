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
            Gerenciamento de equipamentos
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <?php if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_TECNICO, \App\Models\User::NIVEL_COORDENADOR_CURSO, \App\Models\User::NIVEL_COORDENADOR_LABORATORIO])): ?>
                <a href="<?php echo e(route('equipamentos.create')); ?>" class="fixed bottom-6 right-6 bg-[#0C2E5C] text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-blue-800 transition duration-200 z-30">
                    + Novo equipamento
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
                            const nome = el.dataset.nome || '';
                            const marca = el.dataset.marca || '';
                            const modelo = el.dataset.modelo || '';
                            return nome.includes(q) || marca.includes(q) || modelo.includes(q);
                        }
                    }">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <input x-model="query" type="text" placeholder="Buscar por nome, marca ou modelo..." class="border rounded px-3 py-2 text-sm w-full md:w-80" />
                                <button type="button" @click="query=''" class="cursor-pointer px-3 py-2 bg-gray-100 rounded text-sm">Limpar</button>
                            </div> 
                        </div>
                        <!-- Cabeçalho (desktop) --> 
                        <?php $nivel = Auth::user()->nivel; ?>
                        <?php $desktopCols = $nivel != \App\Models\User::NIVEL_PROFESSOR ? 'grid-cols-6' : 'grid-cols-3'; ?>

                        <?php if($nivel != \App\Models\User::NIVEL_PROFESSOR): ?>
                            <div class="hidden md:grid bg-[#0C2E5C] text-white px-4 py-2 rounded-t-md text-sm font-semibold mt-3" style="grid-template-columns: 0.5fr 1fr 1fr 0.7fr 0.7fr 1fr;">
                        <?php else: ?>
                            <div class="hidden md:grid bg-[#0C2E5C] text-white px-4 py-2 rounded-t-md text-sm font-semibold mt-3" style="grid-template-columns: 0.5fr 1fr 1fr;">
                        <?php endif; ?>
                            <div class="text-left">Nome</div>
                            <div class="text-center">Marca</div>
                            <div class="text-center">Modelo</div>
                            <?php if($nivel != \App\Models\User::NIVEL_PROFESSOR): ?>
                                <div class="text-center">Calibração</div>
                                <div class="text-center">Manutenção</div>
                                <div class="text-center">Ações</div>
                            <?php endif; ?>
                        </div>

                        <!-- Lista -->
                        <div class="space-y-3 mt-2">
                            <?php $__empty_1 = true; $__currentLoopData = $equipamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $nome = strtolower($equipamento->nome);
                                    $marca = strtolower($equipamento->marca);
                                    $modelo = strtolower($equipamento->modelo);
                                ?>
                                <!-- Desktop: linha -->
                                <?php if($nivel != \App\Models\User::NIVEL_PROFESSOR): ?>
                                    <div data-nome="<?php echo e($nome); ?>" data-marca="<?php echo e($marca); ?>" data-modelo="<?php echo e($modelo); ?>" x-show="matches($el)" class="hidden md:grid items-center bg-white px-4 py-2 rounded shadow border border-gray-200 hover:shadow-md transition" style="grid-template-columns: 0.5fr 1fr 1fr 0.7fr 0.7fr 1fr;">
                                <?php else: ?>
                                    <div data-nome="<?php echo e($nome); ?>" data-marca="<?php echo e($marca); ?>" data-modelo="<?php echo e($modelo); ?>" x-show="matches($el)" class="hidden md:grid items-center bg-white px-4 py-2 rounded shadow border border-gray-200 hover:shadow-md transition" style="grid-template-columns: 0.5fr 1fr 1fr;">
                                <?php endif; ?>
                                    <div class="text-sm text-gray-700 text-left truncate"><?php echo e($equipamento->nome); ?></div>
                                    <div class="text-sm text-gray-700 text-center truncate"><?php echo e($equipamento->marca); ?></div>
                                    <div class="text-sm text-gray-700 text-center truncate"><?php echo e($equipamento->modelo); ?></div>

                                    <?php if($nivel != \App\Models\User::NIVEL_PROFESSOR): ?>
                                        <div class="text-center">
                                            <span class="px-2 py-1 rounded text-xs font-semibold <?php echo e($equipamento->calibracao ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-800'); ?>">
                                                <?php echo e($equipamento->calibracao ? 'Sim' : 'Não'); ?>

                                            </span>
                                        </div>
                                        <div class="text-center">
                                            <span class="px-2 py-1 rounded text-xs font-semibold <?php echo e($equipamento->manutencao ? 'bg-blue-200 text-blue-800' : 'bg-gray-200 text-gray-800'); ?>">
                                                <?php echo e($equipamento->manutencao ? 'Sim' : 'Não'); ?>

                                            </span>
                                        </div>
                                        <div class="text-center flex justify-center gap-2">
                                            <?php if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_TECNICO, \App\Models\User::NIVEL_COORDENADOR_CURSO, \App\Models\User::NIVEL_COORDENADOR_LABORATORIO])): ?>
                                                <a href="<?php echo e(route('equipamentos.edit', $equipamento->id)); ?>" class="me-2">
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

                                                <form action="<?php echo e(route('equipamentos.destroy', $equipamento->id)); ?>" method="POST" class="inline-block">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button onclick="return confirm('Deseja excluir?')" type="submit">
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
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Mobile: cartão -->
                                <div data-nome="<?php echo e($nome); ?>" data-marca="<?php echo e($marca); ?>" data-modelo="<?php echo e($modelo); ?>" x-show="matches($el)" class="md:hidden bg-white px-4 py-3 rounded shadow border border-gray-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="text-xs text-gray-500">Nome</div>
                                            <div class="text-sm text-gray-700 font-medium"><?php echo e($equipamento->nome); ?></div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500">Modelo</div>
                                            <div class="text-sm text-gray-700"><?php echo e($equipamento->modelo); ?></div>
                                        </div>
                                    </div>
                                    <div class="mt-2 grid grid-cols-2 gap-2 text-sm text-gray-700">
                                        <div>
                                            <div class="text-xs text-gray-500">Marca</div>
                                            <div><?php echo e($equipamento->marca); ?></div>
                                        </div>
                                        <?php if($nivel != \App\Models\User::NIVEL_PROFESSOR): ?>
                                            <div>
                                                <div class="text-xs text-gray-500">Calibração</div>
                                                <span class="px-2 py-1 rounded text-xs font-semibold <?php echo e($equipamento->calibracao ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-800'); ?>">
                                                    <?php echo e($equipamento->calibracao ? 'Sim' : 'Não'); ?>

                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if($nivel != \App\Models\User::NIVEL_PROFESSOR): ?>
                                        <div class="mt-2 grid grid-cols-1 gap-2 text-sm text-gray-700">
                                            <div>
                                                <div class="text-xs text-gray-500">Manutenção</div>
                                                <span class="px-2 py-1 rounded text-xs font-semibold <?php echo e($equipamento->manutencao ? 'bg-blue-200 text-blue-800' : 'bg-gray-200 text-gray-800'); ?>">
                                                    <?php echo e($equipamento->manutencao ? 'Sim' : 'Não'); ?>

                                                </span>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_TECNICO, \App\Models\User::NIVEL_COORDENADOR_CURSO, \App\Models\User::NIVEL_COORDENADOR_LABORATORIO])): ?>
                                        <div class="mt-3 flex items-center gap-3">
                                            <a href="<?php echo e(route('equipamentos.edit', $equipamento->id)); ?>" class="text-sm text-[#0C2E5C] font-medium">Editar</a>
                                            <form action="<?php echo e(route('equipamentos.destroy', $equipamento->id)); ?>" method="POST" class="inline-block">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button onclick="return confirm('Deseja excluir?')" type="submit" class="cursor-pointer text-sm text-red-600 font-medium">Excluir</button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center text-gray-500 py-4">
                                    Nenhum equipamento encontrado.
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
<?php endif; ?><?php /**PATH C:\laragon\www\ilab\resources\views/equipamento/index.blade.php ENDPATH**/ ?>