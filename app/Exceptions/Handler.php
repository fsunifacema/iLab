<?php

namespace App\Exceptions;


use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler // hanlder é a central de controle de exceções do laravel
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    public function render($request, Throwable $exception) // o metodo render é chamado qnd há uma exceção
    {
        if ($request->is("api/*")) { // verificando se a req esta indo para uma rota da api
            if ($exception instanceof ValidationException) { // verifica se é uma falha de validação
                return response()->json( // retorna um json com os erros de validação e codigo http
                    $exception->errors(),
                    $exception->status
                );
            }
        }
    }
}
