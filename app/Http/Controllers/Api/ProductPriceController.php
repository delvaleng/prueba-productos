<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductPriceRequest;
use App\Http\Resources\ProductPriceResource;
use App\Models\Product;
use App\Services\ProductPriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductPriceController extends Controller
{
    public function __construct(private ProductPriceService $service)
    {
    }

    public function index(Product $product): AnonymousResourceCollection
    {
        return ProductPriceResource::collection($product->prices()->with('currency')->get());
    }

    public function store(StoreProductPriceRequest $request, Product $product): JsonResponse
    {
        $price = $this->service->store($product, $request->validated());

        return (new ProductPriceResource($price))->response()->setStatusCode(201);
    }
}
