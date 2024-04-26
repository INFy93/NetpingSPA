<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeksTemperatures extends Model
{
    use HasFactory;

    public function scopeWhereDateBetween($query,$fieldName,$fromDate,$todate)
    {
        return $query->where($fieldName,'>=',$fromDate)->where($fieldName,'<=',$todate);
    }
}
