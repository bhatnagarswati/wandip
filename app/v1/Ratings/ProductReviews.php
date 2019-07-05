<?php

namespace App\v1\Ratings;

use Illuminate\Database\Eloquent\Model;
use App\v1\Products\Product;
use App\v1\Customers\Customer;

class ProductReviews extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_reviews';

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
    protected $fillable = ['productId', 'customerId', 'reviewTitle', 'reviewDescription', 'customerRating', 'status'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'productId');
    }
    
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerId')->select(array('id', 'name'));
    }
    
}
