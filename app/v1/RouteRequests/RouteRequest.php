<?php
namespace App\v1\RouteRequests;

use Illuminate\Database\Eloquent\Model;
use App\v1\Drivers\Driver;
use App\v1\Servicers\Servicer;
use App\v1\Customers\Customer;
use App\v1\RouterInformations\RouterInformation;


class RouteRequest extends Model
{
	 
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['routeId', 'customerId',  'servicerId', 'driverId','requestedAddress', 'requestedMassUnit',  'requestedUnitPrice' , 'requestedRoute', 'requestedQty', 'customerLat', 'customerLong', 'requestedDate', 'estimatedCalPrice', 'status', 'markedStatus', 'languageType'];

    // Driver Info
    public function driverinfo()
    {
        return $this->belongsTo(Driver::class, 'driverId');
    }

    // Service Providers
    public function servicerinfo()
    {
        return $this->belongsTo(Servicer::class, 'servicerId');
    }

    // Customer Providers
    public function customerinfo()
    {
        return $this->belongsTo(Customer::class, 'customerId');
    }  
    // Driver Info
    public function routerInfo()
    {
        return $this->belongsTo(RouterInformation::class, 'requestedRoute');
    }

}
