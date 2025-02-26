<?php

namespace App\Services;

use App\Models\Netping;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use function App\Helpers\get_single_secure_state;
use function App\Helpers\get_single_door_state;

class AlarmSwitch
{
    protected mixed $timeout;

    public function __construct()
    {
        $this->timeout = env('NETPING_TIMEOUT');
    }

    public function setAlarm($netping_id)
    {
        $logService = new LogService();
        $netping = Netping::find($netping_id);
        $secure_status = get_single_secure_state($netping->netping_state);

        // Если ревизия равна 4, передаём уже полученную модель
        if ($netping->revision == 4) {
            return $this->setAlarmv4($netping, $secure_status);
        }

        $status = 0;
        switch ($secure_status) {
            case 3:
                $status = 3;
                break;
            case 'direction:2': // точка на охране – снимаем охрану
                $raw_state = $this->sendRequest($netping->alarm_control . '1');
                $result = $this->parseResponse($raw_state);
                if ($result === 'ok') {
                    $logService->logging($netping->id, $netping->name, 1);
                    $status = 0;
                } elseif ($result === 'error') {
                    $status = 2;
                }
                break;
            case 'direction:1': // точка не на охране – ставим на охрану
                $door_state = get_single_door_state($netping->door_state);
                if ($door_state == 1) {
                    // Если дверь открыта, ставить на охрану нельзя
                    $status = 4;
                    break;
                }

                try {
                    $raw_state = $this->sendRequest($netping->alarm_control . '2');
                } catch (ConnectionException $exp) {
                    $status = 3;
                    break;
                }
                $result = $this->parseResponse($raw_state);
                if ($result === 'ok') {
                    $logService->logging($netping->id, $netping->name, 2);
                    $status = 1;
                } elseif ($result === 'error') {
                    $status = 2;
                }
                break;
            default:
                $status = 3;
        }

        return response()->json($status)->getData();
    }

    public function setAlarmv4($netping, $current_state): int
    {
        $logService = new LogService();

        // Если текущее состояние равно 3, возвращаем статус 3
        if ($current_state == 3) {
            return 3;
        }

        $status = 0;
        switch ($current_state[0]) {
            case '1': // точка на охране – снимаем охрану
                try {
                    $raw_state = $this->sendRequest($netping->alarm_control . '0');
                    $turn_off = $this->sendRequest($netping->alarm_switch_v4 . '0');
                } catch (ConnectionException $exp) {
                    return 3;
                }

                $result = $this->parseResponse($turn_off);
                if ($result === 'ok') {
                    $logService->logging($netping->id, $netping->name, 1);
                    $status = 0;
                } elseif ($result === 'error') {
                    $status = 2;
                }
                break;
            case '0': // точка снята с охраны – исправляем ситуацию
                $door_state = get_single_door_state($netping->door_state);
                if ($door_state == 1) {
                    return 4;
                }
                try {
                    $raw_state = $this->sendRequest($netping->alarm_control . '1');
                    $restart = $this->sendRequest($netping->alarm_control . '2');
                } catch (ConnectionException $exp) {
                    return 3;
                }
                if ($raw_state && $restart) {
                    $logService->logging($netping->id, $netping->name, 2);
                    $status = 1;
                } else {
                    $status = 2;
                }
                break;
            default:
                $status = 3;
        }
        return $status;
    }

    /**
     * Отправка HTTP-запроса с заданным таймаутом.
     */
    private function sendRequest($url)
    {
        return HTTP::timeout($this->timeout)->get($url);
    }

    /**
     * Разбор ответа HTTP-запроса.
     */
    private function parseResponse($rawState)
    {
        $parts = explode("'", $rawState);
        return $parts[1] ?? null;
    }
}
