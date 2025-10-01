<?php

namespace App\Http\Controllers;

use App\Http\Requests\Insumos\StoreInsumoRequest;
use App\Http\Requests\Insumos\UpdateInsumoRequest;
use App\Models\Insumo;

class InsumoApiController extends Controller // Usado para testar metodos, rotas, validação via api
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        return Insumo::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInsumoRequest $request)
    {
        Insumo::create($request->validated());

        return response()->json([
            'message' => 'Insumo criado com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $insumo = Insumo::findOrFail($id);

            return response()->json($insumo);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Record not found',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Insumo $insumo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInsumoRequest $request, $id)
    {
        $insumo = Insumo::findOrFail($id);
        $insumo->update($request->validated());

        return response()->json([
            'message' => 'Insumo atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $insumo = Insumo::findOrFail($id);
        $insumo->delete();

        return response()->json([
            'message' => 'Insumo deletado com sucesso!'
        ]);
    }
}
