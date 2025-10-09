<?php

namespace App\Services;

use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Throwable;

/**
 * Bertugas sebagai eksekutor logika inti dari suatu proses controller
 * Tiap method menjalankan logika bisnis dan aturan kerja aplikasi
 * Tidak boleh berinteraksi dengan database langsung
 * @see app\Http\Controllers\Admin\StatistikController.php
 */
class StatistikService 
{

    protected $userRepository;
    protected $roleRepository;

    // Inject dependency yang dibutuhkan
    public function __construct(
        UserRepository $userRepository,
        RoleRepository $roleRepository,
    )
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }


    /**
     * Meminta data numerik statistik dari jumlah user dan pemilik role
     * @return array{user_admin: int, user_aktif: int, user_klien: int, user_nonaktif: int, user_total: int}
     */
    public function getUserStatistics(): array
    {
        try {
            return [
                'user_total'    => $this->userRepository->getAllCount(),
                'user_aktif'    => $this->userRepository->getActiveCount(),
                'user_nonaktif' => $this->userRepository->getInactiveCount(),
                'user_admin'    => $this->roleRepository->getRoleCount('Admin'),
                'user_klien'    => $this->roleRepository->getRoleCount('Klien'),
            ];
        } catch (Throwable $e) {
            throw $e;
        }
    }
}