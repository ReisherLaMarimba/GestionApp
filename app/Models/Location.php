<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Screen\AsSource;
use OwenIt\Auditing\Contracts\Auditable;

class Location extends Model implements Auditable
{
    use AsSource, softdeletes, Filterable, \OwenIt\Auditing\Auditable;

    protected $fillable = ['name', 'address', 'phone', 'email'];

    protected $allowedFilters = [
           'id'         => Where::class,
           'name'       => Like::class,
           'address'    => Like::class,
           'phone'      => Like::class,
           'email'      => Like::class,
           'updated_at' => WhereDateStartEnd::class,
           'created_at' => WhereDateStartEnd::class,
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
