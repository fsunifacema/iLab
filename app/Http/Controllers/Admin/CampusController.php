<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    public function index()
    {
        $campuses = Campus::orderBy('nome')->get();
        return view('admin.campus.index', compact('campuses'));
    }

    public function create()
    {
        return view('admin.campus.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'     => 'required|string|max:255|unique:campuses,nome',
            'sigla'    => 'nullable|string|max:20',
            'cidade'   => 'required|string|max:255',
            'endereco' => 'nullable|string|max:255',
        ]);

        Campus::create($data);

        return redirect()->route('admin.campus.index')->with('success', 'Campus cadastrado com sucesso!');
    }

    public function edit(Campus $campus)
    {
        return view('admin.campus.edit', compact('campus'));
    }

    public function update(Request $request, Campus $campus)
    {
        $data = $request->validate([
            'nome'     => 'required|string|max:255|unique:campuses,nome,' . $campus->id,
            'sigla'    => 'nullable|string|max:20',
            'cidade'   => 'required|string|max:255',
            'endereco' => 'nullable|string|max:255',
        ]);

        $campus->update($data);

        return redirect()->route('admin.campus.index')->with('success', 'Campus atualizado com sucesso!');
    }

    public function destroy(Campus $campus)
    {
        $campus->delete();

        return redirect()->route('admin.campus.index')->with('success', 'Campus excluído com sucesso!');
    }
}
