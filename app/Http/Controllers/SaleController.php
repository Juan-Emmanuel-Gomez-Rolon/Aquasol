<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with('products')->latest()->paginate(10);
        $sales->load('user');
        return Inertia::render('Sales/Index', [
            'sales' => $sales,
            'csrf' => csrf_token(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array',
            'products.*.id' => 'required|integer',
            'products.*.purchase_quantity' => 'required|integer|gt:0',
            'client' => 'required|array',
            'client.name' => 'required|string',
            'client.email' => 'nullable|email',
            'client.phone' => 'nullable|string|max:20',
            'client.address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 400);
        }

        $validated = $validator->validated();

        DB::beginTransaction();

        try {
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'total' => 0, // TODO: calculate the total
                'client_name' => $validated['client']['name'],
                'client_email' => $validated['client']['email'] ?? null,
                'client_phone' => $validated['client']['phone'] ?? null,
                'client_address' => $validated['client']['address'] ?? null,
            ]);

            // get list of products and calculate the total
            $dbProducts = Product::whereIn('id', array_column($validated['products'], 'id'))->get();


            $total = 0;
            foreach ($validated['products'] as $product) {
                $db_product = $dbProducts->find($product['id']);
                $sale->products()->attach($product['id'], ['quantity' => $product['purchase_quantity'], 'price' => $db_product->price]);
                $db_product->update(['stock' => $db_product->stock - $product['purchase_quantity'], 'last_sale' => now()]);
                $total += $product['purchase_quantity'] * $db_product->price;
            }

            $sale->update(['total' => $total]);
            $sale->load('products');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Sale could not be created'], 500);
        }

        return response()->json(['status' => 'success', 'sale' => $sale], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load('products');
        $sale->load('user');

        return Inertia::render('Sales/Show', [
            'sale' => $sale,
            'csrf' => csrf_token(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        // can only update customer details
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string',
            'client_email' => 'nullable|email',
            'client_phone' => 'nullable|string|max:20',
            'client_address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 400);
        }

        $validated = $validator->validated();
        $sale->update($validated);

        return response()->json(['status' => 'success', 'sale' => $sale], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
