<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // 1. Daftar Pesanan Customer
    public function index()
    {
        $orders = Sale::where('user_id', Auth::id())->latest()->get();
        return view('orders.index', compact('orders'));
    }

    // 2. Checkout (Menggunakan stock dari tabel products)
    public function checkout(Request $request)
    {
        dd($request->all());
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

            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');
        });
    }

    // 3. Tracking (Cegah IDOR)
    public function show($id)
    {
        $order = Sale::where('id', $id)
                     ->where('user_id', Auth::id())
                     ->firstOrFail();

        return view('orders.tracking', compact('order'));
    }

    // 4. Update Status (Pegawai)
    public function updateStatus(Request $request, $id)
    {
        $order = Sale::findOrFail($id);
        
        if ($request->status == 'shipped') {
            $request->validate(['tracking_receipt_number' => 'required|string|max:45']);
            $order->tracking_receipt_number = $request->tracking_receipt_number;
        }

        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Status pesanan diupdate ke ' . $request->status);
    }

    public function showCheckout(Request $request) 
    {
        $product = Product::findOrFail($request->query('id')); 
        return view('orders.checkout', compact('product'));
    }
}