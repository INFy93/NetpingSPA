<?php

namespace App\Services;

use App\Models\Bdcom;
use App\Models\MonthTemperatures;
use App\Models\Temperature;
use App\Models\WeeksTemperatures;
use App\Models\YearTemperatures;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TemperatureService
{
    public function makeTemperaturesArray($collection = null, $group = 1): array
    {
        $temperatures = $collection->toArray();
        $grouped = [];

        //dd($temperatures);

        if ($group == 1) {
            $grouped = array_group_by($temperatures, function ($row) {
                return $row['netping'] != null ? $row['netping']['id'] :  'no_netping';
            });
            ksort($grouped);
        } else {
            usort($temperatures['data'], function ($a, $b) {
                return ($a['bdcom_id'] - $b['bdcom_id']);
            });
            $grouped = $temperatures;
        }

        //dd($grouped);

        $data = [];
        if ($group == 1) {
            foreach ($grouped as $key => $value) {
                if ($key != 'no_netping')
                    $data[] = $value;
            }

            $result = array_values($data);
        } else
        {
            usort($temperatures['data'], function ($a, $b) {
                return ($a['bdcom_id'] - $b['bdcom_id']);
            });
            $result = $temperatures;
        }

        return $result;
    }

    public function formatDateRange($period): array
    {
        return match ($period) {
            'daily' => [
                'dateRange' =>Carbon::now()->subHours(24)->format('Y-m-d H:i:s'),
                'format' => "%Y-%m-%d %H:%i:%s",
            ],
            'weekly' => [
                'dateRange' => Carbon::now()->subDays(7)->format('Y-m-d H:i'),
                'format' => "%Y-%m-%d %H:%i",
            ],
            'monthly' => [
                'dateRange' => Carbon::now()->subDays(30),
                'format' => "%Y-%m-%d %H:%i",
            ],
            'year' => [
                'dateRange' => Carbon::now()->subDays(365),
                'format' => "%Y-%m-%d",
            ],
        };
    }

    public function fillAvgTable($multiper = 30): array
    {
        $bdcom_count = Bdcom::select('id')->count();
        $arrayToDB = [];
        $range = Carbon::now()->subMinutes($multiper);

        $data = Temperature::select(DB::raw('from_unixtime(CEIL(unix_timestamp(created_at) / (60*' . $multiper . ')) * 60 * ' . $multiper . ') as date, CEIL(AVG(temperature)) as temp'), 'bdcom_id', 'created_at')
            ->where('temperature', '!=', 0)
            ->whereDateBetween('created_at', $range, Carbon::now())
            ->groupBy('bdcom_id', 'date')
            ->orderBy('created_at', 'desc')
            ->limit($bdcom_count)
            ->get();
        foreach ($data as $d) {
            $arrayToDB[] = [
                'bdcom_id' => $d->bdcom_id,
                'temperature' => $d->temp,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ];
        }

        usort($arrayToDB, function ($a, $b) {
            return ($a['bdcom_id'] - $b['bdcom_id']);
        });

        if ($multiper == 30) {
            WeeksTemperatures::insert($arrayToDB);
        } else if ($multiper == 120) {
            MonthTemperatures::insert($arrayToDB);
        } else if ($multiper == 720) {
            YearTemperatures::insert($arrayToDB);
        }

        return $arrayToDB;
    }
}
