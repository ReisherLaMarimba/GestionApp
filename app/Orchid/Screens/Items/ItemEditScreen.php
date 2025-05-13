<?php

namespace App\Orchid\Screens\Items;

use App\Http\Requests\ItemRequest;
use App\Jobs\ProcessImagesJob;
use App\Models\Additional;
use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\Request;
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

            Button::make('Print Code')
                ->icon('print')
                ->method('printCode'),

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
