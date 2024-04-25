<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TemperatureGraphsService;

class TemperatureGraphController extends Controller
{
    public function index()
    {
        return inertia('Graphs/TemperatureGraphs');
    }
    public function getTemperatureData()
    {
        $period = \request('period', "daily");

        $graphHelper = new TemperatureGraphsService();

        return $graphHelper->getTemperatureGraphsData($period);
    }
}
