<?php

namespace App\Exceptions;

use App\Mail\ExceptionHandlerMail;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
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
    public function render($request, Throwable $exception)
    {

        if ($exception instanceof ValidationException) {
            $exceptionsDatas = [
                'statusCode' => $exception->getCode(),
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $exception->validator->getMessageBag()
            ];
            return response()->json($exceptionsDatas);
        }
        if ($exception instanceof AuthorizationException) {
            $exceptionsDatas = [
                'statusCode' => $exception->getCode(),
                'success' => false,
                'message' => "Non autorisé",
                'errors' => $exception->getMessage()
            ];
            // if (env('APP_ENV') != 'local') {
            //     Mail::to(['marc.b@carex54.com', 'ulrich.a@carex54.com', "mary-ange.q@carex54.com"])->send(new ExceptionHandlerMail($exceptionsDatas));
            // }
            return response()->json($exceptionsDatas);
        }

        if ($exception instanceof NotFoundHttpException) {

            $exceptionsDatas = [
                'statusCode' => $exception->getStatusCode(),
                'success' => false,
                'message' => "Url introuvable",
                'errors' => $exception->getMessage()
            ];
            // if (env('APP_ENV') != 'local') {
            //     Mail::to(['marc.b@carex54.com', 'ulrich.a@carex54.com', "mary-ange.q@carex54.com"])->send(new ExceptionHandlerMail($exceptionsDatas));
            // }
            return response()->json($exceptionsDatas);
        }
        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            $exceptionsDatas = [
                'statusCode' => $exception->getCode(),
                'success' => false,
                'message' => "Non authentifié",
                'errors' => $exception->getMessage()
            ];
            // if (env('APP_ENV') != 'local') {
            //     Mail::to(['marc.b@carex54.com', 'ulrich.a@carex54.com', "mary-ange.q@carex54.com"])->send(new ExceptionHandlerMail($exceptionsDatas));
            // }
            return response()->json($exceptionsDatas);
        }
        if ($exception instanceof ModelNotFoundException) {

            $exceptionsDatas = [
                'statusCode' => $exception->getCode(),
                'success' => false,
                'message' => "Aucune instance du model {$exception->getModel()} ne correspond à l'id fourni",
                'errors' => $exception->getMessage()
            ];
            // if (env('APP_ENV') != 'local') {
            //     Mail::to(['marc.b@carex54.com', 'ulrich.a@carex54.com', "mary-ange.q@carex54.com"])->send(new ExceptionHandlerMail($exceptionsDatas));
            // }
            return response()->json($exceptionsDatas);
        }
        if ($exception instanceof MethodNotAllowedHttpException) {

            $exceptionsDatas = [
                'statusCode' => $exception->getStatusCode(),
                'success' => false,
                'message' => "Invalide verbe HTTP",
                'errors' => $exception->getMessage()
            ];
            // if (env('APP_ENV') != 'local') {
            //     Mail::to(['marc.b@carex54.com', 'ulrich.a@carex54.com', "mary-ange.q@carex54.com"])->send(new ExceptionHandlerMail($exceptionsDatas));
            // }
            return response()->json($exceptionsDatas);
        }

        $rendered = parent::render($request, $exception);
        $exceptionsDatas = [
            'statusCode' => $rendered->getStatusCode(),
            'success' => false,
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        ];
        // if (env('APP_ENV') != 'local') {
        //     Mail::to(['marc.b@carex54.com', 'ulrich.a@carex54.com', "mary-ange.q@carex54.com"])->send(new ExceptionHandlerMail($exceptionsDatas));
        // }
        return response()->json($exceptionsDatas);
    }
}
