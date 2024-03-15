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
        $i = 0;
        foreach ($netping_ips as $power) {
            try {
                $pool
                    ->timeout(env("NETPING_TIMEOUT"))
                    ->get($power->power_state);
            } catch (ConnectionException $exp) {
                $p_state[$i][2] = "3";
            }
        }
    });
    foreach ($responses as $r) {
        $p_state[] = explode(", ", $r);
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
            try {
                $pool->timeout(env("NETPING_TIMEOUT"))->get($door->door_state);
            } catch (ConnectionException $exp) {
                $d_state[$i][2] = "3";
            }
        }
    });
    foreach ($responses as $r) {
        $d_state[] = explode(", ", $r);
    }
    return $d_state;
}

function get_alarm_state($netping_ips)
{
    //состояние сирены
    $a_state = [];
    $responses = Http::pool(function (Pool $pool) use ($netping_ips) {
        $i = 0;
        foreach ($netping_ips as $alram) {
            try {
                $pool
                    ->timeout(env("NETPING_TIMEOUT"))
                    ->get($alram->alarm_state);
            } catch (ConnectionException $exp) {
                $a_state[$i][2] = "3";
            }
        }
    });
    foreach ($responses as $r) {
        $a_state[] = explode(", ", $r);
    }
    return $a_state;
}

function get_netping_state($netping_ips)
{
    $s_state = [];
    $responses = Http::pool(function (Pool $pool) use ($netping_ips) {
        $i = 0;
        foreach ($netping_ips as $secure) {
            try {
                $pool
                    ->timeout(env("NETPING_TIMEOUT"))
                    ->get($secure->netping_state);
            } catch (ConnectionException $exp) {
                $a_state[$i][2] = "3";
            }
        }
    });
    foreach ($responses as $r) {
        if (mb_detect_encoding($r) == "ASCII") {
            $s_state[] = iconv("ascii", "utf-8", $r->body());
        } else {
            $netping_state = iconv("windows-1251", "utf-8", $r->body());
            $netping_state = Str::after($netping_state, "data=");
            $netping_state = Str::remove(";", $netping_state);
            $netping_state = explode(",", $netping_state);
            $netping_string = $netping_state[19];
            $netping_string = explode(":", $netping_string);
            $s_state[] = $netping_string[1];
        }
    }

    return $s_state;
}
