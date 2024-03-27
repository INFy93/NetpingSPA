<?php

declare(strict_types=1);

namespace App\Providers;

use App\MoonShine\Pages\Graphs\TemperatureGraphs;
use App\MoonShine\Resources\ActionResource;
use App\MoonShine\Resources\BdcomResource;
use App\MoonShine\Resources\LogResource;
use App\MoonShine\Resources\NetpingResource;
use App\MoonShine\Resources\UserResource;
use Illuminate\Http\Request;
use MoonShine\Menu\MenuDivider;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    protected function resources(): array
    {
        return [];
    }

    protected function pages(): array
    {
        return [];
    }

    protected function menu(): array
    {
        return [
            MenuGroup::make('Управление', [
                MenuItem::make('Точки', new NetpingResource()),
                MenuItem::make('BDCOM', new BdcomResource()),
                MenuDivider::make(),
                MenuItem::make('Пользователи', new UserResource()),
                MenuItem::make('Действия', new ActionResource()),
                MenuItem::make('Логи', new LogResource()),
            ])
                ->canSee(function(Request $request) {
                    return $request->user()->is_admin === 1;
                })
            ,
            MenuItem::make('Графики', new TemperatureGraphs()),
            MenuItem::make('Главная', '/')
        ];
    }

    /**
     * @return array{css: string, colors: array, darkColors: array}
     */
    protected function theme(): array
    {
        return [];
    }
}
