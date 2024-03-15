<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Str;

function get_power_state($netping_ip) { //состояние питания
        try
        {
            $raw_power_state = Http::timeout(env('NETPING_TIMEOUT'))->get($netping_ip);
        }
        catch (ConnectionException $exp)
        {
            return '3';
        }

        $power_state = explode(", ", $raw_power_state);

        return $power_state[2];

    }

function get_door_state($netping_ip) { //состояние двери

        try
        {
            $raw_door_state = Http::timeout(env('NETPING_TIMEOUT'))->get($netping_ip);
        }
        catch (ConnectionException $exp)
        {
            return '3';
        }
        // dd($raw_door_state);
        $door_state = explode(", ", $raw_door_state);

        return $door_state[2];

    }

function get_alarm_state($netping_ip) { //состояние сирены
        try
        {
            $raw_alarm_state = Http::timeout(env('NETPING_TIMEOUT'))->get($netping_ip);
        }
        catch (ConnectionException $exp)
        {
            return '3';
        }

        $alarm_state = explode(", ", $raw_alarm_state);

        return $alarm_state[2];
    }

function get_netping_state($netping_ip, $revision)
    {
        try
        {
            $raw_netping_state = Http::timeout(env('NETPING_TIMEOUT'))->get($netping_ip);
        }
        catch (ConnectionException $exp)
        {
            return '3';
        }

        if ($revision == 2)
        {
            $netping_state = iconv("windows-1251","utf-8",$raw_netping_state->body());
            $netping_state = Str::after($netping_state, 'data=');
            $netping_state = Str::remove(';', $netping_state);
            $netping_state = explode(',', $netping_state);

            return $netping_state[19];

        } else if ($revision == 4)
        {
            return explode(",", $raw_netping_state);
        }

    }
