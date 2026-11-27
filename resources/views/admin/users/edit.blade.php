<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0C2E5C]">Editar Usuário</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow p-6 rounded">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nome</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full border rounded px-3 py-2" required>
                            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">E-mail</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full border rounded px-3 py-2" required>
                            @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nova Senha (opcional)</label>
                                <input type="password" name="password" class="mt-1 block w-full border rounded px-3 py-2">
                                @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Confirmação de Senha</label>
                                <input type="password" name="password_confirmation" class="mt-1 block w-full border rounded px-3 py-2">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nível</label>
                            <select name="nivel" class="mt-1 block w-full border rounded px-3 py-2" required>
                                @foreach($niveis as $k => $label)
                                    <option value="{{ $k }}" {{ old('nivel', $user->nivel) == $k ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('nivel') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('admin.users.index') }}" class="mr-2 px-4 py-2 bg-gray-200 rounded">Cancelar</a>
                            <button type="submit" class="px-4 py-2 bg-[#0C2E5C] text-white rounded">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>