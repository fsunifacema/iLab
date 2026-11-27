<?php

namespace App\Http\Controllers;

use App\Models\Roteiro;
use App\Models\Solicitacao;
use App\Models\UnidadeCurricular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnidadeCurricularController extends Controller
{
    // Acesso restrito ao Coordenador de Curso (nivel:3) na rota.

    public function index()
    {
        $unidades = UnidadeCurricular::with('criador')->orderBy('nome')->get();
        return view('unidades-curriculares.index', compact('unidades'));
    }

    public function create()
    {
        return view('unidades-curriculares.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'          => 'required|string|max:255|unique:unidades_curriculares,nome',
            'curso'         => 'nullable|string|max:255',
            'carga_horaria' => 'nullable|integer|min:1',
        ]);

        $data['user_id'] = auth()->id();

        UnidadeCurricular::create($data);

        return redirect()->route('unidades-curriculares.index')
            ->with('success', 'Unidade curricular criada com sucesso!');
    }

    public function edit(UnidadeCurricular $unidade)
    {
        return view('unidades-curriculares.edit', compact('unidade'));
    }

    public function update(Request $request, UnidadeCurricular $unidade)
    {
        $data = $request->validate([
            'nome'          => 'required|string|max:255|unique:unidades_curriculares,nome,' . $unidade->id,
            'curso'         => 'nullable|string|max:255',
            'carga_horaria' => 'nullable|integer|min:1',
        ]);

        $nomeAntigo = $unidade->nome;

        DB::transaction(function () use ($unidade, $data, $nomeAntigo) {
            $unidade->update($data);

            // Solicitações e roteiros guardam o NOME da UC; mantém tudo consistente ao renomear.
            if ($nomeAntigo !== $data['nome']) {
                Solicitacao::where('unidade_curricular', $nomeAntigo)->update(['unidade_curricular' => $data['nome']]);
                Roteiro::where('unidade_curricular', $nomeAntigo)->update(['unidade_curricular' => $data['nome']]);
            }
        });

        return redirect()->route('unidades-curriculares.index')
            ->with('success', 'Unidade curricular atualizada com sucesso!');
    }

    public function destroy(UnidadeCurricular $unidade)
    {
        $unidade->delete();

        return redirect()->route('unidades-curriculares.index')
            ->with('success', 'Unidade curricular excluída com sucesso!');
    }
}
