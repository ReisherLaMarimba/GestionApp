<?php

namespace App\Orchid\Screens\Items;

use App\Models\Item;
use App\Orchid\Layouts\items\ItemProductMovementsTable;
use Illuminate\Http\Request;
use Orchid\Screen\Repository;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;


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
        $image = json_decode($item->image, true) ?? []; // Fallback to empty array if null


        return [
            'item' => $item,
            'image' => $image,
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
        return [];
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
                Layout::view('items.show-image'),
//                Layout::view('items.show-info'),
                Layout::legend('item', [
                    Sight::make('id')->popover('Unique identifier for the item'),
                    Sight::make('name', 'Name'),
                    Sight::make('Item_code', 'Item Code'),
                    Sight::make('description', 'Description'),
                    Sight::make('weight', 'Weight')->render(fn (Item $item) => $item->weight ? $item->weight . ' kg' : 'N/A'),
                    Sight::make('stock', 'Stock')->render(fn (Item $item) => $item->stock ?? 'N/A'),
                    Sight::make('min_quantity', 'Minimum Quantity'),
                    Sight::make('max_quantity', 'Maximum Quantity'),
                    Sight::make('comments', 'Comments')->render(fn (Item $item) => $item->comments ?? 'No comments available'),
                    Sight::make('status', 'Status')->render(fn (Item $item) => $item->status === 'En Inventario'
                        ? '<i class="text-success">●</i> En Inventario'
                        : '<i class="text-danger">●</i> Asignado'),
                    Sight::make('category_id', 'Category')->render(fn (Item $item) => $item->category->name ?? 'Unknown Category'),
                    Sight::make('location_id', 'Location')->render(fn (Item $item) => $item->location->name ?? 'Unknown Location'),
                    Sight::make('created_at', 'Created')->popover('Date when the item was added'),
                    Sight::make('updated_at', 'Updated')->popover('Last updated date for the item'),
                ])

                ])->ratio('40/60'),


                    ItemProductMovementsTable::class


            ];

    }
}
