<?php

namespace App\Services;

use App\Models\Bdcom;
use App\Models\MonthTemperatures;
use App\Models\Temperature;
use App\Models\WeeksTemperatures;
use App\Models\YearTemperatures;
use Carbon\Carbon;

class TemperatureGraphsService
{
    public function getTemperatureGraphsData($period): \Illuminate\Http\JsonResponse
    {
        $bdcom_names = Bdcom::select('id', 'bdcom_name')->paginate(5);

        $data = [];

        $dateRange = new TemperatureService();
        $range = $dateRange->formatDateRange($period);

        $table = '';

        match ($period)
        {
            'daily' => $table = new Temperature(),
            'weekly' => $table = new WeeksTemperatures(),
            'monthly' => $table = new MonthTemperatures(),
            'year' => $table = new YearTemperatures()
        };

        foreach ($bdcom_names as $b) {
                $temps = $table->query()
                    ->selectRaw('temperature as temp, DATE_FORMAT(created_at, "' . $range['format'] . '") as date')
                    ->where('bdcom_id', $b->id)
                    ->where('temperature', '!=', 0)
                    ->whereDateBetween('created_at', $range['dateRange'], Carbon::now())
                    ->groupBy('date', 'bdcom_id')
                    ->get();


            $date = [];
            $temp = [];
            foreach ($temps as $t) {
                $date[] = $t->date;
                $temp[] = $t->temp;
            }

            $data[] = (object)[
                'bdcom_name' => $b->bdcom_name,
                'options' => [
                    'chart' => [
                        'animations' => [
                            'enabled' => false,
                            'dynamicAnimation' => [
                                'enabled' => false
                            ]
                        ],
                        'toolbar' => [
                            'show' => false
                        ],
                    ],
                    'title' => [
                        'text' => $b->bdcom_name
                    ],
                    'grid' => [
                        'strokeDashArray' => 2
                    ],
                    'colors' => [
                        '#ec4176'
                    ],
                    'stroke' => [
                        'width' => 3,
                        'curve' => 'smooth'
                    ],
                    'xAxis' => [
                        'axisBorder' => [
                            'show' => false,
                        ],
                        'axisTicks' => [
                            'show' => false,
                        ],
                        'categories' => $date
                    ],
                    'yAxis' => [
                        'title' => [
                            'text' => 'Температура'
                        ],
                    ],
                    'series' => [[
                        'name' => $b->bdcom_name,
                        'data' => $temp,
                    ]]
                ],


            ];
        }

        return response()->json([
            'bdcoms' => $bdcom_names,
            'graphs' => $data
        ]);
    }
}
