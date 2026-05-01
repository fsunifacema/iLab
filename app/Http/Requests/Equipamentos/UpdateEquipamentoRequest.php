<?php

namespace App\Http\Requests\Equipamentos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEquipamentoRequest extends FormRequest
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
                Rule::unique('equipamentos')->ignore($this->route('equipamento'))->where(
                    fn($query) =>
                    $query->where('marca', $this->marca)
                        ->where('modelo', $this->modelo)
                )
            ],
            'marca' => 'required|string',
            'modelo' => 'required|string',
            'manutencao' => 'nullable|boolean',
            'calibracao' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'marca.required' => 'O campo marca é obrigatório.',
            'modelo.required' => 'O campo modelo é obrigatório.',

            'nome.string' => 'O campo nome deve ser uma string.',
            'marca.string' => 'O campo marca deve ser uma string.',
            'modelo.string' => 'O campo modelo deve ser uma string.',

            'nome.unique' => 'Já existe um equipamento com o mesmo nome, marca e modelo.',
        ];
    }
}
