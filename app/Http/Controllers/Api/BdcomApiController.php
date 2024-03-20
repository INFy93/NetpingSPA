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
        $bdcoms = Bdcom::select('id', 'bdcom_ip')->get();

        $temps = getBdcomTemp($bdcoms);

        return response()->json($temps)->getData();
    }
}
