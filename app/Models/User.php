<?php

namespace App\Models;

use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Platform\Models\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_name',
        'photo',
        'phone',
        'DOB',
        'superior',
        'Cedula',
        'emp_number',
        'Hire_date',
        'Schedule_type',
        'task_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions'          => 'array',
        'email_verified_at'    => 'datetime',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
           'id'         => Where::class,
           'name'       => Like::class,
           'email'      => Like::class,
           'updated_at' => WhereDateStartEnd::class,
           'created_at' => WhereDateStartEnd::class,
        'Cedula'      => Like::class,
        'emp_number'  => Like::class,
        'Hire_date'   => Like::class,
        'Schedule_type'   => Like::class,
        'task_id'   => Where::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'email',
        'updated_at',
        'created_at',
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_user')
            ->using(ItemUser::class)
            ->withTimestamps()
            ->wherePivotNull('deleted_at')
            ->withPivot('id', 'deleted_at');
    }
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function tasks()
    {
        return $this->hasOne(Task::class);
    }
}
