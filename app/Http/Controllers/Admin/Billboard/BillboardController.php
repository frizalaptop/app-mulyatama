<?php

namespace App\Http\Controllers\Admin\Billboard;

use App\Http\Controllers\Controller;
use App\Models\Billboard;
use App\Traits\HandlersException;

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
            return view('billboard.billboard-list', $data);
        } catch (\Throwable $e) {
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
            $billboards = Billboard::all();

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
                    'ukuran' => "$billboard->panjang x $billboard->lebar",
                    'unit' => $billboard->unit,
                    'koordinat' => "$billboard->latitude | $billboard->longitude",
                    'gambar' => $billboard->gambar,
                    'admin_buat' => $billboard->admin_buat,
                    'admin_ubah' => $billboard->admin_ubah,
                    'created_at' => $billboard->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $billboard->updated_at->format('Y-m-d H:i:s'),

                    'aksi' => '<div class="btn-group" role="group">
                        <button class="btn btn-sm btn-warning btn-upload" 
                            data-id="' . $billboard->id . '" 
                            data-toggle="modal" 
                            data-target="#modalUploadGambarBillboard">Upload</button>
                        <button class="btn btn-sm btn-dark btn-edit" 
                            data-id="' . $billboard->id . '" 
                            data-toggle="modal" 
                            data-target="#modalEditBillboard">Edit</button>
                        <button class="btn btn-sm btn-success btn-detail" 
                            data-id="' . $billboard->id . '">Detail</button>
                    </div>',
                ];
            });
            
            return response()->json(['data' => $data]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
