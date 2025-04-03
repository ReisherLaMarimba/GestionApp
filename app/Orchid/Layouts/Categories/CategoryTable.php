<?php

namespace App\Orchid\Layouts\Categories;

use App\Models\Category;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Button;

class CategoryTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'categories';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */

    protected function iconNotFound(): string{
        return 'table';
    }

    protected function textNotFound(): string
    {
        return __('Por el momento no hay categorias');
    }

    protected function subNotFound(): string
    {
        return '';
    }




    protected function columns(): iterable
    {
        return [
            TD::make('name'),
            TD::make('description'),
            TD::make('risk'),
            TD::make('Actions')
            ->render(function (Category $category) {
                return Button::make('Eliminar')
                    ->confirm('¿Estas seguro que deseas eliminar esta categoria?')
                    ->method('delete',['category' => $category->id]);
            }),
            TD::make('Actions', 'Editar')
                ->render(function ($category) {
                   return Button::make('Editar')
                       ->method('redirectToEdit')
                       ->parameters(['category' => $category->id])
                       ->novalidate();
                }),



        ];
    }
}
