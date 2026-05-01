@props(['value' => '', 'label' => '', 'name' => '', 'id' => ''])

<div class="mb-4">
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <textarea id="{{ $id }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'w-full p-2 border rounded-md']) }}>{{ $value }}</textarea>
</div>
