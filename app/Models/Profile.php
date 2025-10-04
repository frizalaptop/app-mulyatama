<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $table = 'user_profile';

    protected $fillable = [
        'user_id',
        'perusahaan',
        'whatsapp',
        'telegram',
        'alamat',
        'photo',
    ];

    // relasi balik ke user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
