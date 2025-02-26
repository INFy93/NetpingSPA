<?php

namespace App\Services;

use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Str;

class NetpingHttpService
{
    protected float $timeout;
    protected int $retryTimes;
    protected int $retryDelay;

    public function __construct()
    {
        // Можно задать таймаут по умолчанию (или брать из конфигурации)
        $this->timeout = 0.2;
        $this->retryTimes = 3;
        $this->retryDelay = 100; // миллисекунды
    }

    /**
     * Приватный метод для выполнения pool-запросов.
     * $items — массив объектов, $urlCallback — функция, возвращающая URL для запроса.
     */
    private function performPoolRequest(iterable $items, callable $urlCallback): array
    {
        return Http::pool(function (Pool $pool) use ($items, $urlCallback) {
            $requests = [];
            foreach ($items as $item) {
                $url = $urlCallback($item);
                if ($url) {
                    $requests[] = $pool->withOptions([
                        'connect_timeout' => $this->timeout
                    ])
                        ->retry($this->retryTimes, $this->retryDelay, function ($exp) {
                            return $exp instanceof ConnectionException;
                        })
                        ->get($url);
                }
            }
            return $requests;
        });
    }

    /**
     * Получить состояние питания для набора точек.
     */
    public function getPowerState(iterable $netpingIps): array
    {
        $responses = $this->performPoolRequest($netpingIps, function ($item) {
            return $item->power_state;
        });

        $p_state = [];
        foreach ($responses as $r) {
            if (!$r instanceof ConnectionException) {
                $p_state[] = explode(", ", $r->body());
            } else {
                $p_state[] = ['0', '0', '3'];
            }
        }
        return $p_state;
    }

    /**
     * Получить состояние двери для набора точек.
     */
    public function getDoorState(iterable $netpingIps): array
    {
        $responses = $this->performPoolRequest($netpingIps, function ($item) {
            return $item->door_state;
        });

        $d_state = [];
        foreach ($responses as $r) {
            if (!$r instanceof ConnectionException) {
                $d_state[] = explode(", ", $r->body());
            } else {
                $d_state[] = ['0', '0', '3'];
            }
        }
        return $d_state;
    }

    /**
     * Получить состояние сирены для набора точек.
     */
    public function getAlarmState(iterable $netpingIps): array
    {
        $responses = $this->performPoolRequest($netpingIps, function ($item) {
            return $item->alarm_state;
        });

        $a_state = [];
        foreach ($responses as $r) {
            if (!$r instanceof ConnectionException) {
                $a_state[] = explode(", ", $r->body());
            } else {
                $a_state[] = ['0', '0', '3'];
            }
        }
        return $a_state;
    }

    /**
     * Получить состояние Netping для набора точек.
     */
    public function getNetpingState(iterable $netpingIps): array
    {
        $responses = $this->performPoolRequest($netpingIps, function ($item) {
            return $item->netping_state;
        });

        $s_state = [];
        foreach ($responses as $r) {
            if (!$r instanceof ConnectionException) {
                $body = $r->body();
                if (strlen($body) == 1) {
                    $s_state[] = iconv("ascii", "utf-8", $body);
                } else {
                    $netping_state = iconv("windows-1251", "utf-8", $body);
                    $netping_state = Str::after($netping_state, "data=");
                    $netping_state = Str::remove(";", $netping_state);
                    $netping_state = explode(",", $netping_state);
                    $s_state[] = $netping_state[19] ?? '3';
                }
            } else {
                $s_state[] = '3';
            }
        }
        return $s_state;
    }

    /**
     * Получить состояние вентилятора для набора точек.
     */
    public function getVentState(iterable $netpingIps): array
    {
        $v_state = [];
        $nonNullItems = [];

        // Собираем элементы, у которых определён адрес vent_state
        foreach ($netpingIps as $item) {
            if ($item->vent_state !== null) {
                $nonNullItems[] = $item;
            }
        }

        $responses = $this->performPoolRequest($nonNullItems, function ($item) {
            return $item->vent_state;
        });

        // Переиспользуем оригинальный порядок: если у точки vent_state отсутствует – возвращаем дефолтное значение
        $responseIndex = 0;
        foreach ($netpingIps as $item) {
            if ($item->vent_state !== null) {
                if (isset($responses[$responseIndex]) && !$responses[$responseIndex] instanceof ConnectionException) {
                    $v_state[] = [
                        'id' => $item->id,
                        'state' => explode(", ", $responses[$responseIndex]->body())
                    ];
                } else {
                    $v_state[] = [
                        'id' => $item->id,
                        'state' => ['0', '0', '3']
                    ];
                }
                $responseIndex++;
            } else {
                $v_state[] = [
                    'id' => $item->id,
                    'state' => ['0', '0', '3']
                ];
            }
        }
        return $v_state;
    }

    /**
     * Получить состояние охраны для одной точки.
     */
    public function getSingleSecureState(string $netpingIp): string
    {
        try {
            $r = Http::timeout($this->timeout)
                ->retry($this->retryTimes, $this->retryDelay, function ($exp) {
                    return $exp instanceof ConnectionException;
                })
                ->get($netpingIp);
            $body = $r->body();
            if (strlen($body) == 1) {
                $state = iconv("ascii", "utf-8", $body);
            } else {
                $netping_state = iconv("windows-1251", "utf-8", $body);
                $netping_state = Str::after($netping_state, "data=");
                $netping_state = Str::remove(";", $netping_state);
                $netping_state = explode(",", $netping_state);
                $state = $netping_state[19] ?? '3';
            }
        } catch (ConnectionException $exp) {
            $state = '3';
        }
        return $state;
    }

    /**
     * Получить состояние двери для одной точки.
     */
    public function getSingleDoorState(string $netpingIp): string
    {
        try {
            $r = Http::timeout($this->timeout)
                ->retry($this->retryTimes, $this->retryDelay, function ($exp) {
                    return $exp instanceof ConnectionException;
                })
                ->get($netpingIp);
            $door_state = explode(", ", $r->body());
            return $door_state[2] ?? '3';
        } catch (ConnectionException $exp) {
            return '3';
        }
    }

    /**
     * Получить состояние вентилятора для одной точки.
     */
    public function getSingleVentState(?string $netpingIp): string
    {
        if (!$netpingIp) {
            return '3';
        }
        try {
            $r = Http::timeout($this->timeout)
                ->retry($this->retryTimes, $this->retryDelay, function ($exp) {
                    return $exp instanceof ConnectionException;
                })
                ->get($netpingIp);
            $vent_state = explode(", ", $r->body());
            return $vent_state[2] ?? '3';
        } catch (ConnectionException $exp) {
            return '3';
        }
    }
}
