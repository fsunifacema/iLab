<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">
            Gerenciar Usuários
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4">
                @if(session('success'))
                    <div class="p-2 bg-green-50 border border-green-200 text-green-800 rounded mb-4">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="p-2 bg-red-50 border border-red-200 text-red-800 rounded mb-4">{{ session('error') }}</div>
                @endif
                @if(in_array(Auth::user()->nivel, [\App\Models\User::NIVEL_ADMINISTRADOR]))
                    <a href="{{ route('admin.users.create') }}" class="fixed bottom-6 right-6 bg-[#0C2E5C] text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-blue-800 transition duration-200">
                        + Novo Usuário
                    </a>
                @endif
                <div class="p-3 overflow-x-auto">
                    <div class="w-full">
                    <!-- Controles: busca -->
                        <div class="mb-4" x-data="{ 
                            query: '',
                            matches(el){
                                const q = this.query.toLowerCase().trim();
                                if(q === '') return true; 
                                const nome = (el.dataset.nome || '').toLowerCase();
                                const email = (el.dataset.email || '').toLowerCase();
                                const nivel = (el.dataset.nivel || '').toLowerCase();
                                if(nome.includes(q) || email.includes(q) || nivel.includes(q)) return true;
                                
                                return false;
                            }
                        }">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <input x-model="query" type="text" placeholder="Buscar por nome, email, nivel..." class="border rounded px-3 py-2 text-sm w-full md:w-80" />
                                <button type="button" @click="query=''" class="cursor-pointer px-3 py-2 bg-gray-100 rounded text-sm">Limpar</button>
                            </div>
                        </div>

                        @php $nivel = Auth::user()->nivel; @endphp
                        @php $desktopCols = 'grid-cols-4'; @endphp

                        @if($nivel != \App\Models\User::NIVEL_PROFESSOR)
                        <!-- Cabeçalho (desktop) -->
                        <div class="hidden md:grid {{ $desktopCols }} bg-[#0C2E5C] text-white px-4 py-2 rounded-t-md text-sm font-semibold mt-3">
                            <div>Nome</div>
                            <div>Email</div>
                            <div>Nível</div>
                            <div class="text-center">Ações</div>
                        </div>
                        @endif
                        <!-- Lista -->
                        <div class="space-y-3 mt-2">
                        @forelse($users as $user)
                            @php
                                $nome = strtolower($user->name);
                                $email = strtolower($user->email);
                                $nivelUser = strtolower((string) $user->nivel);
                            @endphp
                            <div 
                                x-show="matches($el)" 
                                data-nome="{{ $nome }}" 
                                data-email="{{ $email }}" 
                                data-nivel="{{ $nivelUser }}" 
                                class="hidden md:grid {{ $desktopCols }} items-center bg-white px-4 py-2 rounded shadow border border-gray-200 hover:shadow-md transition"
                            >
                                <div class="text-sm text-gray-700">{{ $user->name }}</div>
                                <div class="text-sm text-gray-700">{{ $user->email }}</div>
                                <div class="text-sm text-gray-700">{{ $user->nivel }}</div>     
                                @if($nivel = \App\Models\User::NIVEL_ADMINISTRADOR)
                                <div class="text-center flex justify-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="me-2">
                                        <x-icons-logo name="edit"/>
                                    </a>            
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Deseja excluir este usuário?')" type="submit">
                                            <div class="cursor-pointer relative group inline-block">
                                                <x-icons-logo name="delete"/>
                                            </div>
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-500">Nenhum usuário encontrado.</p>
                        @endforelse
                        </div>
                    </div>
                </div>  
                </div>
        </div>
    </div>
</x-app-layout>