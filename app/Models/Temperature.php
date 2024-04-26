<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Temperature extends Model
{
    use HasFactory;

    protected $fillable = [
        'bdcom_id',
        'temperature'
    ];

    public function bdcom(): HasOne
    {
        return $this->hasOne(PivotNetpingBdcom::class, 'bdcom_id', 'bdcom_id');
    }

    public function scopeWhereDateBetween($query,$fieldName,$fromDate,$todate)
    {
        return $query->where($fieldName,'>=',$fromDate)->where($fieldName,'<=',$todate);
    }
}
