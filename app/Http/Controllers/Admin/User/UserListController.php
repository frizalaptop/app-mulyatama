<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserListService;
use App\Traits\HandlersException;

/**
 * Controller khusus admin untuk mengelola data user
 */
class UserListController extends Controller
{

    use HandlersException;

    protected $userListService;

    // Inject class service yang dibutuhkan dalam controller
    public function __construct(UserListService $userListService)
    {
        $this->userListService = $userListService;
    }

    /**
     * Mengambil view daftar user 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function index ()
    {
        try {
            $data = $this->userListService->getUserListViewData();
            return view('user.user-list', $data);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Mengambil data-tabel user
     * @return \Illuminate\Http\JsonResponse
     */
    public function tabel ()
    {
        try {
            $data = $this->userListService->getUserDataTable();
            return response()->json(['data' => $data]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Mengambil data user berdasarkan id
     * @param mixed $id user id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getId ($id) 
    {
        try {
            $user = $this->userListService->getUserById($id);
            return response()->json([
                'success' => true,
                'message' => 'User ditemukan.',
                'user' => $user,
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'User tidak ditemukan');
        }
    }

    /**
     * Menambah data user
     * @param \Illuminate\Http\Request $request [name, email, password, password_confirm, perusahaan, whatsapp, telegram, alamat, aktivasi, role]
     * @return \Illuminate\Http\JsonResponse
     */
    public function simpan (AddUserRequest $request)
    {
        try {
            $this->userListService->addUser($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'User baru berhasil ditambahkan.',
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Mengubah data user
     * @param \Illuminate\Http\Request $request [name, email, password, password_confirm, perusahaan, whatsapp, telegram, alamat, aktivasi, role]
     * @param mixed $id user id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update (UpdateUserRequest $request, $id)
    {
        try {
            $this->userListService->updateUser($id, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'User berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'User tidak ditemukan');
        }
    }
}
