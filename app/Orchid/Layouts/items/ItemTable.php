<?php

namespace App\Orchid\Layouts\items;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ItemTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'items';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [

            TD::make('item_code', 'Codigo')
            ->cantHide(),
            TD::make('name', 'Nombre')
            ->cantHide(),
            TD::make('description', 'Descripcion'),
            TD::make('min_quantity', 'Min. Cant.'),
            TD::make('max_quantity', 'Max. Cant.'),
            TD::make('stock', 'Stock')
                ->sort()
                ->render(fn ($item) => abs($item->stock - $item->min_quantity) <= abs($item->stock - $item->max_quantity)
                    ? "<span class='text-danger'>{$item->stock}</span>"
                    : "<span class='text-success'>{$item->stock}</span>"
                ),


            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->cantHide()
                ->render(fn ($items) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([

                        Link::make(__('Ver'))
                            ->route('platform.items.show', $items->id)
                            ->icon('bs.eye'),

                        Link::make(__('Edit'))
                            ->route('platform.items.edit', $items->id)
                            ->icon('bs.pencil'),

                        Button::make(__('Delete'))
                            ->icon('bs.trash3')
                            ->confirm(__('Una vez eliminado el item, todos sus datos serán eliminados, esta accion se puede deshacer.'))
                            ->method('remove', [
                                'id' => $items->id,
                            ]),
                    ])),





        ];
    }
}
