<?php

namespace App\Orchid\Screens\Locations;

use App\Models\Location;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class LocationEditScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */

    public $location;

    public function query(Location $location): array
    {
        $this->location = $location;
        return [
            'location' => $location,
        ];
    }



    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Editando ubicacion: ' . $this->location->name;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save Changes')
                ->icon('save')
                ->method('update'),
            Button::make('Cancel')
                ->icon('close')
                ->method('cancelEdit'),
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

            Layout::rows([
                    Input::make('location.name')
                        ->title('Nombre')
                        ->value($this->location->name)
                        ->required(),

                    Input::make('location.address')
                        ->title('Direccion')
                        ->value($this->location->address)
                        ->required(),

                    Input::make('location.phone')
                        ->title('Telefono')
                        ->value($this->location->phone)
                        ->required(),

                    Input::make('location.email')
                        ->title('Email')
                        ->value($this->location->email)
                        ->required(),
                ]),


        ];
    }

    public function update(Location $location, Request $request){
        $validated = $request->validate([
            'location.name' => 'required|string|max:255',
            'location.address' => 'required|string|max:255',
            'location.phone' => 'required|string|max:255',
            'location.email' => 'required|string|max:255',
        ]);

        $location->update([
            'name' => $validated['location']['name'],
            'address' => $validated['location']['address'],
            'phone' => $validated['location']['phone'],
            'email' => $validated['location']['email'],
        ]);

        return redirect()->route('platform.locations');
    }

    public function cancelEdit()
    {
        return redirect()->route('platform.locations');
    }
}
