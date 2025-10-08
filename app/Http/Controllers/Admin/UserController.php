<?php

namespace  App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\Traits\HandlersException;


class UserController extends Controller
{
    use HandlersException;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Mengambil view daftar user 
     * Role admin
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function userList()
    {
        try {
            $data = $this->userService->getUserListView();
            return view('user.user-list', $data);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Mengambil data-tabel user
     * Role admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable()
    {
        try {
            $data = $this->userService->getUserDataTable();
            return response()->json(['data' => $data]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Mengambil data user berdasarkan id
     * Role admin
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserById($id){
        try {
            $user = $this->userService->getUserById($id);
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
     * Mengambil statistik user
     * Role admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistic()
    {
        try {
            $stats = $this->userService->getUserStatistics();
            return response()->json($stats);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Menambah data user
     * Role admin
     * @param \Illuminate\Http\Request $request [name, email, password, password_confirm, perusahaan, whatsapp, telegram, alamat, aktivasi, role]
     * @return \Illuminate\Http\JsonResponse
     */
    public function addUser(AddUserRequest $request)
    {
        try {
            $this->userService->addUser($request->validated());
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
     * Role admin
     * @param \Illuminate\Http\Request $request [name, email, password, password_confirm, perusahaan, whatsapp, telegram, alamat, aktivasi, role]
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(UpdateUserRequest $request, $id)
    {
        try {
            $this->userService->updateUser($id, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'User berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'User tidak ditemukan');
        }
    }
}
