<?php

namespace App\Http\Controllers\admin\billboard;

use App\Http\Controllers\Controller;
use App\Traits\HandlersException;
use Illuminate\Http\Request;
use Throwable;

class BillboardSewaController extends Controller
{
    
    use HandlersException;

    /**
     * Mengambil view daftar billboard 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function index ()
    {
        try {
            $data = ['title' => 'Billboard Sewa'];
            return view('admin.billboard.billboard-sewa', $data);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function simpan ()
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Penyewaan Billboard baru berhasil ditambahkan.',
            ]);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }
    
}
