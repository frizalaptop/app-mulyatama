<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Menangani semua error yang mungkin terjadi pada aplikasi
 */
trait HandlersException {

    use ServiceLogger;

   /**
     * Menangani seluruh exception dan mengembalikan response yang sesuai.
     *
     * @param Throwable $e
     * @param string|null $notFoundMsg
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
            $this->logException($e);
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