<?php

namespace App\Orchid\Layouts\Items;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
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

            TD::make('item_code', 'Code')
            ->cantHide()
            ->filter(Input::make()),

            TD::make('name', 'Name')
            ->cantHide()
                ->filter(Input::make()),

            TD::make('description', 'Description')
                ->filter(Input::make()),

            TD::make('min_quantity', 'Min. Qty.'),

            TD::make('stock', 'Stock')
                ->sort()
                ->render(fn ($item) => abs($item->stock - $item->min_quantity) <= abs($item->stock - $item->max_quantity)
                    ? "<span class='text-danger'>{$item->stock}</span>"
                    : "<span class='text-success'>{$item->stock}</span>"
                ),
            TD::make('location', 'Location')
                ->render(fn ($item) => $item->location->name),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->cantHide()
                ->render(fn ($items) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        Link::make(__('Item Details'))
                            ->route('platform.items.show', $items->id)
                            ->icon('bs.eye'),

                        Link::make(__('Edit'))
                            ->route('platform.items.edit', $items->id)
                            ->icon('bs.pencil'),

                        Button::make(__('Delete'))
                            ->icon('bs.trash3')
                            ->confirm(__('Once the item is deleted, all of its resources and data will be permanently deleted. All the agents under this item will be reassigned to BLANK'))
                            ->method('remove', [
                                'id' => $items->id,
                            ]),

                        Button::make(__('Print Code'))
                        ->icon('bs.printer')
                        ->method('printCode', [
                            'id' => $items->id,
                        ]),
                    ])),
        ];
    }
}
