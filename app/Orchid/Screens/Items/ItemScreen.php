<?php

namespace App\Orchid\Screens\Items;

use App\Http\Requests\AssignBasicItemRequest;
use App\Http\Requests\ItemRequest;
use App\Jobs\ProcessImagesJob;
use App\Models\Additional;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemUser;
use App\Models\Location;
use App\Models\User;
use App\Orchid\Layouts\Items\ItemTable;
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
        return 'Items List';
    }

    public function description(): ?string
    {
        return 'Here you can view and edit all the items';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Create Item')
                ->modal('itemsModal')
                ->method('create')
                ->icon('plus'),

            ModalToggle::make('Fast Find (FF)')
                ->modal('searchModal')
                ->icon('search'),



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

            Layout::modal('searchModal',
                layout::view('FFI.FindFastItem')
            )->title('Buscar')
            ->closeButton(false)
            ->applyButton(false),


            Layout::modal('itemsModal', Layout::rows([
                Group::make([
                    Input::make('item_code')
                        ->title('Código')
                        ->type('string')
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


            ItemTable::class
        ];



    }





    public function create(ItemRequest $itemRequest){

        $images = $itemRequest->file('images');

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
            Toast::success('The item '  . $itemRequest->item_code . ' was created successfully and images were optimized');

        }
        Toast::success('The item '  . $itemRequest->item_code . ' was created successfully without images');
    }






    public function remove(Request $request): void
    {
        Item::findOrFail($request->get('id'))->delete();

        Toast::info(__('The item was deleted successfully'));
    }

    public function printCode(Request $request)
    {
        $item = Item::findOrFail($request->get('id'));

        // ZPL ajustado para las dimensiones 1.197" x 1.004"
        $zpl = "^XA" .
            "^PW520^LL400^LH0,0" . // Tamaño de la etiqueta: ancho 243 dots, alto 204 dots

            // Texto del código centrado arriba
            "^FX Campo para el elemento 'CODE'" .
            "^^FO130,30^A0N,30,30^FD" . $item->item_code . "^FS" .

            // Código QR apuntando a la URL
            "^FX Código QR que apunta al sistema" .
            "^FO180,40^BQN,2,3^FDLA,https://gestionapp-main-xkaoxd.laravel.cloud/admin^FS" .

            // Descripción del ítem debajo del QR
            "^FX Descripción del artículo" .
            "^FO130,150^A0N,30,30^FD" . $item->description . "^FS" .

            "^XZ";

//        ^FO130,30^A0N,30,30^FD CMAX-PC001^FS  ; Texto superior centrado
//
//        ^FO180,10^BQN,2,4^FDLA,CMAX-PC001^FS  ; Código QR alineado debajo del texto
//
//        ^FO130,150^A0N,30,30^FD DESCRIPTION^FS  ; Descripción alineada debajo del QR
//
//

        // Ruta de la impresora compartida (ajustar si es necesario)
        $printerPath = "\\\\localhost\\ZebraPrinter";

        // Guardamos temporalmente el ZPL en un archivo
        $tmpFile = tempnam(sys_get_temp_dir(), 'zpl');
        file_put_contents($tmpFile, $zpl);

        // Envía el archivo a la impresora
        exec("cmd /c COPY /B \"$tmpFile\" \"$printerPath\"");

        // Elimina el archivo temporal
        unlink($tmpFile);

        toast::success('Código impreso correctamente');
    }





}
