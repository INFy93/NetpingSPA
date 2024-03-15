<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Netping;
use Illuminate\Http\Request;
use function App\Helpers\get_alarm_state;
use function App\Helpers\get_door_state;
use function App\Helpers\get_netping_state;
use function App\Helpers\get_power_state;

class NetpingApiController extends Controller
{
    /*
   *  методы для ajax-запросов
   */
    public function get_power_data()
    {
        $netping = Netping::select('id', 'power_state')->get();
        $power_data = get_power_state($netping);

        return response()->json($power_data)->getData();
    }

    public function get_door_data()
    {
        $netping = Netping::select('id', 'door_state')->get();
        $door_data = get_door_state($netping);

        return response()->json($door_data)->getData();
    }

    public function get_alarm_data()
    {
        $netping = Netping::select('id', 'alarm_state', 'revision')->get();
        $revision = [];
        foreach ($netping as $r)
        {
            $revision[] = $r->revision;
        }
        $alarm_data = get_alarm_state($netping);

        return response()->json([
            'revision' => $revision,
            'alarm_data' => $alarm_data
        ])->getData();
    }
    public function get_secure_data()
    {
        $netping = Netping::select('id', 'netping_state', 'revision')->get();
        $revision = [];

        $secure_state = get_netping_state($netping);

        foreach ($netping as $r)
        {
            $revision[] = $r->revision;
        }

        return response()->json([
            'revision' => $revision,
            'secure_data' => $secure_state
        ])->getData();
    }
}
