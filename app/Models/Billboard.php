<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billboard extends Model
{
    use HasFactory;
    
    // tabel database
    protected $table = 'billboards';

    // kolom yang dapat diisi
    protected $fillable = [
        'judul',
        'area',
        'lokasi',
        'status',
        'aktif',
        'keterangan',
        'jenis',
        'lebar',
        'panjang',
        'unit',
        'latitude',
        'longitude',
        'gambar',
        'admin_buat',
        'admin_ubah',
    ];
}
