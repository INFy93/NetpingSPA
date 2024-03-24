<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bdcom;
use Illuminate\Http\Request;
use function App\Helpers\getBdcomTemp;

class BdcomApiController extends Controller
{
    public function getBdcomCurrentTemperature()
    {
        $bdcoms = Bdcom::select('id', 'bdcom_ip', 'netping_id')->get();

        $temps = getBdcomTemp($bdcoms);

        foreach ($bdcoms as $b)
        {
            $b->temps = $temps;
        }
        dd($bdcoms);
        return response()->json($bdcoms)->getData();
    }
}
