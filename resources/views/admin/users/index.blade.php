<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">
            Gerenciar Usuários
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="p-2 bg-green-50 border border-green-200 text-green-800 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="p-2 bg-red-50 border border-red-200 text-red-800 rounded mb-4">{{ session('error') }}</div>
            @endif

            @if(Auth::user()->nivel == \App\Models\User::NIVEL_ADMINISTRADOR)
                <a href="{{ route('admin.users.create') }}" class="fixed bottom-6 right-6 bg-[#0C2E5C] text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-blue-800 transition duration-200 z-50">
                    + Novo Usuário
                </a>
            @endif

            <div class="p-3 overflow-x-auto">
                <div class="w-full">
                    
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
                                <input x-model="query" type="text" placeholder="Buscar por nome, e-mail ou nível..." class="border rounded px-3 py-2 text-sm w-full md:w-80" />
                                <button type="button" @click="query=''" class="cursor-pointer px-3 py-2 bg-gray-100 rounded text-sm">Limpar</button>
                            </div>
                        </div>

                        <div class="hidden md:grid grid-cols-12 gap-4 bg-[#0C2E5C] text-white px-4 py-2 rounded-t-md text-sm font-semibold mt-3">
                            <div class="col-span-4">Nome</div>
                            <div class="col-span-5">Email</div>
                            <div class="col-span-1 text-center">Nível</div> <div class="col-span-2 text-center">Ações</div>
                        </div>

                        <div class="space-y-3 mt-2">
                            @forelse($users as $user)
                                @php
                                    $nome = strtolower($user->name);
                                    $email = strtolower($user->email);
                                    $nivelUser = strtolower((string) $user->nivel);
                                @endphp

                                <div 
                                    data-nome="{{ $nome }}" 
                                    data-email="{{ $email }}" 
                                    data-nivel="{{ $nivelUser }}" 
                                    x-show="matches($el)" 
                                    class="hidden md:grid grid-cols-12 gap-4 items-center bg-white px-4 py-2 rounded shadow border border-gray-200 hover:shadow-md transition"
                                >
                                    <div class="col-span-4 text-sm text-gray-700 font-medium">{{ $user->name }}</div>
                                    
                                    <div class="col-span-5 text-sm text-gray-700 truncate" title="{{ $user->email }}">
                                        {{ $user->email }}
                                    </div>
                                    
                                    <div class="col-span-1 text-sm text-gray-700 text-center">{{ $user->nivel }}</div>
                                    
                                    <div class="col-span-2 text-center flex justify-center gap-2">
                                        @if(Auth::user()->nivel == \App\Models\User::NIVEL_ADMINISTRADOR)
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="me-2">
                                                <x-icons-logo name="edit"/>
                                            </a>

                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Deseja excluir este usuário?')" type="submit">
                                                    <x-icons-logo name="delete"/>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <div 
                                    data-nome="{{ $nome }}" 
                                    data-email="{{ $email }}" 
                                    data-nivel="{{ $nivelUser }}" 
                                    x-show="matches($el)" 
                                    class="md:hidden bg-white px-4 py-3 rounded shadow border border-gray-200"
                                >
                                    <div class="flex justify-between items-start">
                                        <div class="w-full">
                                            <div class="text-xs text-gray-500">Nome</div>
                                            <div class="text-sm text-gray-700 font-medium mb-2">{{ $user->name }}</div>
                                            
                                            <div class="text-xs text-gray-500">E-mail</div>
                                            <div class="text-sm text-gray-700 mb-2 break-all">{{ $user->email }}</div>

                                            <div class="text-xs text-gray-500">Nível</div>
                                            <div class="text-sm text-gray-700">{{ $user->nivel }}</div>
                                        </div>
                                    </div>

                                    @if(Auth::user()->nivel == \App\Models\User::NIVEL_ADMINISTRADOR)
                                        <div class="mt-3 flex items-center gap-3 border-t pt-2 border-gray-100">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-sm text-[#0C2E5C] font-medium">Editar</a>
                                            
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Deseja excluir este usuário?')" type="submit" class="cursor-pointer text-sm text-red-600">Excluir</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>

                            @empty
                                <div class="text-center text-gray-500 py-4">
                                    Nenhum usuário encontrado.
                                </div>
                            @endforelse
                        </div> 
                    </div> 
                </div>
            </div>
        </div>
    </div>
</x-app-layout>