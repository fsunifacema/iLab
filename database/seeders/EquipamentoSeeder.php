<?php

namespace Database\Seeders;

use App\Models\Equipamento;
use Illuminate\Database\Seeder;

class EquipamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipamentos = [
            [
                'nome' => 'Microscópio Óptico',
                'marca' => 'Leica',
                'modelo' => 'DM100',
                'manutencao' => true,
                'calibracao' => true,
            ],
            [
                'nome' => 'Balança Analítica',
                'marca' => 'Shimadzu',
                'modelo' => 'AUW220D',
                'manutencao' => true,
                'calibracao' => true,
            ],
            [
                'nome' => 'Centrífuga de Bancada',
                'marca' => 'Eppendorf',
                'modelo' => '5415D',
                'manutencao' => true,
                'calibracao' => false,
            ],
            [
                'nome' => 'pHmetro',
                'marca' => 'Hanna',
                'modelo' => 'HI98103',
                'manutencao' => true,
                'calibracao' => true,
            ],
            [
                'nome' => 'Estufa de Secagem',
                'marca' => 'Fischer',
                'modelo' => 'EF-600',
                'manutencao' => false,
                'calibracao' => false,
            ],
        ];

        foreach ($equipamentos as $equipamento) {
            Equipamento::updateOrCreate([
                'nome' => $equipamento['nome'],
                'marca' => $equipamento['marca'],
                'modelo' => $equipamento['modelo'],
            ], $equipamento);
        }
    }
}
