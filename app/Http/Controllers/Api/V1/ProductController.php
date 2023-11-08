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

    public function index()
    {
        return $this->productService->collection();
    }

    public function store(Upsert $request)
    {
        return $this->productService->store($request);
    }

    public function show(string $id)
    {
        return $this->productService->show($id);
    }

    public function update(Request $request, string $id)
    {
        //
    }

   public function destroy(string $id)
    {
        return $this->productService->destroy($id);
    }
}
