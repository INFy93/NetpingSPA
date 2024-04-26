<?php

namespace App\Services;

use App\Models\Bdcom;
use App\Models\MonthTemperatures;
use App\Models\Temperature;
use App\Models\WeeksTemperatures;
use App\Models\YearTemperatures;
use Carbon\Carbon;


class TemperatureGraphsService
{
    public function getTemperatureGraphsData($period): \Illuminate\Http\JsonResponse
    {
        $bdcom_names = Bdcom::select('id', 'bdcom_name')->paginate(5);

        $data = [];

        $dateRange = new TemperatureService();
        $range = $dateRange->formatDateRange($period);

        $table = '';

        match ($period)
        {
            'daily' => $table = new Temperature(),
            'weekly' => $table = new WeeksTemperatures(),
            'monthly' => $table = new MonthTemperatures(),
            'year' => $table = new YearTemperatures()
        };

        foreach ($bdcom_names as $b) {

                $temps = $table->query()
                    ->selectRaw('temperature as temp, DATE_FORMAT(created_at, "' . $range['format'] . '") as date')
                    ->where('bdcom_id', $b->id)
                    ->whereDateBetween('created_at', $range['dateRange'], Carbon::now()->format('Y-m-d H:i:s'))
                    ->groupBy('date', 'bdcom_id')
                    ->get();

            $date = [];
            $temp = [];
            foreach ($temps as $t) {
                $date[] = $t->date;
                $temp[] = $t->temp == 0 ? null : $t->temp;
            }
            $options = new TemperatureGraphOptions($b->bdcom_name, $date, $temp);
            $data[] = $options->graphOptions();
        }

        return response()->json([
            'bdcoms' => $bdcom_names,
            'graphs' => $data
        ]);
    }
}
