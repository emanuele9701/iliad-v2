<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Providers\ProductServiceProvider;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductApiController extends Controller
{

    protected $productProvider;

    public function __construct(ProductServiceProvider $productProvider)
    {
        $this->productProvider = $productProvider;
    }
    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        $products = null;
        if ($request->has('q')) {
            $products = Product::where('name', 'like', '%' . $request->input('q') . "%")->get();
        } else {
            $products = Product::all();
        }
        return response()->json(['total_count' => $products->count(), 'items' => $products]);
    }

    public function index(Request $request)
    {
        $products = Product::orderByDesc('id')->select('*');

        if($request->has('search')) {
            $products->where('name','like',"%".$request->input('search')['value']."%");
        }

        $table = DataTables::of($products);
        return $table->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = $this->productProvider->createProduct($request->all());
        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product->load('orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product = $this->productProvider->updateProduct($product,$request->all());
        return (new ProductResource($product))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return ['esito' => true];
    }
}
