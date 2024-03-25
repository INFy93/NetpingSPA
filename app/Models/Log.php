<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Log extends Model
{
    use HasFactory;

    public function netping(): BelongsTo
    {
        return $this->belongsTo(Netping::class, 'id', 'netping_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}


