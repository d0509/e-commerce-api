<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Upsert;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        return $this->productService->collection($request);
    }

    public function store(Upsert $request)
    {
        return $this->productService->store($request);
    }

    public function show(string $ulid)
    {
        return $this->productService->resource($ulid);
    }

    public function update(Upsert $request, string $ulid)
    {
       return $this->productService->update($request,$ulid);
    }

   public function destroy(string $ulid)
    {
        return $this->productService->destroy($ulid);
    }
}
