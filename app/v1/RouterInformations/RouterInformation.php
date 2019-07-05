<?php

namespace App\v1\RouterInformations;

use Illuminate\Database\Eloquent\Model;

class RouterInformation extends Model
{

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['routerId', 'location', 'city', 'locationLat', 'locationLong', 'sortOrder'];

  
}
