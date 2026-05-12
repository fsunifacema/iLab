<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>
        
        <h1 class="text-center justify-center text-[#0C2E5C] text-3xl font-bold border-">iLab</h1>

        <h2 class="text-center justify-center text-xl text-[#0C2E5C] mt-2 mb-4">Sistema de Gerenciamento de Laboratórios!</h2>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('Status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}"> 
            @csrf

            <div>
                <x-label class="text-[#0C2E5C]" for="email" value="{{ __('E-mail') }}" />
                <x-input id="email" class="block p-1 mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label class="text-[#0C2E5C]" for="password" value="{{ __('Senha') }}" />
                
                <div class="relative flex items-center">
                    <x-input id="password" class="block p-1 mt-1 w-full pr-10" type="password" name="password" required autocomplete="current-password" />
                    
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg id="eye-icon" class="h-5 w-5 text-gray-500 hover:text-[#0C2E5C] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path id="eye-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center text-[#0C2E5C]">
                    
                    <x-checkbox id="remember_me" name="remember" />
                    
                    <span class="ms-2 text-sm text-gray-700 -mt-px">{{ __('Lembrar-me') }}</span>
                    
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-700 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Esqueceu sua senha?') }}
                    </a>



                @endif


                <x-button class="bg-[#0C2E5C] ms-4">
                    {{ __('Entrar') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>

    <script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            // Opcional: Mudar o ícone para um olho cortado aqui
            eyeIcon.style.color = '#0C2E5C'; 
        } else {
            passwordInput.type = 'password';
            eyeIcon.style.color = '#6b7280'; // Volta para o cinza
        }
    }
    </script>
</x-guest-layout>