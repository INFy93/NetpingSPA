<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\Components\Link;
use MoonShine\Pages\Page;

class Dashboard extends Page
{
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return $this->title ?: 'Управление';
    }

    public function components(): array
	{
		return [
            Link::make('/admin/resource/netping-resource/index-page', 'Точки')->button(),
            Link::make('/admin/resource/bdcom-resource/index-page', 'BDCOM')->button(),
            Link::make('/admin/resource/user-resource/index-page', 'Пользователи')->button(),
            Link::make('/admin/resource/action-resource/index-page', 'Действия')->button(),
            Link::make('/admin/resource/log-resource/index-page', 'Логи')->button(),
        ];
	}
}
