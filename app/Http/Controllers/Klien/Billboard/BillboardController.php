<?php

namespace App\Http\Controllers\Klien\Billboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\ControllerHelpers;
use App\Models\Billboard;
use App\Traits\HandlersException;
use Illuminate\Http\Request;
use Throwable;

class BillboardController extends Controller
{
    
    use HandlersException;

    /**
     * Mengambil view daftar billboard 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function index ()
    {
        try {
            $data = ['title' => 'Billboard List'];
            return view('klien.billboard.billboard-list', $data);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }
    
    /**
     * Mengambil data-tabel billboard
     * @return \Illuminate\Http\JsonResponse
     */
    public function tabel (Request $request, ControllerHelpers $helper)
    {
        try {
            $result = $helper->tabelHelper(
                request: $request,
                query: Billboard::where('aktif', 1),
                searchableColumns: ['judul', 'area', 'lokasi', 'jenis']
            );

            $result['data'] = collect($result['data'])->map(function ($row) {
                return [
                    'id' => $row->id,
                    'judul' => $row->judul,
                    'area' => $row->area,
                    'lokasi' => $row->lokasi,
                    'status' => $row->status,
                    'jenis' => $row->jenis,
                    'ukuran' => "$row->lebar x $row->panjang",
                    'unit' => $row->unit,
                    'koordinat' => "$row->latitude | $row->longitude",
                    'gambar' => $row->gambar,
                ];
            });

            return response()->json($result);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Mengambil opsi filter billboard
     * @return \Illuminate\Http\JsonResponse
     */
    public function opsiFilter()
    {
        return response()->json([
            'status' => Billboard::query()
                ->select('status')
                ->distinct()
                ->pluck('status')
                ->map(fn ($s) => [
                    'value' => $s,
                    'text' => $s == 1 ? 'Tersedia' : 'Tersewa',
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
    }
}
