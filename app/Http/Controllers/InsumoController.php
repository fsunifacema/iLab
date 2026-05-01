<?php

namespace App\Http\Controllers;

use App\Http\Requests\Insumos\StoreInsumoRequest;
use App\Http\Requests\Insumos\UpdateInsumoRequest;
use App\Models\Insumo;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $insumos = Insumo::all();
        return view('insumo.index', compact('insumos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('insumo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInsumoRequest $request)
    {
        try {
            $data = $request->validated();
            $data['drq'] = $request->has('drq');
            Insumo::create($data);

            return redirect()->route('insumos.index')->with('success', 'Insumo criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('insumos.index')->with('error', 'Erro ao criar insumo.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $insumo = Insumo::findOrFail($id);
        return view('insumo.show', compact('insumo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $insumo = Insumo::findOrFail($id);
        return view('insumo.edit', compact('insumo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInsumoRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $data['drq'] = $request->has('drq');
            Insumo::findOrFail($id)->update($data);

            return redirect()->route('insumos.index')->with('success', 'Insumo atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('insumos.index')->with('error', 'Erro ao atualizar insumo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Insumo::findOrFail($id)->delete();
            return redirect()->route('insumos.index')->with('success', 'Insumo deletado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('insumos.index')->with('error', 'Erro ao deletar insumo.');
        }
    }

    public function estoque(Insumo $insumo)
    {
        return view('insumo.estoque', compact('insumo'));
    }
}
