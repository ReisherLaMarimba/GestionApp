<?php

namespace App\Orchid\Layouts\Items;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ItemProductMovementsTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = '';


    //MOSTRAR TEXTO SI NO HAY DATOS

    protected function textNotFound(): string
    {
        return __('This item has no movements');
    }

    protected function subNotFound(): string
    {
        return __('This items seems to have no movements, check u are checking the right item');
    }

    protected function IconNotFound(): string
    {
        return 'bs.list-task';
    }


    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [];
    }
}
