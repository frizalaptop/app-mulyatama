<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $table = 'user_profile';

    protected $fillable = [
        'pf_iduser',
        'pf_company',
        'pf_wa',
        'pf_telegram',
        'pf_address',
        'pf_photo',
    ];

    // relasi balik ke user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pf_iduser', 'id');
    }
}
