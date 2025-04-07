<?php

namespace App\Orchid\Screens\Items;

use App\Http\Requests\AssignBasicItemRequest;
use App\Http\Requests\ItemRequest;
use App\Jobs\ProcessImagesJob;
use App\Models\Additional;
use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use App\Models\User;
use App\Orchid\Layouts\items\ItemTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Attach;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ItemScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'items' => Item::filters()->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Articulos';
    }

    public function description(): ?string
    {
        return 'Aqui podes ver y editar todos los articulos';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Crear Articulo')
                ->modal('itemsModal')
                ->method('create')
                ->icon('plus'),

            Modaltoggle::make('Assign Basic Items')
                ->modal('assignBasicItemsModal')
                ->method('assignBasicItems')
                ->icon('list')
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

            Layout::modal('itemsModal', Layout::rows([
                Group::make([
                    Input::make('item_code')
                        ->title('Código')
                        ->placeholder('Inserte el código del artículo'),

                    Input::make('name')
                        ->title('Nombre')
                        ->placeholder('Inserte el nombre del artículo'),
                ]),
                Group::make([
                    Input::make('weight')
                        ->title('Peso')
                        ->type('number')
                        ->placeholder('Inserte el peso del artículo en LIBRAS')
                        ->popover('Inserte el peso del artículo en LIBRAS'),

                    Input::make('min_quantity')
                        ->title('Min. Cant.')
                        ->type('number')
                        ->placeholder('Inserte la cantidad mínima del artículo')
                        ->popover('Inserte la cantidad mínima del artículo'),

                    Input::make('max_quantity')
                        ->title('Max. Cant.')
                        ->type('number')
                        ->placeholder('Inserte la cantidad máxima del artículo')
                        ->popover('Inserte la cantidad máxima del artículo'),

                    Input::make('stock')
                        ->title('Cantidad en Almacén')
                        ->type('number')
                        ->placeholder('Inserte la cantidad en almacén del artículo')
                        ->popover('Inserte la cantidad en almacén del artículo'),
                ]),
                Group::make([
                    TextArea::make('description')
                        ->title('Descripción')
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
                    input::make('comments')
                        ->title('Comentarios')
                        ->placeholder('Inserte un comentario del artículo')
                        ->help('Inserte la descripción del artículo'),

                    Select::make('additionals')
                        ->title('Aditionales')
                        ->fromModel(Additional::class, 'name')
                        ->multiple()
                        ->placeholder('Aditionales del artículo')
                        ->help('Elija la adtionales del artículo'),
                    ]),

                 Group::make([
                     Select::make('category')
                         ->title('Categoría')
                         ->fromModel(category::class, 'name')
                         ->placeholder('Categoria del artículo')
                         ->help('Elija la categoría del artículo'),

                     Select::make('location')
                         ->title('Ubicacion')
                         ->fromModel(Location::class, 'name')
                         ->placeholder('Categoria del artículo')
                         ->help('Elija la ubicacion del artículo'),
                 ])

            ]))
                ->title('Crear Artículo')
                ->size(Modal::SIZE_LG),

            Layout::modal('assignBasicItemsModal', Layout::rows([
                Group::make([
                    Select::make('items_id')
                        ->title('Basic Items')
                        ->fromModel(
                            Item::whereHas('category', function ($query) {
                                $query->where('name', '!=', 'CPU');
                            }),
                            'name', 'id'
                        )
                        ->multiple()
                        ->placeholder('Basic Items')
                        ->help('Elija los basic items'),

                    Select::make('user_id')
                        ->title('Select User')
                        ->fromModel(User::class, 'name')
                        ->multiple()
                        ->placeholder('Select User')
                        ->help('Elija los usuarios')



        ]),


            ]))->title('Assign Basic Items'),




            ItemTable::class
        ];



    }

    public function create(ItemRequest $itemRequest){

        $images = $itemRequest->file('images');

//        dd($itemRequest->ALL());

        $item = Item::create([
            'item_code' => $itemRequest->item_code,
            'name' => $itemRequest->name,
            'weight' => $itemRequest->weight,
            'min_quantity' => $itemRequest->min_quantity,
            'max_quantity' => $itemRequest->max_quantity,
            'stock' => $itemRequest->stock,
            'description' => $itemRequest->description,

            'category_id' => $itemRequest->category,
            'location_id' => $itemRequest->location,
            'additionals' => $itemRequest->additionals,

            'comments' => $itemRequest->comments,
//            'images' => [],
            'status' => 'En Inventario'
            ]);

        if($itemRequest->hasFile('images')){
            foreach($images as $image){

                //IMAGEN EN RUTA TEMPORAL
                $imagePath = $image->store('/images/temp');
                $column = 'images';

                ProcessImagesJob::dispatch($imagePath, $item->id, $column);
            }


        }
        Toast::success('El artículo se ha creado correctamente, las imagenes se estan optimizando');
    }

    public function assignBasicItems(AssignBasicItemRequest $assignBasicItemRequest)
    {
        DB::transaction(function () use ($assignBasicItemRequest) {
            $itemsIds = $assignBasicItemRequest->get('items_id'); // Array of item IDs
            $userIds = $assignBasicItemRequest->get('user_id'); // Array of user IDs

            // Ensure the number of user IDs does not exceed 2
            if (count($userIds) > 2) {
                throw new \Exception('No se pueden asignar más de 2 usuarios a un ítem.');
            }

            foreach ($itemsIds as $itemId) {
                $item = Item::findOrFail($itemId); // Find item or fail

                // Replace the 'assigned_to' column with the new user IDs
                $item->update([
                    'assigned_to' => json_encode($userIds), // Save users as JSON
                ]);
            }
        });

        Toast::success('Usuarios asignados correctamente a los ítems seleccionados.');

    }




    public function remove(Request $request): void
    {
        Item::findOrFail($request->get('id'))->delete();

        Toast::info(__('Item eliminado'));
    }
}
