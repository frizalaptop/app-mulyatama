<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 * Menangani semua error yang mungkin terjadi pada aplikasi
 * @see app\Http\Controllers\Admin\User\UserListController.php
 * @see app\Http\Controllers\Admin\StatistikController.php
 */
trait HandlersException {

    use ServiceLogger;

   /**
     * Menangani seluruh exception dan mengembalikan response yang sesuai.
     *
     * @param Throwable $e base interface untuk object apapun yang dapat di throw melalui throw statement
     * @param string|null $notFoundMsg pesan custom jika suatu data tidak ditemukan
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    protected function handleException(Throwable $e, ?string $notFoundMsg = null)
    {
        if ($e instanceof ModelNotFoundException) {
            $status = 404;
            $message = $notFoundMsg ?? 'Data tidak ditemukan.';
        } elseif ($e instanceof ValidationException) {
            $status = 422;
            $message = $e->validator->errors()->first();
        } else {
            $status = 500;
            $message = 'Terjadi kesalahan pada server.';

            // Log hanya untuk error 500
            $this->logExceptionHttp($e);
        }

        // Jika permintaan AJAX → JSON response
        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status);
        }

        // Jika permintaan biasa → tampilkan view error
        abort($status, $message);
    }
}