<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SemestreLetivo;
use Illuminate\Http\Request;

class SemestreLetivoController extends Controller
{
    public function index()
    {
        $semestres = SemestreLetivo::orderByDesc('data_inicio')->get();
        return view('admin.semestres.index', compact('semestres'));
    }

    public function create()
    {
        return view('admin.semestres.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'        => 'required|string|max:50|unique:semestres_letivos,nome',
            'data_inicio' => 'required|date',
            'data_fim'    => 'required|date|after:data_inicio',
        ]);

        $data['ativo'] = $request->boolean('ativo');

        SemestreLetivo::create($data);

        return redirect()->route('admin.semestres.index')->with('success', 'Semestre letivo cadastrado com sucesso!');
    }

    public function edit(SemestreLetivo $semestre)
    {
        return view('admin.semestres.edit', compact('semestre'));
    }

    public function update(Request $request, SemestreLetivo $semestre)
    {
        $data = $request->validate([
            'nome'        => 'required|string|max:50|unique:semestres_letivos,nome,' . $semestre->id,
            'data_inicio' => 'required|date',
            'data_fim'    => 'required|date|after:data_inicio',
        ]);

        $data['ativo'] = $request->boolean('ativo');

        $semestre->update($data);

        return redirect()->route('admin.semestres.index')->with('success', 'Semestre letivo atualizado com sucesso!');
    }

    public function destroy(SemestreLetivo $semestre)
    {
        $semestre->delete();

        return redirect()->route('admin.semestres.index')->with('success', 'Semestre letivo excluído com sucesso!');
    }
}
