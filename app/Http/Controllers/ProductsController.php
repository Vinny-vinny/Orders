<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /** Get Product Listing
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProducts(): JsonResponse
    {
       return response()->json($this->productRepository->all());
    }
}
