<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bdcom extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
      'netping_id'
    ];

    public function netping(): BelongsToMany
    {
        return $this->belongsToMany(Netping::class, 'netping_bdcom');
    }


}
