<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use const Grpc\STATUS_ALREADY_EXISTS;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        /*if ($exception instanceof PermissionAlreadyExists) {
            return response()->json([
                'message' => 'Permission already exists',
            ], method_exists($exception, 'getStatusCode') ? $exception->getStausCode() : 500);
        }
        if ($exception instanceof RoleAlreadyExists) {
            return response()->json([
                'message' => 'Role already exists',
            ], method_exists($exception, 'getStatusCode') ? $exception->getStausCode() : 500);
        }*/
        return parent::render($request, $exception);
    }
}
