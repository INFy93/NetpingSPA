<?php

namespace App\Services;

use App\Models\Netping;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use function App\Helpers\get_single_door_state;
use function App\Helpers\get_single_secure_state;

class AlarmSwitch
{
    public function setAlarm($netping_id)
    {
        $logService = new LogService();
        $netping = Netping::where('id', $netping_id)->first();
        $status = 0;
        $raw_state = '';
        $secure_status = get_single_secure_state($netping->netping_state);

        if ($netping->revision == 4) { return $this->setAlarmv4($netping->id, $secure_status); }

        switch ($secure_status) {
            case 3:
                $status = 3;
                break;
            case 'direction:2': //точка на охране - снимаем охрану

                $raw_state = HTTP::timeout(env('NETPING_TIMEOUT'))->get($netping->alarm_control . '1');

                $state = explode("'", $raw_state);
                if ($state[1] == 'ok') {
                    $logService->logging($netping->id, $netping->name, 1 );
                    $status = 0;
                } else if ($state[1] == 'error') {
                    $status = 2;
                }
                break;
            case 'direction:1': //точка не на охране - ставим на охрану
                $door_state = get_single_door_state($netping->door_state);
                if ($door_state == 1) { //если дверь открыта - поставить на охрану нельзя
                    $status = 4;
                    break;
                } else { //дверь закрыта - можно ставить на охрану
                    try {
                        $raw_state = HTTP::timeout(env('NETPING_TIMEOUT'))->get($netping->alarm_control . '2');
                    } catch (ConnectionException $exp) {
                        $status = 3;
                    }
                    $state = explode("'", $raw_state);
                    if ($state[1] == 'ok') {
                        $logService->logging($netping->id, $netping->name, 2 );
                        $status = 1;
                    } else if ($state[1] == 'error') {
                        $status = 2;
                    }
                    break;
                }
        }
        return response()->json($status)->getData();
    }

    public function setAlarmv4($netping_id, $current_state)
    {
        $logService = new LogService();
        $raw_state = '';
        $turn_off_the_alarm = '';
        $status = 0;

        if ($current_state == 3) {
            $status = 3;
        }

        $netping_v4 = Netping::where('id', $netping_id)->first();


        switch ($current_state[0]) {
            case '1': //точка на охране, снимаем охрану
                try {
                    $raw_state =  HTTP::timeout(env('NETPING_TIMEOUT'))->get($netping_v4->alarm_control . '0');
                } catch (ConnectionException $exp) {
                    $status = 3;
                }

                try {
                    $turn_off_the_alarm = HTTP::timeout(env('NETPING_TIMEOUT'))->get($netping_v4->alarm_switch_v4 . '0');
                } catch (ConnectionException $exp) {
                    $status = 3;
                }

                $answer = explode("'", $turn_off_the_alarm);

                if ($answer[1] == 'ok') {
                    $logService->logging($netping_v4->id, $netping_v4->name, 1 );
                    $status = 0;
                } else if ($answer[1] == 'error') {
                    $status = 2;
                }
                break;
            case '0': //точка снята с охраны, исправляем это недоразумение
                $door_state = get_single_door_state($netping_id);
                if ($door_state == 1) {
                    $status = 4;
                    break;
                } else {
                    try {
                        $raw_state =  HTTP::timeout(env('NETPING_TIMEOUT'))->get($netping_v4->alarm_control . '1');
                    } catch (ConnectionException $exp) {
                        return 3;
                    }

                    try {
                        $restart_logic =  HTTP::timeout(env('NETPING_TIMEOUT'))->get($netping_v4->alarm_control . '2');
                    } catch (ConnectionException $exp) {
                        return 3;
                    }
                    if ($raw_state && $restart_logic) {
                        $logService->logging($netping_v4->id, $netping_v4->name, 1 );
                        $status = 1;
                    } else {
                        $status = 2;
                    }
                    break;
                }
        }
        return $status;
    }
}
