<?php

namespace App\Http\Controllers\Admin\Billboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddUpdateBillboardRequest;
use App\Models\Billboard;
use App\Traits\HandlersException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
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
            return view('admin.billboard.billboard-list', $data);
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

    /**
     * Mengambil data billboard berdasarkan id
     * @param mixed $id billboard id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getId ($id) 
    {
        try {
            $billboard = Billboard::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Billboard ditemukan.',
                'billboard' => $billboard,
            ]);
        } catch (Throwable $e) {
            return $this->handleException($e, 'Billboard tidak ditemukan');
        }
    }

    /**
     * Menambah data billboard
     * @param \Illuminate\Http\Request $request [judul, area, lokasi, jenis, lebar, panjang, unit, latitude, longitude, aktif, keterangan]
     * @return \Illuminate\Http\JsonResponse
     */    
    public function simpan (AddUpdateBillboardRequest $request)
    {
        try {
            $data = $request->validated();
            $user = Auth::user();
            
            Billboard::create([
                'judul'     => $data['judul'],
                'area'      => $data['area'],
                'lokasi'    => $data['lokasi'],
                'jenis'     => $data['jenis'],

                'lebar'     => $data['lebar'],
                'panjang'   => $data['panjang'],
                'unit'      => $data['unit'],

                'latitude'  => $data['latitude'],
                'longitude' => $data['longitude'],

                'aktif' => $data['aktif'] === 'Aktif',
                'keterangan' => $data['aktif'] === 'Aktif' ? null : $data['keterangan'],

                'admin_buat' => $user->name,
                'admin_ubah' => $user->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Billboard baru berhasil ditambahkan.',
            ]);

        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Mengubah data billboard
     * @param \Illuminate\Http\Request $request [judul, area, lokasi, jenis, lebar, panjang, unit, latitude, longitude, aktif, keterangan]
     * @param mixed $id billboard id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update (AddUpdateBillboardRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $billboard = Billboard::findOrFail($id);
            $user = Auth::user();

            $billboard->judul = $data['judul'];
            $billboard->area = $data['area'];
            $billboard->lokasi = $data['lokasi'];
            $billboard->jenis = $data['jenis'];
            $billboard->lebar = $data['lebar'];
            $billboard->panjang = $data['panjang'];
            $billboard->unit = $data['unit'];
            $billboard->latitude = $data['latitude'];
            $billboard->longitude = $data['longitude'];
            $billboard->aktif = $data['aktif'] === 'Aktif';
            $billboard->keterangan = $data['aktif'] === 'Aktif' ? null : $data['keterangan'];
            $billboard->admin_ubah = $user->name;

            $billboard->save();
            
            return response()->json([
                'success' => true,
                'message' => 'User berhasil diperbarui.',
            ]);
        } catch (Throwable $e) {
            return $this->handleException($e, 'User tidak ditemukan');
        }
    }

    /**
     * Mengubah gambar billboard
     * @param \Illuminate\Http\Request $request [gambar]
     * @param mixed $id billboard id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function updateGambar(Request $request, $id)
    {
        $request->validate([
            'gambar' => 'required|image|max:2048|mimes:jpg,jpeg,png',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $billboard = Billboard::findOrFail($id);
                $user = Auth::user();

                $file = $request->file('gambar');
                $fileSizeMB = $file->getSize() / 1024 / 1024;
                $quality = $fileSizeMB >= 0.8 ? 80 : 100;

                $namaFile = "{$billboard->id}_billboard";
                $namaFileUtama = "{$namaFile}.webp";
                $namaFileThumb = "thum_{$namaFile}.webp";

                $manager = new ImageManager(new Driver());

                $originalImage = $manager->read($file)->toWebp($quality); // versi asli

                // Buat versi thumbnail (crop tengah 100x100)
                $thumbnailImage = $manager->read($file)
                    ->cover(100, 100)
                    ->toWebp(90);

                // Update nama file utama di database
                $billboard->gambar = $namaFileUtama;
                $billboard->admin_ubah = $user->name;
                $billboard->touch(); // update timestamp
                $billboard->save();

                // Simpan setelah commit ke storage
                DB::afterCommit(function () use ($namaFileUtama, $namaFileThumb, $originalImage, $thumbnailImage) {
                    // simpan versi asli
                    Storage::disk('public')->put("upload/billboard/{$namaFileUtama}", $originalImage);
                    // simpan versi thumbnail
                    Storage::disk('public')->put("upload/billboard/{$namaFileThumb}", $thumbnailImage);
                });
            });

            return redirect()->back()->with('success', 'Gambar dan thumbnail berhasil diperbarui.');
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

}
