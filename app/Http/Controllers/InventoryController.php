<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);

        return Inertia::render('Inventory/Index', [
            'products' => $products
        ]);
    }

    public function barcode($barcode)
    {
        $products = Product::where('barcode', $barcode)->get();

        return response()->json([
            'status' => 'success',
            'products' => $products
        ]);
    }
}
