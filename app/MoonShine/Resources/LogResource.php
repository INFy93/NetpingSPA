<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Log;

use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\HasOne;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;

/**
 * @extends ModelResource<Log>
 */
class LogResource extends ModelResource
{
    protected string $model = Log::class;

    protected string $title = 'Логи';

    public function fields(): array
    {
        return [
            Block::make([
                //ID::make()->sortable(),
                BelongsTo::make('Пользователь', 'user', resource: new UserResource()),
                BelongsTo::make('Точка', 'netping', resource: new NetpingResource()),
                BelongsTo::make('Действие', 'action', resource: new ActionResource()),
                Text::make('Дата', 'created_at'),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
