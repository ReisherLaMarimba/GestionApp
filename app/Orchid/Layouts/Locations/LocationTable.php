<?php

namespace App\Orchid\Layouts\Locations;

use App\Models\Location;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class LocationTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'locations';

    protected function iconNotFound(): string{
        return 'geo-alt';
    }

    protected function textNotFound(): string
    {
        return __('Por el momento no hay ubicaciones');
    }

    protected function subNotFound(): string
    {
        return '';
    }


    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name','Nombre'),
            TD::make('address', 'Direccion'),
            TD::make('phone', 'Telefono'),
            TD::make('email', 'Email'),
            TD::make('actions')
            ->render(function (Location $location) {
                return Button::make('Eliminar')
                    ->type(Color::WARNING)
                    ->confirm('¿Estas seguro que deseas eliminar esta ubicacion?')
                    ->method('delete',['location' => $location->id]);
            }),
            TD::make('Actions', 'Editar')
                ->render(function ($location) {
                   return Button::make('Editar')
                       ->method('redirectToEdit')
                       ->parameters(['location' => $location->id])
                       ->novalidate();
                }),


        ];
    }
}
