<?php

namespace App\Http\Controllers\Admin\Billboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\ControllerHelpers;
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
     * Mengambil data-tabel billboard
     * @param \Illuminate\Http\Request $request instance http request
     * @param \App\Http\Controllers\Helpers\ControllerHelpers $helper instance helper controller
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function tabel (Request $request, ControllerHelpers $helper)
    {
        try {
            $result = $helper->tabelHelper(
                request: $request,
                query: BillboardSewa::query()
                    ->leftJoin('billboards', 'billboard_sewa.billboard_id', '=', 'billboards.id')
                    ->leftJoin('users', 'billboard_sewa.user_id', '=', 'users.id')
                    ->select([
                        'billboard_sewa.id',
                        'billboard_sewa.periode',
                        'billboard_sewa.tgl_awal',
                        'billboard_sewa.tgl_akhir',
                        'billboard_sewa.admin_buat',
                        'billboard_sewa.admin_ubah',
                        'billboard_sewa.created_at',
                        'billboards.judul',
                        'billboards.lokasi',
                        'billboards.jenis',
                        'users.email',
                    ]),
                searchableColumns: [
                    'billboards.judul',
                    'billboards.lokasi',
                    'billboards.jenis',
                    'users.email',  
                ],
                customColumnFilter: function ($query, $colName, $colSearch) {
                    if ($colName === 'periode') {
                        $query->where('billboard_sewa.periode', $colSearch);
                        return true;
                    }
                    return false;
                }
            );

            $result['data'] = collect($result['data'])->map(function ($row) {
                return [
                    'id' => $row->id,
                    'periode' => $row->periode,
                    'tgl_awal' => $row->tgl_awal,
                    'tgl_akhir' => $row->tgl_akhir,
                    'admin_buat' => $row->admin_buat,
                    'admin_ubah' => $row->admin_ubah,
                    'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                    'judul' => $row->judul,
                    'lokasi' => $row->lokasi,
                    'jenis' => $row->jenis,
                    'email' => $row->email,
                    'countdown' => $row->tgl_akhir,
                ];
            });

            return response()->json($result);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Mengambil opsi filter billboard sewa
     * @return \Illuminate\Http\JsonResponse
     */
    public function opsiFilter()
    {
        try {
            return response()->json([
            'periode' => BillboardSewa::query()
                ->select('periode')
                ->distinct()
                ->pluck('periode')
                ->map(fn ($p) => [
                    'value' => $p,
                    'text' => $p.' bulan',
                ])
                ->values(),

            'jenis' => Billboard::query()
                ->select('jenis')
                ->distinct()
                ->pluck('jenis')
                ->map(fn ($j) => [
                    'value' => $j,
                    'text' => ucwords($j),
                ])
                ->values(),
        ]);
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
                'tgl_awal'         => 'required|date|after_or_equal:today',
            ]);

            DB::transaction(function () use ($validated) {

                $billboard = Billboard::findOrFail($validated['billboard_id']);

                if (!$billboard->status) {
                    throw ValidationException::withMessages([
                        'billboard_id' => 'Billboard masih dalam masa penyewaan.',
                    ]);
                }

                if (!$billboard->aktif) {
                    throw ValidationException::withMessages([
                        'billboard_id' => 'Billboard masih dalam masa pemeliharaan.',
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
