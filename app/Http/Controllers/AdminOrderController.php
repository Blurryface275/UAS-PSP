<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Tampilkan daftar semua pesanan.
     */
    public function index()
    {
        // Ambil data sales beserta relasi user
        $orders = Sale::with('user')
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Tampilkan detail pesanan.
     */
    public function show($id)
    {
        // Ambil sale beserta detail dan produknya
        $order = Sale::with(['user', 'details.product'])->findOrFail($id);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Proses pesanan dari pending -> processing.
     */
    public function process($id)
    {
        $order = Sale::findOrFail($id);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Status pesanan tidak valid untuk diproses.');
        }

        $order->update([
            'status' => 'processing'
        ]);

        return back()->with('success', 'Pesanan berhasil dikonfirmasi dan sedang diproses.');
    }

    /**
     * Kirim pesanan (processing -> shipped) beserta input resi.
     */
    public function ship(Request $request, $id)
    {
        $request->validate([
            'tracking_receipt_number' => 'required|string|max:255'
        ], [
            'tracking_receipt_number.required' => 'Nomor resi wajib diisi untuk melakukan pengiriman.'
        ]);

        $order = Sale::findOrFail($id);

        if ($order->status !== 'processing') {
            return back()->with('error', 'Status pesanan tidak valid untuk dikirim.');
        }

        $order->update([
            'status' => 'shipped',
            'tracking_receipt_number' => $request->tracking_receipt_number
        ]);

        return back()->with('success', 'Resi berhasil disimpan. Pesanan dalam status pengiriman.');
    }
}
