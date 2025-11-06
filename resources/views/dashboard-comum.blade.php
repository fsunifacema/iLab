<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bem-vindo ao Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Olá, {{ auth()->user()->name }}!</h3>
                <p class="text-gray-700">Seu acesso está limitado. Aguarde autorização para visualizar outras funcionalidades.</p>
            </div>
        </div>
    </div>
</x-app-layout>
