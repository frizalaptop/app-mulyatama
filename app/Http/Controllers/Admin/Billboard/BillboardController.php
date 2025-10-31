<?php

namespace App\Http\Controllers\Admin\Billboard;

use App\Http\Controllers\Controller;
use App\Models\Billboard;
use App\Traits\HandlersException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function updateGambar (Request $request, $id)
    {
        $request->validate([
            'gambar' => 'required|image|max:2048|mimes:jpg,jpeg,png',
        ]);

        try {

            DB::transaction( function () use ($request, $id) {

                $billboard = Billboard::findOrFail($id);
        
                $manager = new ImageManager(new Driver());
        
                $file = $request->file('gambar');
        
                $namaFile = "{$billboard->id}_billboard.webp";
        
                $image = $manager->read($file)
                    ->toWebp(75); // 0–100 (semakin kecil = semakin terkompres)
        
                $billboard->gambar = $namaFile;
                $billboard->save();

                DB::afterCommit( function () use ($namaFile, $image) {
                    $path = "upload/billboard/{$namaFile}";
                    Storage::disk('public')->put($path, $image);
                });
            });

            return redirect()->back()->with('success', 'Gambar berhasil diperbarui.');

        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
