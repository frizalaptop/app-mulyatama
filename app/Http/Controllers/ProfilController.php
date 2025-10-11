<?php

namespace App\Http\Controllers;

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

    public function updateProfile(Request $request)
    {
        /**
         * Catatan Review:
         *
         * ✅ Hal yang sudah baik:
         * - Validasi input sudah cukup ketat => Catatan Validasi.
         * - Penggunaan `auth()->user()` sudah tepat.
         * - Password di-hash dengan aman menggunakan `Hash::make()`.
         * - Feedback ke user via `with('success', ...)` membantu UX.
         *
         * ⚠️ Koreksi dan Saran Perbaikan:
         * 1. Hapus `use` yang tidak digunakan (contoh: `DataTables` tidak dipakai di controller ini).
         * ✅2. Validasi email harus tetap unik, tapi pengecualian untuk user saat ini harus jelas:
         *    Gunakan: `Rule::unique('users')->ignore($user->id)` agar lebih aman dan terbaca.
         * ✅3. Validasi sebaiknya dipindahkan ke `FormRequest` agar controller tetap bersih dan mudah diuji.
         * ✅4. Gunakan `$request->filled('password')` sebelum mengubah password, untuk menghindari overwrite kosong.
         * 5. Tambahkan pengecekan `save()` agar bisa menangani kegagalan simpan:
         *    ```php
         *    if (!$user->save()) {
         *        return back()->withErrors(['msg' => 'Gagal memperbarui profil.']);
         *    }
         *    ```
         * 6. Pertimbangkan untuk memisahkan logika update ke Service atau Action agar lebih modular.
         * ✅7. Tambahkan logging atau event untuk perubahan data sensitif seperti email dan password.
         * 8. Gunakan komentar yang menjelaskan tujuan setiap blok kode agar mudah dipahami oleh developer lain.
         */

        /**
         * 📚 Catatan Validasi:
         *
         * Laravel menyediakan dua cara utama untuk menulis validasi:
         *
         * 1. ✅ **Validasi langsung di dalam controller**
         *    Gunakan jika:
         *    - Validasi hanya dipakai di satu tempat.
         *    - Aturan sederhana dan tidak berulang di controller lain.
         *    - Contoh:
         *      ```php
         *      $request->validate([
         *          'name' => 'required|string|max:255',
         *          'email' => 'required|email|unique:users,email,' . $user->id,
         *      ]);
         *      ```
         *
         * 2. ✅ **Validasi menggunakan FormRequest**
         *    Gunakan jika:
         *    - Validasi dipakai di banyak controller atau method.
         *    - Aturan kompleks dan perlu diuji secara terpisah.
         *    - Ingin menjaga prinsip SRP (Single Responsibility Principle).
         *    - Contoh:
         *      ```php
         *      public function update(UpdateUserRequest $request)
         *      ```
         *      Di dalam `UpdateUserRequest.php`:
         *      ```php
         *      public function rules() {
         *          return [
         *              'name' => 'required|string|max:255',
         *              'email' => ['required', 'email', Rule::unique('users')->ignore($this->user()->id)],
         *          ];
         *      }
         *      ```
         *
         * ✨ Tips Tambahan:
         * - Untuk DRY, kamu bisa buat method static di Model seperti `User::validationRules($id)` agar bisa dipakai lintas FormRequest.
         * - Validasi yang sangat spesifik bisa dibuat sebagai `Custom Rule` agar modular dan mudah diuji.
         * - Jangan lupa dokumentasikan aturan validasi agar mudah dipahami oleh tim dan generasi berikutnya.
         */

        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
