<?php

namespace Database\Seeders;

use App\Models\Insumo;
use Illuminate\Database\Seeder;

class InsumoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insumos = [
            [
                'nome' => 'Álcool 70%',
                'validade' => now()->addYear()->format('Y-m-d'),
                'marca' => 'Sanitex',
                'modelo' => 'Frasco 500ml',
                'descricao' => 'Solvente e antisséptico para limpeza de superfícies e material de laboratório.',
                'quantidade' => 50,
                'drq' => false,
            ],
            [
                'nome' => 'Ácido acético glacial',
                'validade' => now()->addYears(2)->format('Y-m-d'),
                'marca' => 'Merck',
                'modelo' => 'ACS Reagent',
                'descricao' => 'Reagente químico de alta pureza utilizado em titulações e extrações.',
                'quantidade' => 10,
                'drq' => true,
            ],
            [
                'nome' => 'Papel filtro',
                'validade' => null,
                'marca' => 'Whatman',
                'modelo' => 'Qualidade 1',
                'descricao' => 'Papel para filtração em processos de separação sólido-líquido.',
                'quantidade' => 200,
                'drq' => false,
            ],
            [
                'nome' => 'Lâmina para microscópio',
                'validade' => null,
                'marca' => 'LabPro',
                'modelo' => 'Pad 72 unidades',
                'descricao' => 'Lâminas de vidro para preparo de cortes histológicos e observações microscópicas.',
                'quantidade' => 300,
                'drq' => false,
            ],
            [
                'nome' => 'Tubo de ensaio',
                'validade' => null,
                'marca' => 'Corning',
                'modelo' => '16x100 mm',
                'descricao' => 'Tubo de vidro para realização de reações em pequena escala.',
                'quantidade' => 100,
                'drq' => false,
            ],
        ];

        foreach ($insumos as $insumo) {
            Insumo::updateOrCreate([
                'nome' => $insumo['nome'],
                'marca' => $insumo['marca'],
                'modelo' => $insumo['modelo'],
            ], $insumo);
        }
    }
}
