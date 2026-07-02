<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'user'])
            ->orderBy('id', 'asc')
            ->get();

        return view('purchase_orders.index', compact('purchaseOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();

        return view('purchase_orders.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseOrderRequest $request)
    {
        DB::transaction(function () use ($request) {

            $purchaseOrder = PurchaseOrder::create([
                'supplier_id' => $request->supplier_id,
                'user_id' => Auth::id(),
                'status' => 'pending',
                'estimated_total' => 0,
            ]);

            $total = 0;

            foreach ($request->items as $item) {

                // Lewati baris yang tidak diisi
                if (empty($item['product_id'])) {
                    continue;
                }

                PurchaseOrderDetail::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'expected_price_per_unit' => $item['expected_price_per_unit'],
                ]);

                $total += $item['qty'] * $item['expected_price_per_unit'];
            }

            $purchaseOrder->update([
                'estimated_total' => $total,
            ]);
        });

        return redirect()
            ->route('purchase-orders.index')
            ->with('success', 'Purchase Order berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load([
            'supplier',
            'user',
            'details.product'
        ]);

        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        $suppliers = Supplier::all();
        $products = Product::all();

        return view('purchase_orders.edit', compact('purchaseOrder', 'suppliers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        DB::transaction(function () use ($request, $purchaseOrder) {

            // Update header PO
            $purchaseOrder->update([
                'supplier_id' => $request->supplier_id,
            ]);

            // Hapus detail lama
            $purchaseOrder->details()->delete();

            $total = 0;

            // Simpan detail baru
            foreach ($request->items as $item) {

                // Lewati baris yang kosong
                if (empty($item['product_id'])) {
                    continue;
                }

                PurchaseOrderDetail::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'expected_price_per_unit' => $item['expected_price_per_unit'],
                ]);

                $total += $item['qty'] * $item['expected_price_per_unit'];
            }

            // Update total
            $purchaseOrder->update([
                'estimated_total' => $total,
            ]);
        });

        return redirect()
            ->route('purchase-orders.index')
            ->with('success', 'Purchase Order berhasil diperbarui.');
    }

    public function approve(PurchaseOrder $purchaseOrder)
    {
        if (Auth::user()?->role !== 'administrator') {
            return redirect()->back()->with('error', 'Hanya administrator yang dapat menyetujui PO.');
        }

        if (!in_array($purchaseOrder->status, ['pending', 'approved'])) {
            return redirect()->back()->with('error', 'Status PO tidak bisa disetujui lagi.');
        }

        $purchaseOrder->update(['status' => 'approved']);

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order berhasil disetujui dan siap dikirim ke supplier (Tinggal Terima Barang).');
    }

    public function cancel(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status === 'cancelled') {
            return redirect()->back()->with('error', 'Purchase Order ini sudah dibatalkan.');
        }

        if (Auth::user()?->role !== 'administrator' && $purchaseOrder->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak berwenang membatalkan PO ini.');
        }

        $purchaseOrder->update(['status' => 'cancelled']);

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order berhasil dibatalkan dari sistem.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();

        return redirect()
            ->route('purchase-orders.index')
            ->with('success', 'Purchase Order berhasil dihapus.');
    }
}
