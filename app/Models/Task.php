<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Screen\AsSource;

class Task extends Model
{
   use SoftDeletes, asSource;

   protected $table = 'Tasks';

    protected $fillable = ['name', 'description','status',"billable_hours"];

    public function users()
    {
        return $this->hasmany(User::class);
    }
}
