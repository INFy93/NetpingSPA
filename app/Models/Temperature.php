<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use MoonShine\Fields\Relationships\HasOneThrough;


class Temperature extends Model
{
    use HasFactory;

    protected $fillable = [
        'bdcom_id',
        'temperature'
    ];

    public function bdcom(): HasOne
    {
        return $this->hasOne(Bdcom::class, 'id', 'bdcom_id');
    }

    public function netping(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(
            Netping::class, //target model
            PivotNetpingBdcom::class, //through model
            'bdcom_id', //field in through model
            'id', //field in target model
            'bdcom_id', //local field
            'netping_id' //'through' field in PivotNetpingBdcom
        );
    }


    public function scopeWhereDateBetween($query,$fieldName,$fromDate,$todate)
    {
        return $query->where($fieldName,'>=',$fromDate)->where($fieldName,'<=',$todate);
    }
}
