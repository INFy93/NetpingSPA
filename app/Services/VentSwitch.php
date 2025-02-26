<?php

namespace App\Services;

use App\Models\Netping;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use function App\Helpers\get_single_vent_state;

class VentSwitch
{
    public function setVent($id)
    {
        $netping = Netping::where('id', $id)->first(); //get netping collection
        $logService = new LogService(); //add Log instance
        $vent_state = get_single_vent_state($netping->vent_state); //check vent status

        if ($vent_state == '3') return '3'; //if there is no vent_state ip - return code 3
        $vent_state == 0 ? $vent_switcher = 1 : $vent_switcher = 0; //on and off vent depends on vent state

        try {
            $response = Http::timeout(env('NETPING_TIMEOUT'))->get($netping->vent_switch_v4 . $vent_switcher); //get HTTP query to on/off vent
        } catch (ConnectionException $exp) {
            return '3';
        }
        $vent_switcher == 1 ? $logService->logging($netping->id, $netping->name, 3) : $logService->logging($netping->id, $netping->name, 4);
        $state = explode("'", $response); //explode response array from netping

        return response()->json($state[1])->getData(); //return status from netping and convert it to JSON
    }

}
