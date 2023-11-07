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
    $image = $inputs->file('image');

    $product = new Product();

    $product->fill($inputs->all());
    $product->save();
    $lastProduct = $product->id;

    if ($inputs->hasFile('image')) {

      $ImageNames = rand() . '.' . $image->getClientOriginalExtension();
      $names = explode(',', $ImageNames);
      $image->move(public_path('/uploads/products'), $ImageNames);
      foreach ($names as $ImageName) {
        $productImage = ProductImage::create([
          'product_id' => $lastProduct,
          'image' => $ImageName
        ]);
      }
    } else {
      return response()->json(['error' => 'No images found in request'], 500);
    }
  }
}
