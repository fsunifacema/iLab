<?php

namespace App\Http\Requests\Insumos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInsumoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
                Rule::unique('insumos')->ignore($this->route('insumo'))->where(
                    fn($query) =>
                    $query->where('marca', $this->marca)
                        ->where('modelo', $this->modelo)
                ) // Validação composta, verificando se na tabela insumos ja existe um insumo com o mesmo nome,marca e modelo, ignorando o insumo atual, para que possa manter na atualização os mesmos dados caso queira mudar apenas a descrição por exemplo.
            ],

            'validade' => 'nullable|date',
            'marca' => 'required|string',
            'modelo' => 'required|string',
            'descricao' => 'required|string',
            'quantidade' => 'required|integer',
            'quantidade' => 'required|integer|min:1',
            'drq' => 'nullable|boolean',

        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'marca.required' => 'O campo marca é obrigatório.',
            'modelo.required' => 'O campo modelo é obrigatório.',
            'descricao.required' => 'O campo descricao é obrigatório.',
            'quantidade.required' => 'O campo quantidade é obrigatório.',
            'quantidade.min' => 'O campo quantidade deve ser maior que 0.',

            'nome.string' => 'O campo nome deve ser uma string.',
            'validade.date' => 'O campo validade deve ser uma data.',
            'marca.string' => 'O campo marca deve ser uma string.',
            'modelo.string' => 'O campo modelo deve ser uma string.',
            'descricao.string' => 'O campo descricao deve ser uma string.',

            'nome.unique' => 'Já existe um insumo com o mesmo nome, marca e modelo.',
        ];
    }
}
