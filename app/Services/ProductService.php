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

  public function collection()
  {
    $product = Product::with('media')->get();
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
      $images = $inputs->image;
      foreach ($images as $image) {
        Mediable::create([
          'media_id' => $image,
          'mediable_type' => Product::class,
          'mediable_id' => $product->id,
          'tag' => 'product',
        ]);
      }
    }
    return response()->json(['message' => 'Product Created Successfully', 'success' => true, 'product' => $product], 200);
  }

  public function show($ulid)
  {
    $product = Product::with(['media:disk,directory,filename,extension,size,mime_type', 'category:name,id'])->where('ulid', $ulid)->get();
    // dd($product);

    if ($product) {
      if (isset($product->media)) {
        $mediaUrls = $product->media->map(function ($media) {
          return $media->getUrl();
        });
        return response()->json(['product' => $product, 'success' => true]);
      } else {
        $product = Product::where('ulid', $ulid);
        if (count($product) == 0) {
          return response()->json(['message' => 'Product not found'], 404);
        } else {
          return response()->json(['product' => $product, 'success' => true], 200);
        }
        // dd($product);
      }
    } else {
      return response()->json(['message' => 'Sorry! No such products found.', 'success' => false]);
    }
  }

  public function destroy($ulid)
  {
    $product = Product::where('ulid',$ulid)->first();
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
    $product = Product::with(['media', 'category'])->where('ulid',$id)->first();
    
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

      $existingImage = Mediable::where('mediable_id', $id)->pluck('media_id')->toArray();
      $newImage = $request->image;

      $addedImages = array_diff($newImage, $existingImage);
      foreach ($addedImages as $addImage) {
        Mediable::create([
          'media_id' => $addImage,
          'mediable_type' => Product::class,
          'mediable_id' => $product->id,
          'tag' => 'product',
        ]);
      }

      $deleteImages = array_diff($existingImage, $newImage);
      foreach ($deleteImages as $imageId) {
        $media = Media::find($imageId);

        if ($media) {
          $filePath = public_path('storage/product/' . $media->filename . '.' . $media->extension);
          if (file_exists($filePath)) {
            unlink($filePath);
          }

          $media->delete();
        }
      }
      return response()->json(['message' => 'product basic details and category updated successfully', 'product' => $product, 'success' => true], 200);
    }
  }
}
