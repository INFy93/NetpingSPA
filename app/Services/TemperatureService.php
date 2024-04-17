<?php

namespace App\Services;

class TemperatureService
{
    public function makeTemperaturesArray($collection = null, $group = 1)
    {
        $temperatures = $collection->toArray();

        usort($temperatures, function($a, $b){
            return ($a['bdcom']['netping_id'] - $b['bdcom']['netping_id']);
        });

        $data = [];
        $i = 0;
        foreach ($temperatures as $key => $value) {
            if ($group == 1)
            {
                if (($key - 1) >=0 && $value['bdcom']['netping_id'] && $temperatures[$key - 1]['bdcom']['netping_id'] == $value['bdcom']['netping_id']) {
                    $data[] = array(
                        'bdcom1_temp' => intval($temperatures[$key - 1]['temperature']),
                        'bdcom2_temp' => intval($value['temperature']),
                        'netping_id' => $value['bdcom']['netping_id'],
                        'bdcom_id' => $value['bdcom_id'],
                        'key' => $key
                    );
                    unset($data[$key - 2]);
                } else if ($value['bdcom']['netping_id'])
                {
                    $data[] = array(
                        'bdcom1_temp' => intval($value['temperature']),
                        'netping_id' => $value['bdcom']['netping_id'],
                        'bdcom_id' => $value['bdcom_id'],
                        'key' => $key
                    );
                }
            } else
            {
                $data[] = array(
                    'bdcom1_temp' => intval($value['temperature']),
                    'netping_id' => $value['bdcom']['netping_id'],
                    'bdcom_id' => $value['bdcom_id'],
                    'key' => $key
                );
            }
        }
        if ($group == 0)
        {
            usort($data, function($a, $b){
                return ($a['bdcom_id'] - $b['bdcom_id']);
            });
        }


        return array_values($data);
    }
}
