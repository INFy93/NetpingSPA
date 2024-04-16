<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Netping;

use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\Link;
use MoonShine\Decorations\Divider;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\Fields;
use MoonShine\Fields\Hidden;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\MoonShineRequest;
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

    /*
     * We don't need to display a lot of fields there. So, let's use indexFields method
     */
    public function indexFields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Название', 'name'),
                Text::make('IP', 'ip'),
                BelongsToMany::make('BDCOM', 'bdcom',
                    fn($item) => $item->bdcom_name . " (" . $item->bdcom_ip .")",
                    resource: new BdcomResource())->columnLabel('IP'),
                Text::make('Камера', 'camera_ip'),
            ]),
        ];
    }

    /*
     * Now, we need all fields to add them to database. But also we need to format some values (for example, alarm status link)
     * So, we can use reactive property to format values, using IP address.
     * This links depends on revision. We can use onChangeMethod property and public method to put on the session revision value.
     * Usually, reactive callback (like in documentation) can change the value just only for one field.
     * Putting values to several fields require, for example, foreach loop.
     */
    public function formFields(): array
    {
        return [
            Divider::make('В первую очередь нужно отметить ревизию! По умолчанию ревизия v2.'), //divider
            Checkbox::make('Ревизия (отметить, если v4)', 'revision') //checkbox: selected - revision v4. default - v2
                ->onValue(4)
                ->offValue(2)
            ->onChangeMethod('setRevisionToCreateNetpingLinks'), //put revision to session
            Text::make('Название', 'name'), //name of the netping
            Text::make('IP', 'ip') //ip address: reactive field
                ->reactive(function(Fields $fields, ?string $value): Fields {
                    $revision = session('revision') ? session('revision') : 2; //get revision number from session
                /*
                 * Helper array: formatting netping links using revision
                 */
                    $params = [
                      'power_state' => config('netping.netping_login') . $value . ($revision == 2 ? config('netping.power_state') :
                              config('netping.power_state_v4')),
                      'door_state' =>  config('netping.netping_login') . $value . ($revision == 2 ? config('netping.door_state') :
                              config('netping.door_state_v4')),
                      'alarm_state' => config('netping.netping_login') . $value . ($revision == 2 ? config('netping.alarm_state') :
                              config('netping.alarm_state_v4')),
                      'netping_state' => config('netping.netping_login') . $value . ($revision == 2 ? config('netping.netping_state') :
                              config('netping.netping_state_v4')),
                      'alarm_control' => config('netping.netping_login') . $value . ($revision == 2 ? config('netping.alarm_control') :
                              config('netping.alarm_control_v4')),
                      'alarm_switch_v4' => $revision == 4 ? config('netping.netping_login') . $value .  config('netping.alarm_switch_v4') : '',
                    ];
                    foreach ($params as $key => $value) //fill fields using helper array and foreach loop
                    {
                        $reactive_fields = tap($fields, static fn ($fields) => $fields
                        ->findByColumn($key)
                        ?->setValue($value)->value());
                    }
                    return $reactive_fields;
                }
            ),
            Text::make('Камера', 'camera_ip'), //camera ip. not required
            BelongsToMany::make('BDCOM', 'bdcom',
                fn($item) => $item->bdcom_name . " (" . $item->bdcom_ip .")",
                resource: new BdcomResource())
                ->selectMode()->creatable(),
            Divider::make('Ссылки на управление точкой (заполняются автоматически)'), //divider for section with netping links
            Text::make('Статус питания', 'power_state')->reactive(), //power state link
            Text::make('Статус двери', 'door_state')->reactive(), //door state link
            Text::make('Статус сирены', 'alarm_state')->reactive(), //alarm state link
            Text::make('Состояние охраны', 'netping_state')->reactive(), //secure state link
            Text::make('Управление охраной', 'alarm_control')->reactive(), //secure on/off link
            Text::make('Отключение сирены (только v4)', 'alarm_switch_v4')->reactive(), //alarm on/off link (for revision v4)
        ];
    }

    /*
     * Put Checkbox value to the session.
     * This method using on onChangeMethod property.
     * onChangeMethod requires ONLY public methods!
     */
    public function setRevisionToCreateNetpingLinks(MoonShineRequest $request)
    {
        $request->session()->put('revision', $request->get('value'));
    }

    public function redirectAfterSave(): string
    {
        return '/admin/resource/netping-resource/index-page';
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
