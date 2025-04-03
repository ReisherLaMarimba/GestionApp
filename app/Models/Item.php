<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Item extends Model
{
    use AsSource, softdeletes, Filterable;

    protected $fillable = ['name','item_code', 'description', 'weight', 'min_quantity', 'max_quantity','category_id', 'location_id', 'stock', 'images', 'damage_images', 'comments', 'status'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function inventoryEntries()
    {
        return $this->hasMany(InventoryEntry::class);
    }

    public function inventoryOutbounds()
    {
        return $this->hasMany(InventoryOutbound::class);
    }
}
