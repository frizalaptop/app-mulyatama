<?php
namespace App\Services;

use App\Events\UserSensitiveDataChanged;
use App\Repositories\ProfileRepository;
use App\Repositories\UserRepository;
use Throwable;

class ProfilService 
{
    protected $userRepository;
    protected $profilRepository;

    // Inject dependency repository yang dibutuhkan dalam service
    public function __construct(UserRepository $userRepository, ProfileRepository $profilRepository)
    {
        $this->userRepository = $userRepository;
        $this->profilRepository = $profilRepository;
    }

    /**
     * Mengembalikan data title
     * @return array{title: string}
     */
    public function getProfilViewData()
    {
        try {
            return ['title' => 'Profil'];
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * Menjalankan proses pembaruan data akun profil 
     * @param array $data data-data akun profil yang ingin diperbarui (name, email, password)
     * @param int $userId id user pemilik profil
     * @return void
     */
    public function updateProfilAkun(array $data, int $userId) {
        try {
            $user = $this->userRepository->find($userId);
            $oldEmail = $user->email;
            $user = $this->userRepository->updateUser($user, $data);

            $changes = [];
            
            if (!empty($data['password'])) {
                $changes['password'] = 'updated';
            }

            if ($user->email !== $oldEmail) {
                $changes['email'] = ['old' => $oldEmail, 'new' => $user->email];
            }

            if (!empty($changes)) {
                event(new UserSensitiveDataChanged($user, $changes, $user->id));
            }
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * Menjalankan proses pembaruan data info profil 
     * @param array $data data-data info profil yang ingin diperbarui (perusahaan, whatsapp, telegram, alamat)
     * @param int $userId id user pemilik profil
     * @return void
     */
    public function updateProfilInfo(array $data, int $userId) {
        try {
            $user = $this->userRepository->find($userId);
            $this->profilRepository->updateProfile($user, $data);
        } catch (Throwable $e) {
            throw $e;
        }
    }
}