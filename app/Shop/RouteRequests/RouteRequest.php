<?php
namespace App\Shop\RouteRequests;

use Illuminate\Database\Eloquent\Model;
use App\Shop\Drivers\Driver;
use App\Shop\Servicers\Servicer;
use App\Shop\Customers\Customer;


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

}
