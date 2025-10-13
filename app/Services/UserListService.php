<?php

namespace App\Services;

use App\Events\UserSensitiveDataChanged;
use App\Repositories\ProfileRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Bertugas sebagai eksekutor logika inti dari suatu proses controller
 * Tiap method menjalankan logika bisnis dan aturan kerja aplikasi
 * Tidak boleh berinteraksi dengan database langsung
 * @see app\Http\Controllers\Admin\User\UserListController.php
 */
class UserListService 
{

    protected $userRepository;
    protected $profileRepository;
    protected $roleRepository;

    // Inject dependecy repository yang dibutuhkan dalam service
    public function __construct(
        UserRepository $userRepository,
        ProfileRepository $profileRepository,
        RoleRepository $roleRepository,
    )
    {
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Mengembalikan data title
     * @return array{title: string}
     */
    public function getUserListViewData()
    {
        try {
            return ['title' => 'User List'];
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * Meminta data semua user
     * @return \Illuminate\Database\Eloquent\Collection<int, array{created_at: mixed, email: string, id: mixed, last_login_at: mixed, name: string, role: TFirstDefault|TValue, status: bool, updated_at: mixed>|\Illuminate\Support\Collection<int, array{created_at: mixed, email: string, id: mixed, last_login_at: mixed, name: string, role: TFirstDefault|TValue, status: bool, updated_at: mixed}>}
     */
    public function getUserDataTable()
    {
        try {
            $users = $this->userRepository->getAll();

            return $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $user->active,
                    'role' => $this->roleRepository->getRoleName($user),
                    'last_login_at' => $user->last_login_at,
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
                ];
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * Meminta data suatu user
     * Meminta data profil dan role dari user terkait
     * @param mixed $id id milik user dibutuhkan
     * @return \App\Models\User|\Illuminate\Database\Eloquent\Collection<int, \App\Models\User>
     */
    public function getUserById($id)
    {
        try {
            $user = $this->userRepository->find($id);
            $user->profile = $this->profileRepository->findByUserId($user->id);
            $user->role = $this->roleRepository->getRoleName($user);
            return $user;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * Menjakankan proses pembuatan data user baru
     * @param array $data data user baru dan role user dibutuhkan, opsional untuk profile
     * @return void
     */
    public function addUser(array $data)
    {
        try {
            DB::transaction(function () use ($data) {
                $user = $this->userRepository->createUser($data);
                $this->profileRepository->createProfile($user, $data);
                $this->roleRepository->assignRole($user, $data['role']);
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * Menjalankan proses pembaruan data user, role dan profile terkait
     * Menjalankan event-listener untuk log perubahan data sensitif user
     * @param int $id id user dibutuhkan
     * @param array $data data-data user, role dan profile yang ingin diperbarui
     * @return void
     */
    public function updateUser(int $id, array $data)
    {
        try {
            DB::transaction(function () use ($id, $data) {
                $user = $this->userRepository->find($id);
                $oldEmail = $user->email;
                $user = $this->userRepository->updateUser($user, $data);
                $this->profileRepository->updateProfile($user, $data);
                $this->roleRepository->syncRole($user, $data['role']);
    
                $changes = [];
    
                if (!empty($data['password'])) {
                    $changes['password'] = 'updated';
                }
    
                if ($user->email !== $oldEmail) {
                    $changes['email'] = ['old' => $oldEmail, 'new' => $user->email];
                }
    
                // Jika ada perubahan sensitif setelah perubahan dicommit, buat event
                if (!empty($changes)) {
                    DB::afterCommit(function () use ($user, $changes) {
                        event(new UserSensitiveDataChanged($user, $changes, auth()->user()->id));
                    });
                }
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}