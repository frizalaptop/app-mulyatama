<?php

namespace App\Http\Controllers\admin\billboard;

use App\Http\Controllers\Controller;
use App\Models\Billboard;
use App\Models\BillboardSewa;
use App\Models\User;
use App\Traits\HandlersException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class BillboardSewaController extends Controller
{
    
    use HandlersException;

    /**
     * Mengambil view daftar billboard 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function index ()
    {
        try {
            $data = ['title' => 'Billboard Sewa'];
            return view('admin.billboard.billboard-sewa', $data);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Menambah data sewa billboard
     * @param \Illuminate\Http\Request $request [penyewa_email, penyewa_name, billboard_id, billboard_judul, periode, tgl_awal]
     * @return \Illuminate\Http\JsonResponse
     */    
    public function simpan (Request $request)
    {
        try {
            $validated = $request->validate([
                'penyewa_email'    => 'required|email',
                'penyewa_name'     => 'required|string|max:100',
                'billboard_id'     => 'required|integer',
                'billboard_judul'  => 'required|string|max:255',
                'periode'          => 'required|integer|min:1',
                'tgl_awal'         => 'required|date',
            ]);

            DB::transaction(function () use ($validated) {

                $billboard = Billboard::findOrFail($validated['billboard_id']);

                if (!$billboard->status) {
                    throw ValidationException::withMessages([
                        'billboard_id' => 'Billboard masih dalam masa sewa.',
                    ]);
                }

                $user = User::where('email', $validated['penyewa_email'])
                            ->where('name', $validated['penyewa_name'])
                            ->first();

                if (!$user) {
                    throw ValidationException::withMessages([
                        'penyewa_email' => 'Data pengguna tidak ditemukan dengan email dan nama tersebut.',
                    ]);
                }

                if (!$user->hasRole('Klien')) {
                    throw ValidationException::withMessages([
                        'penyewa_email' => 'Pengguna bukan Klien yang sah untuk menyewa billboard.',
                    ]);
                }

                $tglAwal = \Carbon\Carbon::parse($validated['tgl_awal']);
                $tglAkhir = $tglAwal->copy()->addMonths((int) $validated['periode']);

                BillboardSewa::create([
                    'billboard_id' => $billboard->id,
                    'user_id'      => $user->id,
                    'periode'      => $validated['periode'],
                    'tgl_awal'     => $tglAwal->format('Y-m-d'),
                    'tgl_akhir'    => $tglAkhir->format('Y-m-d'),
                    'admin_buat'   => Auth::user()->name,
                    'admin_ubah'   => Auth::user()->name,
                ]);

                $billboard->update([
                    'status' => 0,
                ]);
            });
            return response()->json([
                'success' => true,
                'message' => 'Penyewaan Billboard baru berhasil ditambahkan.',
            ]);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }
    
}
