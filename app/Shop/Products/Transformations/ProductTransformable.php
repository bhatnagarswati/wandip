<?php

namespace App\Shop\Products\Transformations;

use App\Shop\Products\Product; 
use App\Shop\Stores\Store; 
use Illuminate\Support\Facades\Storage;

trait ProductTransformable
{
    /**
     * Transform the product
     *
     * @param Product $product
     * @return Product
     */
    protected function transformProduct(Product $product)
    {

       
        $prod = new Product;
        $prod->id = (int) $product->id;
        $prod->name = $product->name;
        $prod->sku = $product->sku;
        $prod->slug = $product->slug;
        $prod->description = $product->description;
        $prod->cover = asset("storage/app/public/$product->cover");
        $prod->quantity = $product->quantity;
        $prod->price = $product->price;
        $prod->status = $product->status;
        $prod->weight = (float) $product->weight;
        $prod->mass_unit = $product->mass_unit;
        $prod->serviceOfferedType = $product->serviceOfferedType;
        $prod->sale_price = $product->sale_price;
        $prod->brand_id = (int) $product->brand_id;
        $prod->store_id = (int) $product->store_id;
        $prod->delivery_charges = (float)  $product->delivery_charges;
        $prod->servicer = "";
        @$storeInfo = Store::with('service_provider')->where('id', $product->store_id)->first();
        if ($storeInfo) {
            $prod->servicer =  @$storeInfo->service_provider->name;
            
        }
        return $prod;
    }
}
