<?php

use App\Models\Bdcom;
use App\Models\Temperature;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use function App\Helpers\getBdcomTemp;

Schedule::call(function ()
{
    $bdcoms = Bdcom::select('id', 'bdcom_ip', 'netping_id')->get();
    $temps = getBdcomTemp($bdcoms, 0);
    $tempsToDB = [];
    $i = 0;

    foreach ($temps as $t)
    {
        $tempsToDB[] = array(
            'bdcom_id' => $t['bdcom_id'],
            'temperature' => $t['bdcom1_temp'],
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        );

    }
    Temperature::insert($tempsToDB);
})->cron('04,09,14,19,24,29,34,39,44,49,54,59 * * * *');
