<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StatistikService;
use App\Traits\HandlersException;

/**
 * Controller khusus menangani data statistik
 */
class StatistikController extends Controller
{
    use HandlersException;

    protected $statistikService;

    public function __construct(StatistikService $statistikService)
    {
        $this->statistikService = $statistikService;
    } 

    /**
     * Mengambil data statistik user
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function userList ()
    {
        try {
            $stats = $this->statistikService->getUserStatistics();
            return response()->json($stats);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function userLogin ()
    {

    }
}
