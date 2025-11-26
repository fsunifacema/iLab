<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create()
    {
        $niveis = User::getNiveisDisponiveis();
        return view('admin.users.create', compact('niveis'));
    }

    public function index()
    {
        $users = User::orderBy('name')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nivel' => 'required|in:' . implode(',', array_keys(User::getNiveisDisponiveis())),
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'nivel' => (int) $data['nivel'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Usuário criado com sucesso');
    }

    public function edit(User $user)
    {
        $niveis = User::getNiveisDisponiveis();
        return view('admin.users.edit', compact('user', 'niveis'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'nivel' => 'required|in:' . implode(',', array_keys(User::getNiveisDisponiveis())),
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->nivel = (int) $data['nivel'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso');
    }

    public function destroy(User $user)
    {
        // Evita auto-exclusão
        if (auth()->id() == $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Você não pode excluir seu próprio usuário.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuário excluído');
    }
}
