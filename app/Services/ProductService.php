<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;

class ProductService
{

  public function collection(){
    return Product::all();
  }

  public function store($inputs)
  {
    $product = new Product();

    $product->fill($inputs->all());
    $product->save();

    return response()->json(['message' => 'Product Created Successfully','success' => true]);



  }
}
