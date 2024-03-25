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
    foreach ($responses as $r)
    {
        if (!$r instanceof ConnectionException) {
            if (($i - 1) >= 0 && $bdcoms[$i]->netping_id == $bdcoms[$i-1]->netping_id)
            {
                $temps[$i]['bdcom1_temp'] = $temps[$i - 1]['bdcom1_temp'];
                $temps[$i]['bdcom2_temp'] = str_replace("\n","", $r->body());
                $temps[$i]['netping_id'] = $bdcoms[$i]->netping_id;
                unset($temps[$i - 1]);
            } else
            {
                $temps[$i]['bdcom1_temp'] = str_replace("\n","", $r->body());
                $temps[$i]['netping_id'] = $bdcoms[$i]->netping_id;
            }

            $i++;
    } else
        {
            if (($i - 1) >= 0 && $bdcoms[$i]->netping_id == $bdcoms[$i-1]->netping_id)
            {
                $temps[$i]['bdcom1_temp'] = '0';
                $temps[$i]['bdcom2_temp'] = '0';
                $temps[$i]['netping_id'] = $bdcoms[$i]->netping_id;
                unset($temps[$i - 1]);
            } else
            {
                $temps[$i]['bdcom1_temp'] = '0';
                $temps[$i]['netping_id'] = $bdcoms[$i]->netping_id;
            }

            $i++;
        }
    }

    return array_values($temps);
}
