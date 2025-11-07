<?php

namespace App\Http\Controllers\Klien\Billboard;

use App\Http\Controllers\Controller;
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
    public function tabel (Request $request)
    {
        try {
            // Kolom yang memiliki fitur pengurutan
            $columns = [
                0 => 'id',
                1 => 'judul',
                2 => 'area',
                3 => 'lokasi',
            ];

            $draw   = $request->get('draw');
            $start  = $request->get('start', 0);
            $length = $request->get('length', 10);
            $search = $request->input('search.value');
            $order  = $request->input('order')[0] ?? ['column' => 1, 'dir' => 'asc'];
            $customFilter = $request->input('columns', []);

            $orderColumn = $columns[$order['column']];
            $orderDir = $order['dir'];

            $query = Billboard::where('aktif', 1);

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('area', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
                });
            }

            foreach ($customFilter as $col) {
                $colName = $col['data'] ?? null;
                $colSearch = $col['search']['value'] ?? null;

                if ($colName && $colSearch !== null && $colSearch !== '') {
                    $query->where($colName, 'like', "%{$colSearch}%");
                }
            }

            $recordsTotal = Billboard::where('aktif', 1)->count();
            $recordsFiltered = $query->count();

            $data = $query
                ->orderBy($orderColumn, $orderDir)
                ->offset($start)
                ->limit($length)
                ->get();

            $data = $data->map(function ($row) {
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

            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }
}
