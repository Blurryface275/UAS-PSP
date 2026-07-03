<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('purchaseOrders')->latest()->get();
        return view('admin.supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.supplier.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        Supplier::create($request->only('name', 'phone', 'address'));

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier baru berhasil didaftarkan ke sistem!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->only('name', 'phone', 'address'));

        return redirect()->route('supplier.index')
            ->with('success', 'Data supplier berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);

        // Proteksi: Jangan hapus supplier yang masih punya riwayat PO aktif
        if ($supplier->purchaseOrders()->count() > 0) {
            return redirect()->route('supplier.index')
                ->with('error', 'Supplier ini tidak bisa dihapus karena masih memiliki riwayat Purchase Order!');
        }

        $supplier->delete();

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil dihapus dari sistem!');
    }
}
