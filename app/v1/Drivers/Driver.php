<?php

namespace App\v1\Drivers;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'drivers';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'driverId';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['servicerId','driverEmail', 'password', 'firstName', 'lastName', 'contactNumber', 'address', 'licenceNumber', 'quickBlockId', 'idProof' , 'driverPic', 'status', 'driverLat', 'driverLong'];

     
}
