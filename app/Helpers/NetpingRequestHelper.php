<?php

namespace App\Helpers;

use GuzzleHttp\Promise\Utils;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Str;

function get_power_state($netping_ips)
{
    //состояние питания
    $p_state = [];
    $responses = Http::pool(function (Pool $pool) use ($netping_ips) {
        foreach ($netping_ips as $power) {
                $pool
                    ->withOptions([
                        'connect_timeout' => 0.1
                    ])
                    ->retry(3, 100, function ($exp) use ($pool) {
                        return $pool instanceof \Illuminate\Http\Client\ConnectionException;
                    })
                   ->get($power->power_state);
        }
    });
    foreach ($responses as $r) {
        if (!$r instanceof ConnectionException) {
            $p_state[] = explode(", ", $r);
        } else
        {
            $p_state[] = ['0', '0', '3'];
        }

    }
    return $p_state;
}

function get_door_state($netping_ips)
{
    //состояние двери
    $d_state = [];
    $responses = Http::pool(function (Pool $pool) use ($netping_ips) {
        $i = 0;
        foreach ($netping_ips as $door) {
            $pool->timeout(env("NETPING_TIMEOUT"))
                ->withOptions([
                    'connect_timeout' => 0.1
                ])
                ->retry(3, 100, function ($exp) use ($pool) {
                    return $pool instanceof \Illuminate\Http\Client\ConnectionException;
                })
                ->get($door->door_state);

        }
    });
    foreach ($responses as $r) {
        if (!$r instanceof ConnectionException) {
            $d_state[] = explode(", ", $r);
        } else
        {
            $d_state[] = ['0', '0', '3'];
        }

    }
    return $d_state;
}

function get_alarm_state($netping_ips)
{
    //состояние сирены
    $a_state = [];
    $responses = Http::pool(function (Pool $pool) use ($netping_ips) {
        foreach ($netping_ips as $alram) {
            $pool
                ->withOptions([
                    'connect_timeout' => 0.1
                ])
                ->retry(3, 100, function ($exp) use ($pool) {
                    return $pool instanceof \Illuminate\Http\Client\ConnectionException;
                })
                ->get($alram->alarm_state);
        }
    });
    foreach ($responses as $r) {
        if (!$r instanceof ConnectionException) {
            $a_state[] = explode(", ", $r);
        } else
        {
            $a_state[] = ['0', '0', '3'];
        }
    }
    return $a_state;
}

function get_netping_state($netping_ips)
{
    $s_state = [];
    $responses = Http::pool(function (Pool $pool) use ($netping_ips) {
        foreach ($netping_ips as $secure) {
            $pool
                ->withOptions([
                    'connect_timeout' => 0.1
                ])
                ->retry(3, 100, function ($exp) use ($pool) {
                    return $pool instanceof \Illuminate\Http\Client\ConnectionException;
                })
                ->get($secure->netping_state);

        }
    });
        foreach ($responses as $r) {
            if (!$r instanceof ConnectionException)
            {
                if (mb_detect_encoding($r) == "ASCII") {
                    $s_state[] = iconv("ascii", "utf-8", $r->body());
                } else {
                    $netping_state = iconv("windows-1251", "utf-8", $r->body());
                    $netping_state = Str::after($netping_state, "data=");
                    $netping_state = Str::remove(";", $netping_state);
                    $netping_state = explode(",", $netping_state);
                    $s_state[] = $netping_state[19];
                }
            } else {
                $s_state[] = '3';
            }

        }



    return $s_state;
}
