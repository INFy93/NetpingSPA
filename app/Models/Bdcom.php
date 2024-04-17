<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function temperatures(): HasMany
    {
        return $this->hasMany(Temperature::class);
    }


}
