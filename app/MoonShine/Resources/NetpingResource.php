<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Netping;

use MoonShine\ActionButtons\ActionButton;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\QueryTags\QueryTag;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;

/**
 * @extends ModelResource<Netping>
 */
class NetpingResource extends ModelResource
{
    protected string $model = Netping::class;

    protected string $title = 'Точки';

    public string $column = 'name';

    protected string $sortDirection = 'ASC';

    protected bool $createInModal = true;

    protected bool $editInModal = true;

    protected bool $detailInModal = true;

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Название', 'name'),
                Text::make('IP', 'ip'),
                HasMany::make('BDCOM', 'bdcoms', resource: new BdcomResource())
                    ->fields([
                        Text::make('IP', 'bdcom_ip')
                    ]),
                Text::make('Камера', 'camera_ip'),
            ]),
        ];
    }


    public function rules(Model $item): array
    {
        return [];
    }
}
