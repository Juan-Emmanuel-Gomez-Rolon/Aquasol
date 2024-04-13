<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function web_index()
    {
        $entries = Entry::latest()->paginate(10);
        $entries->load('user');
        $entries->load('products');
        return Inertia::render('Entries/Index', [
            'entries' => $entries
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Entries/Create', [
            'csrf' => csrf_token()
        ]);
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
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 400);
        }

        $validated = $validator->validated();
        DB::beginTransaction();

        try {
            // start a new entry
            $entry = Entry::create([
                'user_id' => auth()->id(),
            ]);

            // attach products to the entry
            foreach ($validated['products'] as $product) {
                $db_product = Product::find($product['id']);

                $entry->products()->attach($db_product->id, [
                    'quantity' => $product['purchase_quantity']
                ]);

                // update the product stock and last entry
                $db_product->stock += $product['purchase_quantity'];
                $db_product->last_entry = now();
                $db_product->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'An error occurred while creating the entry'], 500);
        }


        return response()->json(['status' => 'success', 'message' => 'Entry created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Entry $entry)
    {
        $entry->load('user');
        $entry->load('products');
        return Inertia::render('Entries/Show', [
            'entry' => $entry
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entry $entry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entry $entry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entry $entry)
    {
        //
    }
}
