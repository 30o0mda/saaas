<?php

namespace App\Exceptions;

use App\Helpers\ApiResponseHelper;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use App\Helpers\ApiResponse;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        return ApiResponseHelper::response(
            false,
            'خطأ في التحقق من البيانات',
            $exception->errors(),
            422
        );
    }
}
