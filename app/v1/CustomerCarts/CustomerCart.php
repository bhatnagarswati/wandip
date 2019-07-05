<?php

namespace App\v1\CustomerCarts;

use Illuminate\Database\Eloquent\Model;
use App\v1\CustomerCarts\CustomerCart;
use App\v1\Customers\Customer;
class CustomerCart extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customer_carts';

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
    protected $fillable = ['customerId', 'cartId', 'cartContent'];

    public function customerInfo()
    {
        return $this->belongsTo(Customers::class, 'customerId');
    }
}
