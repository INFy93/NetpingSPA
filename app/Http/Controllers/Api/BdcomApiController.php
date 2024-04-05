<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BdcomResource;
use App\Models\Bdcom;
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
                ->get()
        );
    }
}
