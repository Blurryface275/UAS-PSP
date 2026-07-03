<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['purchaseOrder.supplier', 'purchaseOrder', 'user', 'details.product'])
            ->latest()
            ->get();

        return view('purchases.index', compact('purchases'));
    }

    public function create(PurchaseOrder $purchaseOrder = null)
    {
        $purchaseOrder = $purchaseOrder ?? null;
        $purchaseOrders = PurchaseOrder::where('status', 'approved')
            ->with('supplier')
            ->orderByDesc('id')
            ->get();

        if ($purchaseOrder) {
            $purchaseOrder->load('details.product');
        }

        return view('purchases.create', compact('purchaseOrder', 'purchaseOrders'));
    }

    public function complete(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'approved') {
            return Redirect::back()->with('error', 'Hanya PO dengan status approved yang bisa diselesaikan.');
        }

        $purchaseOrder->update(['status' => 'completed']);

        return Redirect::route('purchases.index')->with('success', 'Status PO berhasil diubah menjadi completed.');
    }

    public function store(Request $request, PurchaseOrder $purchaseOrder)
    {
        // dd([
        //     'purchaseOrder' => $purchaseOrder,
        //     'purchaseOrderId' => $purchaseOrder->id,
        //     'request' => $request->all(),
        // ]);

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty_received' => 'required|integer|min:1',
            'items.*.actual_price_per_unit' => 'required|numeric|min:0',
        ]);

        if ($purchaseOrder->purchase()->exists()) {
            return redirect()->route('purchase-orders.show', $purchaseOrder)
                ->with('error', 'PO ini sudah pernah diterima sebelumnya.');
        }

        DB::transaction(function () use ($request, $purchaseOrder) {
            $purchase = Purchase::create([
                'purchase_order_id' => $purchaseOrder->id,
                'user_id' => Auth::id(),
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            foreach ($request->items as $item) {
                if (empty($item['product_id'])) {
                    continue;
                }

                $product = Product::findOrFail($item['product_id']);

                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'qty' => $item['qty_received'],
                    'price' => $item['actual_price_per_unit'],
                ]);

                $product->stock = (int) $product->stock + (int) $item['qty_received'];
                $product->save();

                $totalAmount += (int) $item['qty_received'] * (float) $item['actual_price_per_unit'];
            }

            $purchase->update([
                'total_amount' => $totalAmount,
            ]);

            // Gunakan Query Builder agar update terjamin menembus database secara instan
            DB::table('purchase_orders')
                ->where('id', $purchaseOrder->id)
                ->update(['status' => 'completed']);
        });

        return redirect()->route('purchases.index')->with('success', 'Penerimaan barang berhasil disimpan dan stok bertambah.');
    }
}
