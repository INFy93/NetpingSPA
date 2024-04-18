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
        $bdcom_names = Bdcom::select('id', 'bdcom_name')->get();

        $data = [];
        $date = [];
        $temp = [];
        foreach ($bdcom_names as $b) {
            $temps = Temperature::where('bdcom_id', $b->id)
                ->get();

            foreach ($temps as $t) {

                $date[] = $t->created_at->format("d-m-Y H-i-s");
                $temp[] = $t->temperature;

            }
            //dd($temperature);

            $data[] = [
                'bdcom_name' => $b->bdcom_name,
                'options' => [
                    'xaxis' => [
                        'categories' => $date
                    ],
                    'series' => [
                        'data' => $temp
                    ]
                ]

            ];
        }

        return response()->json($data)->getData();
    }
}
