<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfilAkunRequest;
use App\Http\Requests\UpdateProfilInfoRequest;
use App\Services\ProfilService;
use App\Traits\HandlersException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{

    use HandlersException;

    protected $profilService;

    // Inject class service yang dibutuhkan dalam controller
    public function __construct(ProfilService $profilService)
    {
        $this->profilService = $profilService;
    }

    public function index()
    {
        try {
            $data = $this->profilService->getProfilViewData();
            return view('user.user-profile', $data);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function updateAkun(UpdateProfilAkunRequest $request, $userId)
    { 
        try {
            $this->profilService->updateProfilAkun($request->validated(), $userId);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'User tidak ditemukan');
        }
    }

    public function updateInfo(UpdateProfilInfoRequest $request, $userId)
    {
        try {
            $this->profilService->updateProfilInfo($request->validated(), $userId);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'User tidak ditemukan');
        }
    }
}
