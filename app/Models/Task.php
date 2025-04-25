<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Screen\AsSource;

class Task extends Model
{
   use SoftDeletes, asSource;

    protected $fillable = ['name', 'description'];

    public function users()
    {
        return $this->hasmany(User::class);
    }
}
