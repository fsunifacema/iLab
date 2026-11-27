<?php

namespace Database\Seeders;

use App\Models\Laboratorio;
use Illuminate\Database\Seeder;

class LaboratorioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $laboratorios = [
            [
                'nome' => 'Laboratório de Informática 1',
                'capacidade' => 30,
                'tipo' => 'Informática',
                'localizacao' => 'Prédio Principal',
            ],
            [
                'nome' => 'Laboratório de Informática 2',
                'capacidade' => 30,
                'tipo' => 'Informática',
                'localizacao' => 'Prédio Principal',
            ],
            [
                'nome' => 'Laboratório de Informática 3',
                'capacidade' => 30,
                'tipo' => 'Informática',
                'localizacao' => 'Prédio Principal',
            ],
            [
                'nome' => 'Laboratório de Informática Charles Babbage',
                'capacidade' => 30,
                'tipo' => 'Informática',
                'localizacao' => 'Prédio Principal',
            ],
        ];

        foreach ($laboratorios as $laboratorio) {
            Laboratorio::updateOrCreate([
                'nome' => $laboratorio['nome'],
            ], $laboratorio);
        }
    }
}
