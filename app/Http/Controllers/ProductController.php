<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::paginate(10);

        return response()->json([
            'status' => 'success',
            'products' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'barcode' => 'required|string|max:255|unique:products,barcode',
            'price' => 'required|numeric|gt:0',
            'stock' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        $product = Product::create($request->all());

        return response()->json([
            'status' => 'success',
            'product' => $product
        ]);
    }


    public function web_show(Product $product)
    {
        $product->sales = $product->lastSales;
        $product->sales->load('user');
        return Inertia::render('Inventory/Show', [
            'product' => $product
        ]);
    }

    public function web_edit(Product $product)
    {
        return Inertia::render('Inventory/Edit', [
            'product' => $product,
            'csrf' => csrf_token()
        ]);
    }

    public function web_create()
    {
        return Inertia::render('Inventory/Create', [
            'csrf' => csrf_token()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json([
            'status' => 'success',
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'barcode' => 'required|string|unique:products,barcode,' . $product->id,
            'price' => 'required|numeric|gt:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        $product->update($request->all());

        return response()->json([
            'status' => 'success',
            'product' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully'
        ]);
    }
}
