<?php

namespace App\Http\Controllers;

use App\MoonShine\Pages\Graphs\TemperatureGraphs;
use Illuminate\Http\Request;
use MoonShine\Pages\Page;

class TemperatureGraphsController extends Controller
{
    public function __invoke(): Page
    {
        return TemperatureGraphs::make();
    }
}
