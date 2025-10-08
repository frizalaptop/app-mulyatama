<?php

namespace App\Services;

use App\Events\UserSensitiveDataChanged;
use App\Repositories\ProfileRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Throwable;


class UserService
{

    protected $userRepository;
    protected $profileRepository;
    protected $roleRepository;

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
    
    public function getUserListView()
    {
        try {
            return ['title' => 'User List'];
        } catch (Throwable $e) {
            throw $e;
        }
    }

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
    
    public function updateUser(int $id, array $data)
    {
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

            // Jika ada perubahan sensitif, buat event
            if (!empty($changes)) {
                DB::afterCommit(function () use ($user, $changes) {
                    event(new UserSensitiveDataChanged($user, $changes, auth()->user()->id));
                });
            }
        });
    }
}