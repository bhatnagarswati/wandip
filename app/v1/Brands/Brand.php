<?php

namespace App\v1\Brands;

use App\v1\Products\Product;
use Illuminate\Database\Eloquent\Model;
use App\v1\Servicers\Servicer;

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
