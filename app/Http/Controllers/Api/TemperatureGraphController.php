<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bdcom;
use App\Models\Temperature;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TemperatureGraphController extends Controller
{
    public function index()
    {
        return inertia('Graphs/TemperatureGraphs');
    }
    public function getTemperatureData()
    {
        $period = \request('period', "%d.%m.%Y %H:%i");
        $bdcom_names = Bdcom::select('id', 'bdcom_name')->paginate(5);

        $data = [];

        foreach ($bdcom_names as $b) {
            $temps = Temperature::query()
                ->where('bdcom_id', $b->id)
                ->where('temperature', '!=', 0)
                ->selectRaw('temperature as temp, DATE_FORMAT(created_at, "' . $period . '") as date')
                ->groupBy('date')
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
