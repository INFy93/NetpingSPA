<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Graphs;

use App\Models\Bdcom;
use App\Models\Temperature;
use MoonShine\Decorations\Heading;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\Text;
use MoonShine\Metrics\LineChartMetric;
use MoonShine\Pages\Page;
use phpDocumentor\Reflection\Types\Object_;

class TemperatureGraphs extends Page
{
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    private $bdcoms;
    private $charts;

    private function getBdcomNames()
    {
        return Bdcom::select('id', 'bdcom_name')->get();
    }

    public function title(): string
    {
        return $this->title ?: 'BDCOM - графики';
    }

    public function beforeRender(): void
    {
        $this->bdcoms = $this->getBdcomNames();
        $this->charts = new \stdClass();
        $chart = [];

        foreach ($this->bdcoms as $b)
        {
            $chart[] = [
                Heading::make($b->bdcom_name)->customAttributes(['class' => 'mt-3']),
                LineChartMetric::make($b->bdcom_name)
                ->line([
                    $b->bdcom_name => Temperature::query()
                        ->where('bdcom_id', $b->id)
                        ->selectRaw('temperature as temp, DATE_FORMAT(created_at, "%d.%m.%Y") as date')
                        ->groupBy('date')
                        ->pluck('temp', 'date')
                        ->toArray()
                ], '#ec4176')

            ];

        }

        $chart = collect($chart);

        $flattened = $chart->flatten();

        $this->charts = $flattened->all();

    }

    public function components(): array
	{
		return [$this->charts];
	}
}
