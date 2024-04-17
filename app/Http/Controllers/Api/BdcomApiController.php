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
        $request = \request('group', '');
        $bdcoms = Bdcom::select('id', 'bdcom_ip', 'netping_id')->get();

        $temps = getBdcomTemp($bdcoms, $request);

        return response()->json($temps)->getData();
    }

    public function getBdcoms()
    {
        return BdcomResource::collection(
            Bdcom::query()
                ->with(['netping' => function ($query) {
                    $query->select('id', 'name', 'bdcom_id');
                }])
                ->get()
        );
    }

    public function getTemperaturesFromDB()
    {
        $group = \request('group', 1);
        $bdcoms_count = Bdcom::select('id', 'bdcom_ip', 'netping_id')->count();
        $temperatures = Temperature::with('bdcom')
            ->select('bdcom_id', 'temperature')
            ->orderBy('created_at', 'desc')
            ->limit($bdcoms_count)
            ->get();

        $format_data = new TemperatureService();

        $data = $format_data->makeTemperaturesArray($temperatures, $group);

        return response()->json($data)->getData();

    }
}
