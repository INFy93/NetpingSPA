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

        if ($group == 1) {
            $grouped = array_group_by($temperatures, function ($row) {
                return $row['bdcom'] != null ? $row['bdcom']['netping_id'] : 'no_netping';
            });
            ksort($grouped);
        } else {
            usort($temperatures, function ($a, $b) {
                return ($a['bdcom_id'] - $b['bdcom_id']);
            });
            $grouped = $temperatures;
        }

        $data = [];

        foreach ($grouped as $key => $value) {
            if ($group = 1) {
                if ($key != 'no_netping') {
                    $data[] = $value;
                }
            } else {
                $data[] = $value;
            }

        }
        if ($group == 0) {
            usort($data, function ($a, $b) {
                return ($a['bdcom_id'] - $b['bdcom_id']);
            });
        }


        return array_values($data);
    }

    public function formatDateRange($period): array
    {
        return match ($period) {
            'daily' => [
                'dateRange' => Carbon::now()->subDay(),
                'format' => "%Y-%m-%d %H:%i:%s",
                'avg' => false,
            ],
            'weekly' => [
                'dateRange' => Carbon::now()->subDays(7),
                'format' => "%Y-%m-%d %H:%i",
                'avg' => true,
                'multiper' => 30
            ],
            'monthly' => [
                'dateRange' => Carbon::now()->subDays(30),
                'format' => "%Y-%m-%d %H:%i",
                'avg' => true,
                'multiper' => 120
            ],
            'year' => [
                'dateRange' => Carbon::now()->subDays(365),
                'format' => "%Y-%m-%d",
                'avg' => true,
                'multiper' => 1440
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
                'created_at' => $d->created_at,
                'updated_at' => $d->created_at,
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
