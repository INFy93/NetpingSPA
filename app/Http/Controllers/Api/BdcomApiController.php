<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BdcomResource;
use App\Models\Bdcom;
use App\Models\Temperature;
use App\Services\TemperatureService;
use Illuminate\Http\Request;
use function App\Helpers\getBdcomTemp;

class BdcomApiController extends Controller
{
    public function getBdcomCurrentTemperature()
    {
        $bdcoms = Bdcom::select('id', 'bdcom_ip', 'netping_id')->get();

        $temps = getBdcomTemp($bdcoms);

        return response()->json($temps)->getData();
    }

    public function getBdcoms()
    {
        return BdcomResource::collection(
            Bdcom::query()
                ->with(['netping' => function ($query) {
                    $query->select('id', 'name', 'bdcom_id');
                }])
                ->paginate(10)
        );
    }

    public function getTemperaturesFromDB()
    {
        $group = \request('group', 1);
        $isPaginate = \request('isPaginate', 0);
        $bdcoms_count = Bdcom::select('id', 'bdcom_ip', 'netping_id')->count();

        $query = Temperature::with(['bdcom',
                'netping' => function($q) {
                    $q->select('id', 'name');
                }
            ])
            ->select('bdcom_id', 'temperature')
            ->orderBy('created_at', 'desc')
            ->limit($bdcoms_count)
            ->get();

        $temperatures = $isPaginate == 0 ? $query : $query->sortBy('bdcom_id')->paginate(10);
       // dd($temperatures);


        $format_data = new TemperatureService();

        $data = $format_data->makeTemperaturesArray($temperatures, $group);


        return response()->json($data)->getData();

    }
}
