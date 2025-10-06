<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;



class AdminController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Mengambil ui daftar user 
     * Role admin
     * @return \Illuminate\Contracts\View\View
     */
    public function userList()
    {
        try {
            $data = $this->userService->getUserListViewData();
            return view('user.user-list', $data);
        } catch (\Throwable $e) {
            abort(500, 'Terjadi kesalahan saat memuat halaman User List.');
        }
    }

    /**
     * Mengambil data-tabel user
     * Role admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable()
    {
        $users = User::get();

        return response()->json([
            'data' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $user->active ? 
                        '<span class="lencana bg-primary">Aktif</span>' : 
                        '<span class="lencana bg-danger">Non aktif</span>',
                    'role' => $user->getRoleNames()->first(),
                    'last_login_at' => $user->last_login_at,
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
                    'action' => '
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-dark" data-id="'.$user->id.'"  data-target="#modalEditUser" data-toggle="modal">Edit</button>
                            <button class="btn btn-sm btn-success" data-id="'.$user->id.'">Profil</button>
                        </div>
                        ',
                ];
            }),
        ]);
    }

    /**
     * Mengambil statistik user
     * Role admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistic()
    {
        return response()->json([
            'user_total'    => User::get()->count(),
            'user_aktif'    => User::where('active', true)->get()->count(),
            'user_nonaktif' => User::where('active', false)->get()->count(),
            'user_admin'    => User::with('roles')->get()->filter(
                                    fn ($user) => $user->roles->where('name', 'Admin')->toArray()
                                )->count(),
            'user_klien'    => User::with('roles')->get()->filter(
                                    fn ($user) => $user->roles->where('name', 'Klien')->toArray()
                                )->count(),
        ]);
    }

    /**
     * Menambah data user
     * Role admin
     * @param \Illuminate\Http\Request $request [name, email, password, password_confirm]
     * @return \Illuminate\Http\JsonResponse
     */
    public function addUser(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)
                    ->uncompromised()
            ],

            'perusahaan'  => ['nullable', 'string', 'max:100'],
            'whatsapp'       => ['nullable', 'string', 'max:15'],
            'telegram' => ['nullable', 'string', 'max:15'],
            'alamat'  => ['nullable', 'string'], 

            'role'     => ['required', 'string', 'in:Admin,Klien']
        ]);

        DB::beginTransaction();

        try {
            // buat user
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'active'   => $request->aktifasi == 'Aktif',
            ]);

            // buat role user
            $user->assignRole($validated['role']);

            // buat profil user
            $profile = Profile::create([
                'user_id'   => $user->id,
                'perusahaan'  => $validated['perusahaan'] ?? null,
                'whatsapp'       => $validated['whatsapp'] ?? null,
                'telegram' => $validated['telegram'] ?? null,
                'alamat'  => $validated['alamat'] ?? null,
            ]); 

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User baru berhasil ditambahkan.',
                'user'    => $user,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan user: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mengambil data user berdasarkan id
     * Role admin
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserById($id){
        $user = User::with('profile')->findOrFail($id);
        $user->role = $user->getRoleNames()->first();

        return response()->json([
            'success' => true,
            'message' => 'User ditemukan.',
            'user' => $user,
        ]);
    }

    /**
     * Mengubah data user
     * Role admin
     * @param \Illuminate\Http\Request $request [name, email, password, password_confirm]
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|min:3',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)
                    ->uncompromised()
            ],
            'perusahaan' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:15',
            'telegram' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',

            'role' => ['required', 'string', 'in:Admin,Klien']
        ]);

        DB::beginTransaction();
        try {
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->active = $request->aktifasi === 'Aktif';

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            // sinkronisasi role dengan Spatie
            $user->syncRoles([$validated['role']]);
            
            $profileData = [
                'perusahaan'  => $validated['perusahaan'] ?? null,
                'whatsapp'       => $validated['whatsapp'] ?? null,
                'telegram' => $validated['telegram'] ?? null,
                'alamat'  => $validated['alamat'] ?? null,
            ];

            if ($user->profile) {
                $user->profile->update($profileData);
            } else {
                $user->profile()->create($profileData);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil diperbarui.',
                'user' => $user,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

}
