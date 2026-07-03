<?php

namespace App\Http\Controllers;

use App\Models\Product;

class CustomerProductController extends Controller
{
    public function index()
    {
        $products = Product::with('categories')
                    ->where('stock', '>', 0)
                    ->latest()
                    ->paginate(8);

        return view('customer.products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::with('categories')->findOrFail($id);

        return view('customer.products.show', compact('product'));
    }
}