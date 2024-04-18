<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Graphs;

use App\Models\Bdcom;
use App\Models\Temperature;
use MoonShine\Decorations\Flex;
use MoonShine\Decorations\Heading;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\Metrics\LineChartMetric;
use MoonShine\MoonShineRequest;
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

    private $charts;

    private function getBdcomNames()
    {
        return Bdcom::select('id', 'bdcom_name')->get();
    }

    public function title(): string
    {
        return $this->title ?: 'BDCOM - графики';
    }

    public function generateCharts()
    {
        $bdcoms = $this->getBdcomNames();
        $this->charts = new \stdClass();
        $chart = [];
        $bdcom_list = [];
        foreach ($bdcoms as $b) {
            $bdcom_list[] = [
                $b->id => $b->bdcom_name,
            ];
            $chart[] = [
                Heading::make($b->bdcom_name)->customAttributes(['class' => 'mt-3']),
                LineChartMetric::make($b->bdcom_name)
                    ->line([
                        $b->bdcom_name => Temperature::query()
                            ->where('bdcom_id', $b->id)
                            ->where('temperature', '!=', 0)
                            ->selectRaw('temperature as temp, DATE_FORMAT(created_at, "'.session('sort_date').'") as date')
                            ->groupBy('date')
                            ->pluck('temp', 'date')
                            ->toArray()
                    ], '#ec4176')->when(session('sort_date'))

            ];

        }
        //dd($bdcom_list);
        $select = Flex::make([
            Select::make('Диапазон выборки')->options([
                '%d.%m.%Y %H:%i' => 'По часам',
                '%d.%m.%Y' => 'По дням',
                '%d.%m' => 'По неделям'
            ])
                ->onChangeMethod('sortByPeriod')
                ->setValue(session('sort_date') ?: '%d.%m.%Y %H:%i'),
            Select::make('Выбрать BDCOM')->options(
                $bdcom_list
            ),
        ]);

        array_unshift($chart, $select);
        $chart = collect($chart);

        $flattened = $chart->flatten();

        $this->charts = $flattened->all();

        return $this->charts;
    }

    public function beforeRender(): void
    {
        $this->generateCharts();
    }

    public function components(): array
    {
        return (array)$this->generateCharts();
    }

    public function sortByPeriod(MoonShineRequest $request)
    {
        session()->put('sort_date', $request->get('value'));
        header("Refresh:0");
    }
}
