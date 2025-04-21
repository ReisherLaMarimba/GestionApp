<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemUser extends Pivot
{
   use SoftDeletes;

    protected $table = 'item_user';

    protected $dates = ['deleted_at'];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function task()
    {
        return $this->hasOneThrough(Task::class, User::class, 'id', 'id', 'user_id', 'task_id');
    }





}
