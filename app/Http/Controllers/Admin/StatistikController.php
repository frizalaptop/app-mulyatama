<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\StatistikService;
use App\Traits\HandlersException;

/**
 * Controller khusus menangani data statistik
 */
class StatistikController extends Controller
{
    use HandlersException;

    /**
     * Mengambil data statistik user
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function userList ()
    {
        try {
            $userTotal = User::count();

            $userAktif = User::where('active', true)->count();

            $userNonaktif = User::where('active', false)->count();

            $userAdmin = User::whereHas('roles', function ($q) {
                $q->where('name', 'Admin');
            })->count();

            $userKlien = User::whereHas('roles', function ($q) {
                $q->where('name', 'Klien');
            })->count();

            $stats = [
                'user_total'    => $userTotal,
                'user_aktif'    => $userAktif,
                'user_nonaktif' => $userNonaktif,
                'user_admin'    => $userAdmin,
                'user_klien'    => $userKlien,
            ];

            return response()->json($stats);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function userLogin ()
    {

    }
}
