<?php

namespace App\Http\Controllers;

use App\Events\UserSensitiveDataChanged;
use App\Http\Requests\UpdateProfilAkunRequest;
use App\Http\Requests\UpdateProfilInfoRequest;
use App\Models\User;
use App\Traits\HandlersException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{

    use HandlersException;

    /**
     * Mengembalikan view user profil
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = ['title' => 'Profil'];
            return view('user.user-profile', $data);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Mengubah data akun profil
     * @param \App\Http\Requests\UpdateProfilAkunRequest $request
     * @param mixed $userId user id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function updateAkun(UpdateProfilAkunRequest $request, $userId)
    { 
        try {
            $data = $request->validated();

            DB::transaction(function () use ($data, $userId) {
                $user = User::findOrFail($userId);
                $oldEmail = $user->email;

                $user->name = $data['name'];
                $user->email = $data['email'];

                if (!empty($data['aktivasi'])) {
                    $user->active = $data['aktivasi'] === 'Aktif';
                }

                $changes = [];

                if (!empty($data['password'])) {
                    $user->password = Hash::make($data['password']);
                    $changes['password'] = 'updated';
                }

                $user->save();

                if ($user->email !== $oldEmail) {
                    $changes['email'] = [
                        'old' => $oldEmail,
                        'new' => $user->email,
                    ];
                }

                if (!empty($changes)) {
                    event(new UserSensitiveDataChanged($user, $changes, $user->id));
                }
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'User tidak ditemukan');
        }
    }

    /**
     * Mengubah data info profil
     * @param \App\Http\Requests\UpdateProfilInfoRequest $request
     * @param mixed $userId user id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function updateInfo(UpdateProfilInfoRequest $request, $userId)
    {
        try {
            $data = $request->validated();
            $user = User::findOrFail($userId);

            $profileData = [
                    'perusahaan' => $data['perusahaan'] ?? null,
                    'whatsapp'   => $data['whatsapp'] ?? null,
                    'telegram'   => $data['telegram'] ?? null,
                    'alamat'     => $data['alamat'] ?? null,
                ];
            $user->profil()->update($profileData);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'User tidak ditemukan');
        }
    }
}
