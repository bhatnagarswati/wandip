<?php

namespace App\Shop\Brands;

use App\Shop\Products\Product;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['name', 'status', 'servicerId', 'adminId', 'languageType'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function servicers()
    {
        return $this->belongsTo(Servicer::class, 'servicerId');
    }
 	   
}
