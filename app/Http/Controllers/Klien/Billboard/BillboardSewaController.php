<?php

namespace App\Http\Controllers\Klien\Billboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\ControllerHelpers;
use App\Models\Billboard;
use App\Models\BillboardSewa;
use App\Models\User;
use App\Traits\HandlersException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $data = ['title' => 'Billboard Saya'];
            return view('klien.billboard.billboard-sewa', $data);
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
            $userId = Auth::user()->id;

            $result = $helper->tabelHelper(
                request: $request,
                query: BillboardSewa::query()
                    ->leftJoin('billboards', 'billboard_sewa.billboard_id', '=', 'billboards.id')
                    ->select([
                        'billboard_sewa.id',
                        'billboard_sewa.periode',
                        'billboard_sewa.tgl_awal',
                        'billboard_sewa.tgl_akhir',
                        'billboards.judul',
                        'billboards.lokasi',
                        'billboards.jenis',
                    ])
                    ->where('user_id', $userId),
                searchableColumns: [
                    'billboards.judul',
                    'billboards.lokasi',
                    'billboards.jenis',
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
                    'judul' => $row->judul,
                    'lokasi' => $row->lokasi,
                    'jenis' => $row->jenis,
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
            $userId = Auth::user()->id;

            return response()->json([
            'periode' => BillboardSewa::query()
                ->select('periode')
                ->where('user_id', $userId)
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
}
