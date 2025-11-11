<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillboardSewa extends Model
{
    protected $table = 'billboard_sewa';

    protected $fillable = [
        'billboard_id',
        'user_id',
        'periode',
        'tgl_awal',
        'tgl_akhir',
        'admin_buat',
        'admin_ubah',
    ];
}
