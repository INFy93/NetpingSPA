<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Netping extends Model
{
    use HasFactory;
    protected $table = 'netping';

    public function bdcoms(): HasMany
    {
        return $this->hasMany(Bdcom::class, 'id', 'bdcom_id');
    }
}
