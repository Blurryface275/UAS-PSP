<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $fillable = [
        'purchase_id',
        'product_id',
        'qty',
        'price',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        // PENTING: Tambahkan withTrashed() agar tabel riwayat pesanan (Modul 2/3) tidak jebol 
        // saat mencari id barang yang sudah masuk tong sampah (Soft Deleted).
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
