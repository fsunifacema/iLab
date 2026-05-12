<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Ilab') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])        
        <style>
            /* O CSS básico para resetar a aparência do navegador */
            input[type="checkbox"] {
                height: 1.25rem;
                width: 1.25rem;
                border-radius: 0.25rem !important;
                border-width: 1px !important;
                border-color: #d1d5db !important;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                background-color: #fff; /* Fundo branco quando desmarcado */
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* Sombra suave */
            }

            /* Estiliza o checkbox quando CHECKED (Cor de fundo + Símbolo de V) */
            input[type="checkbox"].text-indigo-600:checked {
                /* Fundo azul indigo */
                background-color: #4f46e5 !important;
                border-color: #4f46e5 !important;

                /* 🚨 NOVO: INJETA O SÍMBOLO DE 'V' (CHECKMARK) USANDO SVG */
                background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e") !important;
                background-size: 100% 100%;
                background-position: center;
                background-repeat: no-repeat;
            }

            input[type="checkbox"]:focus {
                outline: none !important;
            }
        </style>


        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts

        @stack('scripts')
    </body>
</html>
