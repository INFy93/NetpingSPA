<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Netping;
use App\Services\AlarmSwitch;
use App\Services\NetpingHttpService;
use App\Services\VentSwitch;
use Illuminate\Http\Request;

class NetpingApiController extends Controller
{
    protected NetpingHttpService $netpingService;

    public function __construct(NetpingHttpService $netpingService)
    {
        $this->netpingService = $netpingService;
    }

    /*
     * Методы для ajax-запросов
     */
    public function get_power_data()
    {
        $netping = Netping::select('id', 'power_state')->get();
        $power_data = $this->netpingService->getPowerState($netping);

        return response()->json($power_data);
    }

    public function get_vent_data()
    {
        $netping = Netping::select('id', 'vent_state')->get();
        $vent_data = $this->netpingService->getVentState($netping);

        return response()->json($vent_data);
    }

    public function get_door_data()
    {
        $netping = Netping::select('id', 'door_state')->get();
        $door_data = $this->netpingService->getDoorState($netping);

        return response()->json($door_data);
    }

    public function get_alarm_data()
    {
        $netping = Netping::select('id', 'alarm_state', 'revision')->get();
        $alarm_data = $this->netpingService->getAlarmState($netping);
        $revision = $netping->pluck('revision');

        return response()->json([
            'revision' => $revision,
            'alarm_data' => $alarm_data,
        ]);
    }

    public function get_secure_data()
    {
        $netping = Netping::select('id', 'netping_state', 'revision')->get();
        $secure_state = $this->netpingService->getNetpingState($netping);
        $revision = $netping->pluck('revision');

        return response()->json([
            'revision' => $revision,
            'secure_data' => $secure_state,
        ]);
    }

    public function switchAlarm($netping_id, AlarmSwitch $alarmSwitch)
    {
        return $alarmSwitch->setAlarm($netping_id);
    }

    public function switchVent($netping_id, VentSwitch $ventSwitch)
    {
        return $ventSwitch->setVent($netping_id);
    }
}

