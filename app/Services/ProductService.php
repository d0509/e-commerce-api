<?php

namespace App\Services;

use App\Models\Media;
use App\Models\Mediable;
use App\Models\Product;
use App\Models\ProductImage;

class ProductService
{

  public function collection()
  {
    return Product::with('media')->get();
  }

  public function store($inputs)
  {
    $product = new Product();

    $product->fill($inputs->all());

    $product->save();

    foreach ($inputs->category_id as $category) {
      $product->category()->attach($category);
    }
    $index = 1;
    if ($inputs->image) {
      $timestamp = now()->timestamp;
      foreach ($inputs->image as $image) {
        // dd($image);
        $imageName = $image->getClientOriginalName();
        $name = pathinfo($imageName, PATHINFO_FILENAME);
        $uniqueName = $timestamp . '_' . $name . '_' . $index;
        $uniqueFileName = $uniqueName . '.' . $image->getClientOriginalExtension();
        $media = Media::create([
          'disk' => 'public',
          'directory' => 'products',
          'filename' =>  $uniqueName,
          'extension' => $image->getClientOriginalExtension(),
          'mime_type' => $image->getClientMimeType(),
          'size' => $image->getSize(),
        ]);

        Mediable::create([
          'media_id' => $media->id,
          'mediable_type' => Product::class,
          'mediable_id' => $product->id,
          'tag' => 'product',
          'order' => $index,
        ]);

        $image->move(public_path('/storage/product'), $uniqueFileName);

        $index++;
      }
    }
    return response()->json(['message' => 'Product Created Successfully', 'success' => true]);
  }

  public function show($id)
  {
    $product = Product::with(['media:disk,directory,filename,extension,size,mime_type', 'category:name'])->find($id);
    if ($product) {
      return response()->json(['product' => $product, 'success' => true]);
    } else {
      return response()->json(['message' => 'Sorry! No such products found.', 'success' => false]);
    }
  }

  public function destroy($id)
  {
    $product = Product::find($id);
    // dd($product);
    if ($product == null) {
      return response()->json(['message' => "Product not found"], 404);
    } else {

      if ($product->category) {
        $product->category()->detach();
      }

      $medias = $product->media;
      if ($medias) {
        foreach ($medias as $media) {
          $filePath = public_path('storage/product/' . $media->filename.'.'.$media->extension);
          if (file_exists($filePath)) {
            unlink($filePath); // Delete the image file
          }
          $media->delete();
        }
      }
      $product->delete();
      return response()->json(['message' => 'Product deleted successfully', 'success' => true], 200);
    }
  }
}
