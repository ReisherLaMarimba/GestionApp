<?php

namespace App\Orchid\Screens\Locations;

use App\Http\Requests\LocationsRequest;
use App\Models\Location;
use App\Orchid\Layouts\Locations\LocationTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class LocationScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'locations' => Location::filters()->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Ubicaciones';
    }

    public function description(): ?string{
        return 'Aqui podras ver y editar todas las ubicaciones';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Crear Ubicacion')
            ->modal('locationsModal')
            ->method('create')
            ->icon('plus')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::modal('locationsModal', Layout::rows([
                Input::make('name')->label('Nombre')
                ->required()
                ->title('Nombre de la ubicacion')
                ->placeholder('Nombre de la ubicacion'),

                Input::make('address')->label('Direccion')
                ->required()
                ->title('Direccion de la ubicacion')
                ->placeholder('Direccion de la ubicacion'),

                Input::make('phone')->label('Telefono')
                ->required()
                ->title('Telefono de la ubicacion')
                ->placeholder('Telefono de la ubicacion'),

                Input::make('email')->label('Email')
                ->required()
                ->title('Email de la ubicacion')
                ->placeholder('Email de la ubicacion'),
            ]))->title('Crear ubicacion'),


            LocationTable::class
        ];
    }

    public function create( LocationsRequest $request){

        $request->validate($request->rules());

        Location::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        Toast::success('Ubicacion creada exitosamente');
    }

    public function delete(Location $location){
        $location->delete();
        Toast::success('Ubicacion eliminada exitosamente');
    }

    public function redirectToEdit(Location $location){
        $Location = Location::findOrFail($location->id);

        return redirect()->route('platform.locations.edit', [$Location->id]);
    }
}
