<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
   use SoftDeletes;

    protected $fillable = ['name', 'description'];

    public function user()
    {
        return $this->hasmany(User::class);
    }
}
