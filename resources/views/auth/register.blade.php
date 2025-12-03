<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>
        
        <h1 class="text-center justify-center text-[#0C2E5C] text-3xl font-bold border-">iLab</h1>
        <h2 class="text-center justify-center text-xl text-[#0C2E5C] mt-2 mb-4">Sistema de gerenciamento de laboratórios!</h2>
        <div class="mt-6 text-center">
            <p class="text-gray-700">Registro público desativado. Para criar uma conta, entre em contato com um administrador do sistema ou peça que um coordenador crie sua conta internamente.</p>
            <p class="mt-4"><a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900">Voltar para Login</a></p>
        </div>
    </x-authentication-card>
</x-guest-layout>
