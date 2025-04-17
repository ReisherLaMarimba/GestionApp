<?php

namespace App\Orchid\Layouts\Assigns;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ItemAssignedToUserTable extends Table
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


    protected function textNotFound(): string
    {
        return 'No items related to user';
    }

    protected function subNotFound(): string
    {
       return  'No items Assigned to user';

    }

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('item_code', 'Item Code'),
            TD::make('name', 'Item Name'),
           TD::make('location', 'Item location')
            ->render(fn ($item) => $item->location->name),
            TD::make('Unlink')
                ->render(fn ($item) => Button::make('Unlink Item')
                    ->icon('bs.link-45deg')
                    ->confirm(__('Are you sure you want to Unlink ' . $item->name . ' located in ' . $item->location->name . ' from this user?'))
                    ->method('unlink', [
                        'pivot_id' => $item->pivot->id,
                    ])
                )
        ];
    }
}
