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
            Detalhes do roteiro
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
            <div class="space-y-4">
                <!-- Exibindo os campos do Roteiro -->
                <div class="flex">
                    <strong class="w-1/3">Curso:</strong>
                    <span class="flex-1"><?php echo e($roteiro->curso); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Unidade curricular:</strong>
                    <span class="flex-1"><?php echo e($roteiro->unidade_curricular); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Professor:</strong>
                    <span class="flex-1"><?php echo e($roteiro->professor); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Período:</strong>
                    <span class="flex-1"><?php echo e($roteiro->periodo); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Quantidade de alunos:</strong>
                    <span class="flex-1"><?php echo e($roteiro->quantidade_alunos); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Data:</strong>
                    <span class="flex-1"><?php echo e(\Carbon\Carbon::parse($roteiro->data)->format('d/m/Y')); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Horário de início:</strong>
                    <span class="flex-1"><?php echo e($roteiro->horario_inicio); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Horário de fim:</strong>
                    <span class="flex-1"><?php echo e($roteiro->horario_fim); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Carga horária:</strong>
                    <span class="flex-1"><?php echo e($roteiro->carga_horaria); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Número de práticas:</strong>
                    <span class="flex-1"><?php echo e($roteiro->numero_praticas); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Laboratório:</strong>
                    <span class="flex-1"><?php echo e($roteiro->laboratorio); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Conteúdo:</strong>
                    <span class="flex-1"><?php echo e($roteiro->conteudo); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Tema:</strong>
                    <span class="flex-1"><?php echo e($roteiro->tema); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Objetivos:</strong>
                    <span class="flex-1"><?php echo e($roteiro->objetivos); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Metodologia:</strong>
                    <span class="flex-1"><?php echo e($roteiro->metodologia); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Recursos:</strong>
                    <span class="flex-1"><?php echo e($roteiro->recursos); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Avaliação:</strong>
                    <span class="flex-1"><?php echo e($roteiro->avaliacao); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Observações:</strong>
                    <span class="flex-1"><?php echo e($roteiro->observacoes); ?></span>
                </div>

                <div class="flex">
                    <strong class="w-1/3">Data de assinatura:</strong>
                    <span class="flex-1"><?php echo e(\Carbon\Carbon::parse($roteiro->data_assinatura)->format('d/m/Y')); ?></span>
                </div>
            </div>

            <!-- Botões de ação: Editar e Excluir -->
            <div class="flex justify-end mt-6 space-x-4">
                <?php if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_PROFESSOR])): ?>

                    <a href="<?php echo e(route('roteiros.index')); ?>" class="text-gray-600 font-medium px-4 py-2 border border-gray-600 rounded-md">
                        Voltar
                    </a>
                    <a href="<?php echo e(route('roteiros.edit', $roteiro->id)); ?>" class="text-[#0C2E5C] font-medium px-4 py-2 border border-[#0C2E5C] rounded-md">
                        Editar
                    </a>
                    <form action="<?php echo e(route('roteiros.destroy', $roteiro->id)); ?>" method="POST" class="inline-block">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" onclick="return confirm('Deseja excluir esse roteiro?')" class="cursor-pointer text-red-600 font-medium px-4 py-2 border border-red-600 rounded-md">
                            Excluir
                        </button>
                    </form>
                    <?php else: ?>
                    <a href="<?php echo e(route('roteiros.index')); ?>" class="text-gray-600 font-medium px-4 py-2 border border-gray-600 rounded-md">
                        Voltar
                    </a>
                <?php endif; ?>
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
<?php /**PATH C:\laragon\www\ilab\resources\views/roteiros/show.blade.php ENDPATH**/ ?>