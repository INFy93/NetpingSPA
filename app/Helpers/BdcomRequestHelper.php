<?php

namespace App\Helpers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

function getBdcomTemp($bdcoms): array
{
    $responses = Http::pool(function (Pool $pool) use ($bdcoms) {
        foreach ($bdcoms as $bdcom) {
            $pool
                ->withOptions([
                    'connect_timeout' => 3
                ])
                ->get(env('BDCOM_TEMP_LINK') . $bdcom->bdcom_ip);
        }
    });
    $temps = [];
    $i = 0;
    foreach ($responses as $r) {
            if (!$r instanceof ConnectionException) {
                $temps[$i]['bdcom1_temp'] = intval(str_replace("\n", "", $r->body()));
                $temps[$i]['bdcom_id'] = $bdcoms[$i]->id;
            } else {
                $temps[$i]['bdcom1_temp'] = 0;
                $temps[$i]['bdcom_id'] = $bdcoms[$i]->id;
            }
            $i++;
        }


    return array_values($temps);
}
