<?php

namespace App\Http\Resources\Item;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);

        return[
            'id' => $this->id,
            'item_code' => $this->item_code,
            'name' => $this->name,
            'description' => $this->description,
            'weight' => $this->weight,
            'min_quantity' => $this->min_quantity,
            'max_quantity' => $this->max_quantity,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
            'location_id' => $this->location->name,
            'images' => $this->images,
            'damage_images' => $this->damage_images,
            'comments' => $this->comments,
            'status' => $this->status,
            'additionals' => $this->additionals,
            'assigned_to' => $this->assigned_to,
        ];

    }
}
