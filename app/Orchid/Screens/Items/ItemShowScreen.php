<?php

namespace App\Orchid\Screens\Items;

use App\Models\Additional;
use App\Models\Item;
use App\Orchid\Layouts\Items\ItemProductMovementsTable;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Repository;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;


class ItemShowScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */

    public $item;

    public function query(Item $item): array
    {
        $this->item = $item;

        // Decode the JSON and handle null or invalid data
        $image = json_decode($item->image, true) ?? [];


        $audits = $item->audits;


        return [
            'item' => $item,
            'image' => $image,
            'audits' => $item->audits,
            'additionals' => $item->additionals ?? [],

        ];
    }



    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->item->name;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Back to list')
                ->icon('bs.arrow-left')
                ->method('back'),

            Button::make('Edit')
                ->icon('bs.pencil')
                ->method('redirectToEdit'),

            ModalToggle::make('Movements')
                ->modal('auditModal') // Name of the modal
                ->icon('list'),

            Button::make('Print tag')
            ->icon('printer')
            ->method('printTAG')

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


            Layout::split([
                Layout::view('Items.show-image'),
                Layout::legend('item', [
                    Sight::make('id')->popover('Unique identifier for the item'),
                    Sight::make('name', 'Name'),
                    Sight::make('item_code', 'Item Code'),
                    Sight::make('description', 'Description'),
                    Sight::make('weight', 'Weight')->render(fn (Item $item) => $item->weight ? $item->weight . ' kg' : 'N/A'),
                    Sight::make('stock', 'Stock')->render(fn (Item $item) => $item->stock ?? 'N/A'),
                    Sight::make('min_quantity', 'Minimum Quantity'),
                    Sight::make('max_quantity', 'Maximum Quantity'),
                    Sight::make('comments', 'Comments')->render(fn (Item $item) => $item->comments ?? 'No comments available'),
                    Sight::make('status', 'Status')->render(fn (Item $item) => $item->status === 'En Inventario'
                        ? '<i class="text-success">●</i> On Inventory'
                        : '<i class="text-danger">●</i> Assigned'),
                    Sight::make('category_id', 'Category')->render(fn (Item $item) => $item->category->name ?? 'Unknown Category'),
                    Sight::make('location_id', 'Location')->render(fn (Item $item) => $item->location->name ?? 'Unknown Location'),
                    Sight::make('Additionals')->render(fn (Item $item) =>
                    !empty($item->additionals)
                        ? Additional::whereIn('id', $item->additionals)
                        ->get()
                        ->map(fn ($additional) => isset($additional->license)
                            ? "<strong>{$additional->name}</strong> - Licencia: {$additional->license}"
                            : "<strong>{$additional->name}</strong>")
                        ->implode('<br>')
                        : '<em>No additionals available</em>'
                    ),


                ]),

                ])->ratio('40/60'),


            ItemProductMovementsTable::class,
            Layout::modal('auditModal', [
                Layout::table('audits', [
                    TD::make('user', 'Usuario')
                        ->render(fn($audit) => e($audit->user->name ?? 'Sistema')),

                    TD::make('field', 'Campos Modificados')
                        ->render(fn($audit) =>
                        collect(array_keys($audit->getModified()))
                            ->map(fn($field) => "<span class='badge bg-light text-dark me-1'>{$field}</span>")
                            ->implode('')
                        )->width('250px'),

                    TD::make('old', 'Valor Anterior')
                        ->render(fn($audit) => $this->formatChangesAsList($audit->old_values)),

                    TD::make('new', 'Nuevo Valor')
                        ->render(fn($audit) => $this->formatChangesAsList($audit->new_values)),

                    TD::make('date', 'Fecha de Modificación')
                        ->render(fn($audit) => $audit->created_at->format('Y-m-d H:i')),
                ])
            ])->title('Historial de Cambios')->size(Modal::SIZE_LG)

        ];


    }

    Public function redirectToEdit(){
        return redirect()->route('platform.items.edit', $this->item->id);
    }

    private function formatChanges(array $changes): string
    {
        return collect($changes)
            ->map(fn($value, $field) => "<strong>{$field}:</strong> {$value}")
            ->implode('<br>');
    }

    private function formatChangesAsList(array $changes = []): string
    {
        if (empty($changes)) return '<em>Sin datos</em>';

        return '<ul class="list-unstyled mb-0">' . collect($changes)
                ->map(fn($value, $field) => "<li><strong>{$field}:</strong> " . e($value) . "</li>")
                ->implode('') . '</ul>';
    }

    public function back(){
        return redirect()->route('platform.items');
    }

    Public function printTAG(){
        $item = Item::findOrFail($this->item->id);

        // ZPL ajustado para las dimensiones 1.197" x 1.004"
        $zpl = "^XA" .
            "^PW243^LL204^LH0,0" . // Tamaño de la etiqueta: ancho 243 dots, alto 204 dots

            // Campo para el texto del código centrado
            "^FX Campo para el elemento 'CODE'" .
            "^FO20,10^FWN^CF0,30^FB243,1,C,0^FD" . $item->item_code . "^FS" .

            // Campo para el código de barras basado en el código del ítem
            "^FX Campo para el código de barras del código" .
            "^FO20,50^FWN^BY1,2,50^BCN,50,N,N" .
            "^FD" . $item->item_code . "^FS" .

            "^XZ";

        // Ruta de la impresora compartida (ajustar si es necesario)
        $printerPath = "\\\\localhost\\ZEBRAZD410";

        // Guardamos temporalmente el ZPL en un archivo
        $tmpFile = tempnam(sys_get_temp_dir(), 'zpl');
        file_put_contents($tmpFile, $zpl);

        // Envía el archivo a la impresora
        exec("COPY /B \"$tmpFile\" \"$printerPath\"");

        // Elimina el archivo temporal
        unlink($tmpFile);

        toast::success('Código impreso correctamente');
    }




}
