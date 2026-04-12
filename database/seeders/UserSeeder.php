<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $usuarios = [
      [
        'name'     => 'Professor Teste',
        'email'    => 'professor@teste.com',
        'nivel'    => User::NIVEL_PROFESSOR,
      ],
      [
        'name'     => 'Técnico Teste',
        'email'    => 'tecnico@teste.com',
        'nivel'    => User::NIVEL_TECNICO,
      ],
      [
        'name'     => 'Coordenador de Curso Teste',
        'email'    => 'coordenador.curso@teste.com',
        'nivel'    => User::NIVEL_COORDENADOR_CURSO,
      ],
      [
        'name'     => 'Coordenador de Laboratório Teste',
        'email'    => 'coordenador.laboratorio@teste.com',
        'nivel'    => User::NIVEL_COORDENADOR_LABORATORIO,
      ],
      [
        'name'     => 'Administrador Teste',
        'email'    => 'administrador@teste.com',
        'nivel'    => User::NIVEL_ADMINISTRADOR,
      ],
    ];

    foreach ($usuarios as $usuario) {
      User::updateOrCreate(
        ['email' => $usuario['email']],
        [
          'name'              => $usuario['name'],
          'password'          => Hash::make('12345678'),
          'nivel'             => $usuario['nivel'],
          'email_verified_at' => now(),
        ]
      );
    }

    $this->command->info('Usuários de teste criados com sucesso!');
    $this->command->table(
      ['Nome', 'Email', 'Senha', 'Nível'],
      collect($usuarios)->map(fn($u) => [
        $u['name'],
        $u['email'],
        '12345678',
        $u['nivel'],
      ])->toArray()
    );
  }
}
