<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Category extends Model
{
 use AsSource, softdeletes, Filterable;

 protected $fillable = ['name', 'description', 'risk'];

 public function items()
 {
     return $this->hasMany(Item::class);
 }

}
