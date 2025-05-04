<?php

namespace App\Orchid\Layouts\Locations;

use App\Models\Location;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
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
            TD::make(__('actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->cantHide()
                ->render(fn (Location $location) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        Button::make('Eliminar')
                            ->icon('bs.trash3')
                            ->confirm('¿Estas seguro que deseas eliminar esta ubicacion?')
                            ->method('delete',['location' => $location->id]),

                        Button::make('Editar')
                            ->method('redirectToEdit')
                            ->icon('bs.pencil')
                            ->parameters(['location' => $location->id])
                            ->novalidate()
                    ]))


        ];
    }
}
