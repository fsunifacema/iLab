<nav x-data="{ open: false }" class="bg-[#0C2E5C] border-b border-[#0C2E5C]">
    
    {{-- 
        1. CONFIGURAÇÃO DO MENU
    --}}
    @php
        $user = Auth::user();
        $nivel = $user->nivel;
        
        // Definição dos níveis para facilitar a leitura
        $prof = \App\Models\User::NIVEL_PROFESSOR;
        $tec = \App\Models\User::NIVEL_TECNICO;
        $coordCurso = \App\Models\User::NIVEL_COORDENADOR_CURSO;
        $coordLab = \App\Models\User::NIVEL_COORDENADOR_LABORATORIO;
        $admin = \App\Models\User::NIVEL_ADMINISTRADOR;

        // Lista de todos os menus possíveis
        $menuItems = [
            [
                'label' => 'Inicio',
                'route' => 'dashboard',
                'show_for' => [$prof, $tec, $coordCurso, $coordLab] // Admin não vê esse
            ],
            [
                'label' => 'Laboratórios',
                'route' => 'laboratorios.index',
                'show_for' => [$tec, $coordCurso, $coordLab] // Professor não vê
            ],
            [
                'label' => 'Insumos',
                'route' => 'insumos.index',
                'show_for' => [$prof, $tec, $coordCurso, $coordLab]
            ],
            [
                'label' => 'Equipamentos',
                'route' => 'equipamentos.index',
                'show_for' => [$prof, $tec, $coordCurso, $coordLab]
            ],
            [
                'label' => 'Roteiros',
                'route' => 'roteiros.index',
                'show_for' => [$prof, $tec, $coordCurso, $coordLab]
            ],
            [
                'label' => 'Solicitações',
                'route' => 'solicitacoes.index',
                'show_for' => [$prof, $coordCurso, $coordLab]
            ],
            [
                'label' => 'Usuários',
                'route' => 'admin.users.index', 
                'show_for' => [$admin]
            ]
        ];
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 ">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ $nivel === $admin ? route('admin.users.index') : route('dashboard') }}">
                        <img src="{{ asset('img/logo-facema.png') }}" alt="Logo" class="h-11 w-auto">
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex ">
                    @foreach($menuItems as $item)
                        @if(in_array($nivel, $item['show_for']))
                            <x-nav-link href="{{ route($item['route']) }}" :active="request()->routeIs($item['route'])">
                                {{ __($item['label']) }}
                            </x-nav-link>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="cursor-pointer flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="cursor-pointer inline-flex items-center px-3 py-2 border text-sm leading-4 font-medium rounded-md text-white hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}
                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400">{{ __('Gerenciar conta') }}</div>
                            <x-dropdown-link href="{{ route('profile.show') }}">{{ __('Perfil') }}</x-dropdown-link>
                            <div class="border-t border-gray-200"></div>
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">{{ __('Sair') }}</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="cursor-pointer inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" @click="open = false" class="fixed inset-0 bg-black opacity-50 z-40 sm:hidden" style="display: none;"></div>

    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-x-full" x-transition:enter-end="opacity-100 transform translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-x-0" x-transition:leave-end="opacity-0 transform translate-x-full" 
        class="fixed top-0 right-0 h-screen w-64 bg-[#0C2E5C] shadow-xl z-50 sm:hidden overflow-y-auto"
        style="display: none;" 
    >
        <div class="flex justify-end p-4">
            <button @click="open = false" class="cursor-pointer text-white hover:text-gray-300">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>     
        
        <div class="pt-2 pb-3 space-y-1">
            @foreach($menuItems as $item)
                @if(in_array($nivel, $item['show_for']))
                    <x-responsive-nav-link href="{{ route($item['route']) }}" :active="request()->routeIs($item['route'])">
                        {{ __($item['label']) }}
                    </x-responsive-nav-link>
                @endif
            @endforeach
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif
                <div>
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                        {{ __('Sair') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>