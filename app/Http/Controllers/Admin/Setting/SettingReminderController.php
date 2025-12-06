<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\ControllerHelpers;
use App\Traits\HandlersException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class SettingReminderController extends Controller
{

    use HandlersException;

    /**
     * Mengambil view remender setting 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $data = ['title' => 'Setting Reminder'];
            return view('admin.setting.setting-reminder', $data);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Mengambil data-tabel remender setting
     * @param \Illuminate\Http\Request $request instance http request
     * @param \App\Http\Controllers\Helpers\ControllerHelpers $helper instance helper controller
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function tabel (Request $request, ControllerHelpers $helper)
    {
        try {
            $query = DB::table('settings')
                ->where('group', 'reminder');
            $result = $helper->tabelHelper(
                request: $request,
                query: $query,
                searchableColumns: ['name', 'payload', 'admin_buat', 'admin_ubah']
            );
            $result['data'] = collect($result['data'])->map(function ($row) {
                return [
                    'id' => $row->id,
                    'name' => $row->name,
                    'payload' => implode(', ', json_decode($row->payload, true) ?? []),
                    'admin_buat' => $row->admin_buat,
                    'admin_ubah' => $row->admin_ubah,
                    'created_at' => \Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => \Carbon\Carbon::parse($row->updated_at)->format('Y-m-d H:i:s'),
                    'aksi' =>   '<div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-dark btn-edit" 
                                        data-id="' . $row->id . '" 
                                        data-toggle="modal" 
                                        data-target="#modalEditReminder">Edit</button>  
                                </div>',
                ];
            });

            return response()->json($result);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Mengambil data remnder setting berdasarkan id
     * @param mixed $id settings id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getId ($id) 
    {
        try {
            $reminder = DB::table('settings')
                 ->where('group', 'reminder')
                 ->where('id', $id)->first();
            $reminder->payload = implode(', ', json_decode($reminder->payload, true) ?? []);
            return response()->json([
                'success' => true,
                'message' => 'Reminder ditemukan.',
                'reminder' => $reminder,
            ]);
        } catch (Throwable $e) {
            return $this->handleException($e, 'Reminder tidak ditemukan');
        }
    }

    /**
     * Mengambil opsi filter reminder
     * @return \Illuminate\Http\JsonResponse
     */
    public function opsiFilter()
    {
        try {
            return response()->json([
            'name' => DB::table('settings')
                ->select('name')
                ->distinct()
                ->pluck('name')
                ->map(fn ($n) => [
                    'value' => $n,
                    'text' => ucwords($n),
                ])
                ->values(),
        ]);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Mengubah data remender setting
     * @param \Illuminate\Http\Request $request [payload]
     * @param mixed $id settings id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'payload' => ['required', 'regex:/^[0-9,\s]+$/'],
        ]);

        try {
            $payload = array_map(fn($v) => (int) trim($v), explode(',', $validated['payload'])); 

            $user = Auth::user();

            DB::table('settings')
                ->where('group', 'reminder')
                ->where('id', $id)
                ->update([
                    'payload' => json_encode($payload),
                    'admin_ubah' => $user->name,
                    'updated_at' => now(),
                ]);
            return response()->json([
                'success' => true,
                'message' => 'Reminder berhasil diperbarui.',
            ]);
        } catch (Throwable $th) {
            return $this->handleException($th, 'Gagal memperbarui reminder.');
        }
    }
}
