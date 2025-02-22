<?php

namespace App\Services;

class TemperatureGraphOptions
{
    public string $bdcomName = '';
    public array $dateArray = [];
    public array $temperatures = [];

    public function __construct(string $bdcom, array $dates, array $temps)
    {
        $this->bdcomName = $bdcom;
        $this->dateArray = $dates;
        $this->temperatures = $temps;
    }

    public function graphOptions(): object
    {
        return (object)[
            'bdcom_name' => $this->bdcomName,
            'options' => [
                'chart' => [
                    //'type' => 'spline',
                    'animations' => [
                        'enabled' => false,
                        'dynamicAnimation' => [
                            'enabled' => false
                        ]
                    ],
                    'toolbar' => [
                        'show' => false
                    ],
                ],
                'title' => [
                    'text' => $this->bdcomName
                ],
                'grid' => [
                    'strokeDashArray' => 2
                ],
                'colors' => [
                    '#ec4176'
                ],
                'stroke' => [
                    'width' => 3,
                    'curve' => 'smooth'
                ],
                'xAxis' => [
                    'axisBorder' => [
                        'show' => false,
                    ],
                    'axisTicks' => [
                        'show' => false,
                    ],
                    'tickInterval' => 18,
                    'categories' => $this->dateArray
                ],
                'yAxis' => [
                    'title' => [
                        'text' => 'Температура (°С)'
                    ],
                    'plotBands' => [
                        [
                            'from' => 0,
                            'to' => 69,
                            'color' => '#86efac',
                        ],
                        [
                            'from' => 70,
                            'to' => 74,
                            'color' => '#ffa726',
                        ],
                        [
                            'from' => 75,
                            'to' => 200,
                            'color' => '#e53935',
                        ],
                    ],
                ],
                'tooltip' => [
                    'valueSuffix' => ' °С'
                ],
                'plotOptions' => [
                    'series' => [
                        'connectNulls' => true,
                    ],
                    'area' => [
                        'lineWidth' => 1,


                    ],
                ],
                'series' => [[
                    'color' => [
                        'linearGradient' => [
                            'x1' => 0,
                            'y1' => 0,
                            'x2' => 0,
                            'y2' => 1
                        ],
                        'stops' => [
                            [0, 'rgb(199, 113, 243)'],
                            [1, 'rgb(76, 175, 254)']
                        ],
                    ],
                    'type' => 'area',
                    'name' => $this->bdcomName,
                    'data' => $this->temperatures,
                ]],
            ],

        ];
    }
}
