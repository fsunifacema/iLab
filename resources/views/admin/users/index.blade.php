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
                                const qDigits = q.replace(/\D/g, '');
                                const nome = el.dataset.nome || '';
                                const email = el.dataset.email || '';
                                const nivel = (el.dataset.nivel || '').toString();
                                if(nome.includes(q) || tipo.includes(q) || local.includes(q) || capacidade.includes(q)) return true;
                                if(qDigits !== '' && capacidade.replace(/\D/g, '').includes(qDigits)) return true;
                                return false;
                            }
                        }">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <input x-model="query" type="text" placeholder="Buscar por nome, email, nivel..." class="border rounded px-3 py-2 text-sm w-full md:w-80" />
                                <button type="button" @click="query=''" class="cursor-pointer px-3 py-2 bg-gray-100 rounded text-sm">Limpar</button>
                            </div>
                        </div>



                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nível</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->nivel }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-sm text-[#0C2E5C] mr-3">Editar</a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Deseja excluir este usuário?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $users->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>