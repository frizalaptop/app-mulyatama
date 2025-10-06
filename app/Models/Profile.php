<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    // tabel database
    protected $table = 'user_profile';

    // kolom yang dapat diisi
    protected $fillable = [
        'user_id',
        'perusahaan',
        'whatsapp',
        'telegram',
        'alamat',
        'foto',
    ];

    // relasi balik ke user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
