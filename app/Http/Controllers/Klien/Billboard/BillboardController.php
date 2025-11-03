<?php

namespace App\Http\Controllers\Klien\Billboard;

use App\Http\Controllers\Controller;
use App\Models\Billboard;
use App\Traits\HandlersException;
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
    public function tabel ()
    {
        try {
            $billboards = Billboard::where('aktif', true)->get();
            $data = $billboards->map(function ($billboard) {
                return [
                    'id' => $billboard->id,
                    'judul' => $billboard->judul,
                    'area' => $billboard->area,
                    'lokasi' => $billboard->lokasi,
                    'status' => $billboard->status,
                    'aktif' => $billboard->aktif,
                    'keterangan' => $billboard->keterangan,
                    'jenis' => $billboard->jenis,
                    'ukuran' => "$billboard->lebar x $billboard->panjang",
                    'unit' => $billboard->unit,
                    'koordinat' => "$billboard->latitude | $billboard->longitude",
                    'gambar' => $billboard->gambar,
                    'admin_buat' => $billboard->admin_buat,
                    'admin_ubah' => $billboard->admin_ubah,
                    'created_at' => $billboard->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $billboard->updated_at->format('Y-m-d H:i:s'),

                    'aksi' => '<div class="btn-group" role="group">
                        
                        <button class="btn btn-sm btn-dark btn-edit" 
                            data-id="' . $billboard->id . '" 
                            data-toggle="modal" 
                            data-target="#modalEditBillboard">Edit</button>
                        <button class="btn btn-sm btn-success btn-update" 
                            data-id="' . $billboard->id . '" 
                            data-toggle="modal" 
                            data-target="#modalUpdateGambarBillboard">Upload Gambar</button>
                    </div>',
                ];
            });
            
            return response()->json(['data' => $data]);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }
}
