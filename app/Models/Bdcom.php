<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bdcom extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
      'netping_id'
    ];

    public function netping(): BelongsTo
    {
        return $this->belongsTo(Netping::class);
    }
}
