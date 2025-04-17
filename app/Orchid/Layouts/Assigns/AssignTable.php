<?php

namespace App\Orchid\Layouts\Assigns;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class AssignTable extends Table
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

    protected function iconNotFound(): string{
        return 'collection';
    }

    protected function textNotFound(): string
    {
        return __('By the moment there are no computers assigned');
    }

    protected function subNotFound(): string
    {
        return 'Once you assign a computer to a user, it will appear here';
    }

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
   return[
       TD::make('item_code','Code'),
       TD::make('name','Name'),
       TD::make('Location','Location')
       ->render(function($item){
           return $item->location->name;
       }),
       TD::make('assigned_to','Assigned To')->render(function($item){
           return $item->users->pluck('name')->implode('<strong> & </strong>');
       }),
//       TD::make('location', 'Location'),

   ];
    }
}
