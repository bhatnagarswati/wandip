<?php

namespace App\Shop\Routers;

use Illuminate\Database\Eloquent\Model;
use App\Shop\RouterInformations\RouterInformation;
use App\Shop\Drivers\Driver;
use App\Shop\Servicers\Servicer;

class Router extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'routers';
    protected $dates = ['deliveryDate'];
    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['servicerId', 'driverId', 'deliveryDate', 'departureTime',  'timeNote', 'arrivalTime','volumeContained', 'price', 'priceUnit', 'notifyUsers', 'status', 'languageType'];

    public function routeInfo(){

      return $this->hasMany(RouterInformation::class, 'routerId');
        
    }

    public function driver(){

      return $this->belongsTo(Driver::class, 'driverId');
        
    }

    public function servicer(){

      return $this->belongsTo(Servicer::class, 'servicerId');
        
    }
}
