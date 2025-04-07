<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Additional extends Model
{
    use AsSource, softdeletes, Filterable;

    protected $fillable = ['name', 'license', 'description'];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_additional', 'additional_id', 'item_id');
    }

}


