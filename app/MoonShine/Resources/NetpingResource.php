<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Netping;

use MoonShine\Fields\Relationships\HasMany;
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

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Название', 'name'),

            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
