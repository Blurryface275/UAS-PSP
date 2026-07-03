<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    // 1. Daftar Pesanan Customer
    public function index()
    {
        $orders = Sale::where('user_id', Auth::id())
                  ->latest()
                  ->get();

        return view('customer.orders.index', compact('orders'));
    }

    // 2. Checkout (Menggunakan stock dari tabel products)
    public function checkout(Request $request)
    {
        return DB::transaction(function () use ($request) {
            // Mengunci stok agar tidak terjadi race condition
            $product = Product::where('id', $request->product_id)
                              ->lockForUpdate()
                              ->firstOrFail();

            if ($product->stock < $request->qty) {
                return back()->withErrors(['message' => 'Stok tidak mencukupi!']);
            }

            // Kurangi stok di tabel products
            $product->decrement('stock', $request->qty);

            // Simpan ke tabel sales
            $sale = Sale::create([
                'invoice_number' => 'INV-' . now()->format('YmdHis'),
                'user_id' => Auth::id(),
                'total_amount' => $product->price * $request->qty,
                'status' => 'pending'
            ]);

            // Simpan ke tabel sale_details
            SaleDetail::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'qty' => $request->qty,
                'price' => $product->price
            ]);

            return redirect()->route('customer.orders.index')->with('success', 'Pesanan berhasil dibuat!');
        });
    }

    // 3. Tracking (Cegah IDOR)
    public function show($id)
    {
        $order = Sale::where('id', $id)
                     ->where('user_id', Auth::id())
                     ->firstOrFail();

        return view('customer.orders.tracking', compact('order'));
    }

    public function showCheckout(Request $request) 
    {
        $product = Product::findOrFail($request->query('id')); 
        return view('customer.orders.checkout', compact('product'));
    }

    public function confirmReceived($id)
    {
        $order = Sale::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        if ($order->status !== 'shipped') {
            return back()->with('error', 'Pesanan belum dapat diselesaikan.');
        }

        $order->update([
            'status' => 'delivered'
        ]);

        return redirect()
                ->route('customer.orders.tracking', $order->id)
                ->with('success', 'Pesanan telah diterima.');
    }
}