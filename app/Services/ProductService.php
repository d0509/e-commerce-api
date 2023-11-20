<?php

namespace App\Services;

use App\Models\Media;
use App\Models\Mediable;
use App\Models\Product;

class ProductService
{
  protected $mediaService;

  public function __construct(MediaService $mediaService)
  {
    return $this->mediaService = $mediaService;
  }

  public function collection($request)
  {
    // dd($request['search']);
    if($request->has('search')){
      $product = Product::with(['media', 'category'])->where('name','like','%'. $request['search'] .'%',)->get();
      return response()->json(['product' => $product, 'success' => true], 200);
    } elseif($request->has('category')){
      // $product = Product::with(['media','category'])->where('category')
    }
    $product = Product::with(['media', 'category'])->get();
    if (count($product) == 0) {
      return response()->json(['message' => 'Products not found', 'success' => false], 404);
    } else {
      return response()->json(['product' => $product, 'success' => true], 200);
    }
  }

  public function store($inputs)
  {
    $product = new Product();

    $product->fill($inputs->all());

    $product->save();

    foreach ($inputs->category_id as $category) {
      $product->category()->attach($category);
    }
    if ($inputs->image) {
      foreach ($inputs->image as $image) {
        $imageIds = Media::where('ulid', $image)->pluck('id')->toArray();
        Mediable::create([
          'media_id' => $imageIds[0],
          'mediable_type' => Product::class,
          'mediable_id' => $product->id,
          'tag' => 'product',
        ]);
      }
    }
    return response()->json([
      'message' => 'Product Created Successfully',
      'success' => true, 'product' => $product->load('media', 'category')
    ], 200);
  }

  public function resource($ulid)
  {
    $product = Product::with(['media', 'category'])->where('ulid', $ulid)->first();
    if (!$product) {
      return response()->json([
        'message' => 'Product not found.',
        'success' => false
      ], 404);
    } else {
      return response()->json([
        'product' => $product,
        'success' => true
      ], 200);
    }
  }

  public function destroy($ulid)
  {
    $product = Product::where('ulid', $ulid)->first();
    if ($product == null) {
      return response()->json(['message' => "Product not found"], 404);
    } else {

      if ($product->category) {
        $product->category()->detach();
      }

      $medias = $product->media;
      if ($medias) {
        foreach ($medias as $media) {
          $filePath = public_path('storage/product/' . $media->filename . '.' . $media->extension);
          if (file_exists($filePath)) {
            unlink($filePath);
          }
          $media->delete();
        }
      }
      $product->delete();
      return response()->json(['message' => 'Product deleted successfully', 'success' => true], 200);
    }
  }

  public function update($request, $id)
  {
    $product = Product::with(['media', 'category'])->where('ulid', $id)->first();
    // dd($product);
    if (!$product) {
      return response()->json(['message' => 'Product not found', 'success' => false], 404);
    } else {
      $product->update([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'quantity' => $request->quantity,
        'is_active' => $request->is_active,
      ]);

      $product->category()->sync($request->input('category_id'));

      $existingImage = $product->media->pluck('ulid')->toArray();

      $newImage = $request->image;
      // dd($newImage);
      $addedImages = array_diff($newImage, $existingImage);
      foreach ($addedImages as $addImage) {
        $media = Media::where('ulid', $addImage)->first();
        Mediable::create([
          'media_id' => $media->id,
          'mediable_type' => Product::class,
          'mediable_id' => $product->id,
          'tag' => 'product',
        ]);
      }

      $deleteImages = array_diff($existingImage, $newImage);
      foreach ($deleteImages as $imageId) {
        $media = Media::where('ulid', $imageId)->first();
        if ($media) {
          $filePath = public_path('storage/product/' . $media->filename . '.' . $media->extension);
          if (file_exists($filePath)) {
            unlink($filePath);
          }

          $media->delete();
        }
      }

      return response()->json(['message' => 'product updated successfully.', 'product' => $product->load('media'), 'success' => true], 200);
    }
  }
}
