<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Graphs;

use MoonShine\Pages\Page;

class TemperatureGraphs extends Page
{
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return $this->title ?: 'TemperatureGraphs';
    }

    public function components(): array
	{
		return [];
	}
}
