<?php

namespace App\Http\Requests\Insumos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInsumoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // tem que mudar para true para que haja a validação
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => [
                'required',
                'string',
                Rule::unique('insumos')->where(function ($query) {
                    return $query->where('marca', $this->marca)->where('modelo', $this->modelo);
                }) // Validação composta, verificando se na tabela insumos ja existe um insumo com o mesmo nome,marca e modelo
            ],

            'validade' => 'nullable|date',
            'marca' => 'required|string',
            'modelo' => 'required|string',
            'descricao' => 'required|string',

        ];
    }

    /**
     * Get the validation error messages.
     *
     * This method overrides the parent messages method to allow
     * customization of the error messages returned by the
     * request validation.
     *
     * @return array<string, string>
     */

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'marca.required' => 'O campo marca é obrigatório.',
            'modelo.required' => 'O campo modelo é obrigatório.',
            'descricao.required' => 'O campo descricao é obrigatório.',

            'nome.string' => 'O campo nome deve ser uma string.',
            'validade.date' => 'O campo validade deve ser uma data.',
            'marca.string' => 'O campo marca deve ser uma string.',
            'modelo.string' => 'O campo modelo deve ser uma string.',
            'descricao.string' => 'O campo descricao deve ser uma string.',

            'nome.unique' => 'Já existe um insumo com o mesmo nome, marca e modelo.',
        ];
    }
}
