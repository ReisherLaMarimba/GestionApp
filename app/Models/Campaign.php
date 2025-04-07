<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Campaign extends Model
{
    use HasFactory, asSource, Filterable, Attachable, SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'status',
        'billiable_hours',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

}
