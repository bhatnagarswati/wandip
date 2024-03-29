<?php

namespace App\v1\Couriers;

use App\v1\Orders\Order;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'url',
        'is_free',
        'cost',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
