<?php

namespace App\Orchid\Screens\Items;

use App\Http\Requests\ItemRequest;
use App\Jobs\ProcessImagesJob;
use App\Models\Additional;
use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ItemEditScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */

    public $item;

    public function query(Item $item): iterable
    {
        $this->item = $item;
        return [
            'item' => $item,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Editing : ' . $this->item->name;
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
        //NO RECUERDO QUE DIABLO IBA A HACER XD
        return [
            layout::rows([
                Group::make([
                    Input::make('item_code')
                        ->title('Código')
                        ->value($this->item->item_code)
                        ->placeholder('Inserte el código del artículo'),

                    Input::make('name')
                        ->title('Nombre')
                        ->value($this->item->name)
                        ->placeholder('Inserte el nombre del artículo'),
                ]),
                Group::make([
                    Input::make('weight')
                        ->title('Peso')
                        ->type('number')
                        ->value($this->item->weight)
                        ->placeholder('Inserte el peso del artículo en LIBRAS')
                        ->popover('Inserte el peso del artículo en LIBRAS'),

                    Input::make('min_quantity')
                        ->title('Min. Cant.')
                        ->type('number')
                        ->value($this->item->min_quantity)
                        ->placeholder('Inserte la cantidad mínima del artículo')
                        ->popover('Inserte la cantidad mínima del artículo'),

                    Input::make('max_quantity')
                        ->title('Max. Cant.')
                        ->value($this->item->max_quantity)
                        ->type('number')
                        ->placeholder('Inserte la cantidad máxima del artículo')
                        ->popover('Inserte la cantidad máxima del artículo'),

                    Input::make('stock')
                        ->title('Cantidad en Almacén')
                        ->disabled()
                        ->type('number')
                        ->value($this->item->stock)
                        ->placeholder('Inserte la cantidad en almacén del artículo')
                        ->popover('Inserte la cantidad en almacén del artículo'),
                ]),
                Group::make([
                    TextArea::make('description')
                        ->title('Descripción')
                        ->value($this->item->description)
                        ->placeholder('Inserte la descripción del artículo')
                        ->help('Inserte la descripción del artículo')
                        ->rows(6),

                    Input::make('images')
                        ->type('file')
                        ->title('Multiple files input example')
                        ->multiple()
                        ->horizontal(),
                ]),
                Group::make([
                    Input::make('comments')
                        ->title('Comentarios')
                        ->value($this->item->comments)
                        ->placeholder('Inserte un comentario del artículo')
                        ->help('Inserte la descripción del artículo'),

                    Select::make('additionals')
                        ->title('Adicionales')
                        ->fromModel(Additional::class, 'name')
                        ->multiple()
                        //Can see only if this item is CPU category
                        ->canSee($this->item->category->name == 'CPU')
                        ->value($this->item->additionals)
                        ->placeholder('Aditionales del artículo')
                        ->help('Elija la adtionales del artículo'),


                    ]),


                Group::make([
                    Select::make('category')
                        ->title('Categoría')
                        ->fromModel(category::class, 'name')
                        ->value($this->item->category)
                        ->placeholder('Categoría del artículo')
                        ->help('Elija la categoría del artículo'),

                    Select::make('location')
                        ->title('Ubicación')

                        ->fromModel(Location::class, 'name')
                        ->value($this->item->location)
                        ->placeholder('Ubicación del artículo')
                        ->help('Elija la ubicación del artículo'),
                ])
            ])
        ];
    }
    public function update(ItemRequest $itemRequest, Item $item)
    {
        DB::transaction(function () use ($itemRequest, $item) {
            $data = $itemRequest->only([
                'item_code',
                'name',
                'weight',
                'min_quantity',
                'max_quantity',
                'description',
                'comments',
                'category',
                'location',
                'additionals'
            ]);

            // Mapeo de campos para la base de datos
            $data['category_id'] = $data['category'];
            $data['location_id'] = $data['location'];
            unset($data['category'], $data['location']); // Remueve los originales


            $item->update($data);

            // Verificar si se han enviado imágenes
            if ($itemRequest->hasFile('images')) {
                $image = $itemRequest->file('images')[0]; // Solo la primera imagen
                $imagePath = $image->store('/images/temp');

                ProcessImagesJob::dispatch($imagePath, $item->id, 'images');
                Toast::info('Item actualizado exitosamente, y las imágenes están siendo procesadas.');
            } else {
                Toast::info('Item actualizado exitosamente, sin imágenes');
            }
        });
    }
    public function cancelEdit()
    {
        return redirect()->route('platform.items');
    }
}
