<?php

namespace App\v1\Routers;

use Illuminate\Database\Eloquent\Model;
use App\v1\RouterInformations\RouterInformation;
use App\v1\Drivers\Driver;
use App\v1\Servicers\Servicer;

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


       /*  $request = request();
        
        if (!empty(@$request->search_key)) {


           $keyword = $request->search_key;
           dd($request->search_key);
            return  $this->hasMany(RouterInformation::class, 'routerId')->where('router_informations.location', 'LIKE', "%$keyword%");
        } */
      return $this->hasMany(RouterInformation::class, 'routerId');
        
    }

    public function driver(){

      return $this->belongsTo(Driver::class, 'driverId');
        
    }

    public function servicer(){

      return $this->belongsTo(Servicer::class, 'servicerId');
        
    }
}
