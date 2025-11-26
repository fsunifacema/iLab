<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nivel',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Retorna o tipo de usuário com base no seu nível de acesso.
     *
     * Este método utiliza o valor do atributo 'nivel' para determinar
     * e retornar uma string representando o tipo correspondente,
     * como 'Professor', 'Técnico', 'Coordenador de curso' ou 'Coordenador de laboratório'.
     *
     * Útil para exibir o papel do usuário no sistema de forma legível.

     * @return string
     */
    public function getTypeAttribute(): string
    {
        return match ($this->nivel) {
            0 => 'Comum',
            1 => 'Professor',
            2 => 'Tecnico',
            3 => 'Coordenador_curso',
            4 => 'Coordenador_laboratorio',
        };
    }

    /**
     * Níveis de acesso disponíveis no sistema.
     *
     * Cada constante representa um papel específico atribuído ao usuário,
     * utilizado para controle de permissões e visibilidade de funcionalidades.
     */
    public const NIVEL_COMUM = 0;
    public const NIVEL_PROFESSOR = 1;
    public const NIVEL_TECNICO = 2;
    public const NIVEL_COORDENADOR_CURSO = 3;
    public const NIVEL_COORDENADOR_LABORATORIO = 4;

    /**
     * Retorna uma lista (nivel => label) dos níveis disponíveis no sistema.
     * Centraliza os rótulos para uso em selects e formulários, facilitando
     * a adição futura de novos níveis.
     *
     * @return array<int,string>
     */
    public static function getNiveisDisponiveis(): array
    {
        return [
            self::NIVEL_PROFESSOR => 'Professor',
            self::NIVEL_TECNICO => 'Técnico',
            self::NIVEL_COORDENADOR_CURSO => 'Coordenador de Curso',
            self::NIVEL_COORDENADOR_LABORATORIO => 'Coordenador de Laboratório',
        ];
    }
}
