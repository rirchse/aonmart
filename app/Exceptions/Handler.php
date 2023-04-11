<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return apiResponse(404, "Not found");
            }
        });

        $this->reportable(function (Throwable $e) {
            Log::error($e);
            return apiResponse(500, "Something has gone wrong on the server. Please! try again.");
        });
    }
}
