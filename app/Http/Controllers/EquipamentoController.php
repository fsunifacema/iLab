<?php

namespace App\Http\Controllers;

use App\Http\Requests\Equipamentos\StoreEquipamentoRequest;
use App\Http\Requests\Equipamentos\UpdateEquipamentoRequest;
use App\Models\Equipamento;
use Illuminate\Http\Request;

class EquipamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipamentos = Equipamento::all();
        return view('equipamento.index', compact('equipamentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('equipamento.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEquipamentoRequest $request)
    {

        try {
            // Preparar dados antes de criar
            $data = $request->validated();
            $data['manutencao'] = $request->has('manutencao');
            $data['calibracao'] = $request->has('calibracao');

            Equipamento::create($data);
            return redirect()->route('equipamentos.index')->with('success', 'Equipamento criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('equipamentos.index')->with('error', 'Erro ao criar equipamento.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipamento $equipamento)
    {
        return view('equipamento.show', compact('equipamento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipamento $equipamento)
    {
        return view('equipamento.edit', compact('equipamento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEquipamentoRequest $request, Equipamento $equipamento)
    {
        try {
            // Preparar dados antes de atualizar
            $data = $request->validated();
            $data['manutencao'] = $request->has('manutencao');
            $data['calibracao'] = $request->has('calibracao');

            $equipamento->update($data);

            return redirect()->route('equipamentos.index')->with('success', 'Equipamento atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('equipamentos.index')->with('error', 'Erro ao atualizar equipamento.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipamento $equipamento)
    {
        try {
            $equipamento->delete();
            return redirect()->route('equipamentos.index')->with('success', 'Equipamento deletado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('equipamentos.index')->with('error', 'Erro ao deletar equipamento.');
        }
    }
}
