<?php

namespace App\Http\Requests\Laboratorios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLaboratorioRequest extends FormRequest
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
                ['required', 'string', Rule::unique('laboratorios', 'nome')->ignore($this->route('laboratorio'))], // Validação composta, verificando se na tabela laboratorios ja existe um laboratorio com o mesmo nome, ignorando o laboratorio atual

                'capacidade' => 'required|integer',
                'tipo' => 'required|string',
                'localizacao' => 'required|string',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'capacidade.required' => 'O campo capacidade é obrigatório.',
            'tipo.required' => 'O campo tipo é obrigatório.',
            'localizacao.required' => 'O campo localização é obrigatório.',

            'nome.string' => 'O campo nome deve ser uma string.',
            'capacidade.integer' => 'O campo capacidade deve ser um inteiro.',
            'tipo.string' => 'O campo tipo deve ser uma string.',
            'localizacao.string' => 'O campo localização deve ser uma string.',
        ];
    }
}
