<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['value' => '', 'label' => '', 'name' => '', 'id' => '']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['value' => '', 'label' => '', 'name' => '', 'id' => '']); ?>
<?php foreach (array_filter((['value' => '', 'label' => '', 'name' => '', 'id' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="mb-4">
    <label for="<?php echo e($id); ?>" class="block text-sm font-medium text-gray-700"><?php echo e($label); ?></label>
    <textarea id="<?php echo e($id); ?>" name="<?php echo e($name); ?>" <?php echo e($attributes->merge(['class' => 'w-full p-2 border rounded-md'])); ?>><?php echo e($value); ?></textarea>
</div>
<?php /**PATH C:\laragon\www\ilab\resources\views/components/textarea.blade.php ENDPATH**/ ?>