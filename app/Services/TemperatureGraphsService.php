<?php

namespace App\Services;

use App\Models\Bdcom;
use App\Models\MonthTemperatures;
use App\Models\Temperature;
use App\Models\WeeksTemperatures;
use App\Models\YearTemperatures;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class TemperatureGraphsService
{
    public function getTemperatureGraphsData($period, $is_server): \Illuminate\Http\JsonResponse
    {
        // Если is_server != 0, фильтр равен 1, иначе не фильтруем
        $filter = $is_server != 0 ? 1 : null;
        $bdcomQuery = Bdcom::select('id', 'bdcom_name');
        if (!is_null($filter)) {
            $bdcomQuery->where('is_server', $filter);
        }
        $bdcom_names = $bdcomQuery->paginate(5);

        // Получаем ID для текущей страницы
        $bdcom_ids = $bdcom_names->pluck('id')->toArray();

        // Подготовка диапазона дат и выбор таблицы по периоду
        $temperatureService = new TemperatureService();
        $range = $temperatureService->formatDateRange($period);
        $endDate = Carbon::now()->format('Y-m-d H:i:s');

        $table = match ($period) {
            'daily' => new Temperature(),
            'weekly' => new WeeksTemperatures(),
            'monthly' => new MonthTemperatures(),
            'year' => new YearTemperatures(),
            default => throw new \InvalidArgumentException("Invalid period: $period")
        };

        // Кэширование агрегированных данных для выбранных BDcom на 10 минут.
        $cacheKey = "temperature_graphs_{$period}_{$is_server}_page_" . $bdcom_names->currentPage();
        $aggregatedData = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($table, $bdcom_ids, $range, $endDate) {
            return $table->query()
                ->selectRaw('bdcom_id, temperature as temp, DATE_FORMAT(created_at, "' . $range['format'] . '") as date')
                ->whereIn('bdcom_id', $bdcom_ids)
                ->whereDateBetween('created_at', $range['dateRange'], $endDate)
                ->groupBy('bdcom_id', 'date')
                ->get();
        });

        // Группируем агрегированные данные по bdcom_id
        $groupedData = $aggregatedData->groupBy('bdcom_id');

        $data = [];
        foreach ($bdcom_names as $bdcom) {
            $group = $groupedData->get($bdcom->id, collect([]));
            $dates = $group->pluck('date')->toArray();
            $temps = $group->pluck('temp')
                ->map(fn($temp) => $temp == 0 ? null : $temp)
                ->toArray();
            $options = new TemperatureGraphOptions($bdcom->bdcom_name, $dates, $temps);
            $data[] = $options->graphOptions();
        }

        return response()->json([
            'bdcoms' => $bdcom_names,
            'graphs' => $data
        ]);
    }
}
