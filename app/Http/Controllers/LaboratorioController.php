<?php

namespace App\Http\Controllers;

use App\Http\Requests\Laboratorios\StoreLaboratorioRequest;
use App\Http\Requests\Laboratorios\UpdateLaboratorioRequest;
use App\Models\Laboratorio;
use Illuminate\Http\Request;

class LaboratorioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laboratorios = Laboratorio::all();
        return view('laboratorios.index', compact('laboratorios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('laboratorios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLaboratorioRequest $request)
    {
        try {
            Laboratorio::create($request->validated());
            return redirect()->route('laboratorios.index')->with('success', 'Laboratorio criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('laboratorios.index')->with('error', 'Erro ao criar laboratorio.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Laboratorio $laboratorio)
    {
        return view('laboratorios.show', compact('laboratorio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Laboratorio $laboratorio)
    {
        return view('laboratorios.edit', compact('laboratorio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLaboratorioRequest $request, Laboratorio $laboratorio)
    {
        try {
            $laboratorio->update($request->validated());
            return redirect()->route('laboratorios.index')->with('success', 'Laboratorio atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('laboratorios.index')->with('error', 'Erro ao atualizar laboratorio.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Laboratorio $laboratorio)
    {
        try {
            $laboratorio->delete();
            return redirect()->route('laboratorios.index')->with('success', 'Laboratorio deletado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('laboratorios.index')->with('error', 'Erro ao deletar laboratorio.');
        }
    }
}
