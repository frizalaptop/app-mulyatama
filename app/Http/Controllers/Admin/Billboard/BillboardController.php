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

            $query = Billboard::query();

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

            $recordsTotal = Billboard::count();
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
                    'aktif' => $row->aktif,
                    'keterangan' => $row->keterangan,
                    'jenis' => $row->jenis,
                    'ukuran' => "$row->lebar x $row->panjang",
                    'unit' => $row->unit,
                    'koordinat' => "$row->latitude | $row->longitude",
                    'gambar' => $row->gambar,
                    'admin_buat' => $row->admin_buat,
                    'admin_ubah' => $row->admin_ubah,
                    'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
                    'aksi' =>   '<div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-dark btn-edit" 
                                        data-id="' . $row->id . '" 
                                        data-toggle="modal" 
                                        data-target="#modalEditBillboard">Edit</button>
                                    <button class="btn btn-sm btn-success btn-update" 
                                        data-id="' . $row->id . '" 
                                        data-toggle="modal" 
                                        data-target="#modalUpdateGambarBillboard">Upload Gambar</button>
                                </div>',
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
