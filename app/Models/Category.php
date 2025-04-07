<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use OwenIt\Auditing\Contracts\Auditable;

class Category extends Model implements Auditable
{
 use AsSource, softdeletes, Filterable, \OwenIt\Auditing\Auditable;

 protected $fillable = ['name', 'description', 'risk'];

 public function items()
 {
     return $this->hasMany(Item::class);
 }

}
