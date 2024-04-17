<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Temperature extends Model
{
    use HasFactory;

    protected $fillable = [
        'bdcom_id',
        'temperature'
    ];

    public function bdcom(): BelongsTo
    {
        return $this->belongsTo(Bdcom::class);
    }
}
