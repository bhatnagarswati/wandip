<?php

namespace App\v1\Products\Transformations;

use App\v1\Products\Product;
use Illuminate\Support\Facades\Storage;
use App\v1\Stores\Store;
use App\v1\Brands\Brand;


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

        $extraImages = [];
        $extraimages =  $product->images()->get(['src']);
        if(!empty($extraimages)){
            foreach ($extraimages as $key => $extraimg) {
               $extraImages[] =  asset("storage/app/public/". $extraimg['src']);
            }
        }


        $prod = new Product;
        $prod->id = (int) $product->id;
        $prod->name = $product->name;
        $prod->sku = $product->sku;
        $prod->slug = $product->slug;
        $prod->description = $product->description  <> null ? $product->description : "";
        $prod->cover = asset("storage/app/public/$product->cover");
        $prod->quantity = $product->quantity;
        $prod->price = $product->price;
        $prod->status = $product->status;
        $prod->weight = (float) $product->weight;
        $prod->mass_unit = $product->mass_unit;
        $prod->serviceOfferedType = $product->serviceOfferedType;
        $prod->sale_price = $product->sale_price  <> null ? $product->sale_price : "";
        $prod->delivery_charges = $product->delivery_charges <> null ? $product->delivery_charges : "";
        $prod->brand_id = (int) $product->brand_id;
        $prod->brand_name = $this->productBrand((int) $product->brand_id);
        $prod->store_id = (int) $product->store_id;
        $prod->store_name = $this->productStore((int) $product->store_id);
        $prod->categories = $product->cats()->get();
        $prod->images = $extraImages;
        $existedproductAttributes = $product->attributes()->select('id')->get();
        @$prod->productSizes = [];        
        if(!empty($existedproductAttributes)){
            $existedAttributes = [];
            foreach ($existedproductAttributes as $pa) {
                foreach ($pa->attributesValues as $item) {
                    $existedAttributes[] = $item->value;
                }
            }
            @$prod->productSizes = $existedAttributes;
        }
        return $prod;
    }
    
    // Get Product Brand
    protected function productBrand($brand_id)
    {
         if($brand_id != 0){
	    $brandinfo  = Brand::where('id' , $brand_id)->value('name');
	    return $brandinfo  <> null ? $brandinfo: "";
	 }else{
	     return "";
	 }
    }
    // Get Product Store NAME
    protected function productStore($store_id)
    {
         if($store_id != 0){
	    $storeInfo =  Store::where('id' , $store_id)->value('storeTitle');
	    return $storeInfo  <> null ? $storeInfo: "";
	 }else{
	     return "";
	 }
    }
}
