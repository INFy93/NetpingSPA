<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bdcom;

use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;

/**
 * @extends ModelResource<Bdcom>
 */
class BdcomResource extends ModelResource
{
    protected string $model = Bdcom::class;

    protected string $title = 'BDCOM';

    public string $column = 'bdcom_name';

    protected string $sortDirection = 'ASC';

    protected bool $createInModal = true;

    protected bool $editInModal = true;

    protected bool $detailInModal = true;

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Имя', 'bdcom_name'),
                Text::make('IP', 'bdcom_ip'),
                BelongsToMany::make('Точка', 'netping', resource: new NetpingResource())
                    ->selectMode()
                    ->nullable(),
            ]),
        ];
    }

    public function redirectAfterSave(): string
    {
        return '/admin/resource/bdcom-resource/index-page';
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
