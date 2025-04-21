<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use OwenIt\Auditing\Contracts\Auditable;

class Item extends Model implements Auditable
{
    use AsSource, Softdeletes, Filterable, \OwenIt\Auditing\Auditable;
    protected $fillable = ['name','item_code', 'description', 'weight', 'min_quantity', 'max_quantity','category_id', 'location_id', 'stock', 'images', 'damage_images', 'comments', 'status', 'additionals','assigned_to'];


    protected $casts = [
        'additionals' => 'array',
        'assigned_to' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'item_user')
            ->using(ItemUser::class)
            ->withTimestamps()
            ->withPivot('id', 'deleted_at');
    }



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

//    public function user()
//    {
//        return $this->hasMany(User::class);
//    }

    public function additionals()
    {
        return $this->hasMany(Additional::class, 'item_additional', 'item_id', 'additional_id');
    }



}
