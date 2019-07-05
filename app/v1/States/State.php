<?php

namespace App\v1\States;

use App\v1\Countries\Country;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    public $fillable = [
        'state',
        'state_code'
    ];

    public $timestamps = false;

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}