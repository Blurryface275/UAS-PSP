<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Sale;
use App\Models\SaleDetail;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Default Tim (Akses: Semua Role)
        User::updateOrCreate(
            ['email' => 'admin@uas.com'],
            ['name' => 'Admin Utama (Orang 1)', 'password' => Hash::make('password'), 'role' => 'administrator']
        );
        User::updateOrCreate(
            ['email' => 'pegawai@uas.com'],
            ['name' => 'Pegawai Gudang (Orang 2)', 'password' => Hash::make('password'), 'role' => 'pegawai']
        );
        User::updateOrCreate(
            ['email' => 'customer@uas.com'],
            ['name' => 'Customer Publik (Orang 3)', 'password' => Hash::make('password'), 'role' => 'customer']
        );

        // 2. Kategori Barang Utama
        $catPakaian = Category::firstOrCreate(['name' => 'Pakaian'], ['description' => 'Produk garmen tekstil']);
        $catElektronik = Category::firstOrCreate(['name' => 'Elektronik'], ['description' => 'Gadget & Komputer']);
        $catKonsumsi = Category::firstOrCreate(['name' => 'Konsumsi'], ['description' => 'Kebutuhan harian rumah tangga']);

        // 3. Etalase Produk
        $p1 = Product::firstOrCreate(
            ['name' => 'Sepatu Sneakers Pria'],
            ['description' => 'Sepatu casual santai untuk nongkrong', 'price' => 250000, 'stock' => 50]
        );
        $p2 = Product::firstOrCreate(
            ['name' => 'Kemeja Flannel Kotak'],
            ['description' => 'Baju lengan panjang bahan halus', 'price' => 150000, 'stock' => 100]
        );
        $p3 = Product::firstOrCreate(
            ['name' => 'Laptop ASUS ROG'],
            ['description' => 'Laptop Gaming High-End RTX 3060', 'price' => 15000000, 'stock' => 10]
        );
        $p4 = Product::firstOrCreate(
            ['name' => 'Kopi Bubuk Arabika'],
            ['description' => 'Kemasan 250gr Robusta mix', 'price' => 50000, 'stock' => 200]
        );

        // Menautkan relasi tabel Pivot (Barang <=> Kategori)
        $p1->categories()->syncWithoutDetaching([$catPakaian->id]);
        $p2->categories()->syncWithoutDetaching([$catPakaian->id]);
        $p3->categories()->syncWithoutDetaching([$catElektronik->id]);
        $p4->categories()->syncWithoutDetaching([$catKonsumsi->id]);

        // 4. Pabrikan / Supplier Tujuan untuk Modul 2
        Supplier::firstOrCreate(
            ['name' => 'PT. ASUS Distributor Resmi'],
            ['phone' => '08123456789', 'address' => 'Mangga Dua, Jakarta Pusat']
        );
        Supplier::firstOrCreate(
            ['name' => 'Konveksi Bandung Abadi'],
            ['phone' => '08987654321', 'address' => 'Cibaduyut, Bandung Raya']
        );

        // 5. Generate Sisanya menggunakan Factory agar genap 10 per tabel!
        Category::factory(7)->create();
        Supplier::factory(8)->create();

        $allCategories = Category::all();
        // Generate kelipatan 16 supaya dijumlah 4 hardcode product di atas pass 20 Product.
        Product::factory(16)->create()->each(function ($p) use ($allCategories) {
            // Pasangkan setiap produk dummy factory ini ke 1 atau 2 kategori secara acak
            $p->categories()->attach(
                $allCategories->random(rand(1, 2))->pluck('id')->toArray()
            );
        });

        //6. purchase order
        $pegawai = User::where('role', 'pegawai')->first();
        $suppliers = Supplier::all();

        $po1 = PurchaseOrder::create([
            'status' => 'pending',
            'estimated_total' => 3250000,
            'supplier_id' => $suppliers[0]->id,
            'user_id' => $pegawai->id,
        ]);

        $po2 = PurchaseOrder::create([
            'status' => 'pending',
            'estimated_total' => 15000000,
            'supplier_id' => $suppliers[1]->id,
            'user_id' => $pegawai->id,
        ]);

        $po3 = PurchaseOrder::create([
            'status' => 'pending',
            'estimated_total' => 1250000,
            'supplier_id' => $suppliers[2]->id,
            'user_id' => $pegawai->id,
        ]);

        $po4 = PurchaseOrder::create([
            'status' => 'pending',
            'estimated_total' => 4750000,
            'supplier_id' => $suppliers[3]->id,
            'user_id' => $pegawai->id,
        ]);

        $po5 = PurchaseOrder::create([
            'status' => 'pending',
            'estimated_total' => 2000000,
            'supplier_id' => $suppliers[4]->id,
            'user_id' => $pegawai->id,
        ]);

        //7. purchase order detail
        // PO 1
        PurchaseOrderDetail::create([
            'purchase_order_id' => $po1->id,
            'product_id' => $p1->id,
            'expected_price_per_unit' => $p1->price,
            'qty' => 5,
        ]);

        PurchaseOrderDetail::create([
            'purchase_order_id' => $po1->id,
            'product_id' => $p2->id,
            'expected_price_per_unit' => $p2->price,
            'qty' => 10,
        ]);

        // PO 2
        PurchaseOrderDetail::create([
            'purchase_order_id' => $po2->id,
            'product_id' => $p3->id,
            'expected_price_per_unit' => $p3->price,
            'qty' => 1,
        ]);

        // PO 3
        PurchaseOrderDetail::create([
            'purchase_order_id' => $po3->id,
            'product_id' => $p4->id,
            'expected_price_per_unit' => $p4->price,
            'qty' => 25,
        ]);

        PurchaseOrderDetail::create([
            'purchase_order_id' => $po3->id,
            'product_id' => $p2->id,
            'expected_price_per_unit' => $p2->price,
            'qty' => 4,
        ]);

        // PO 4
        PurchaseOrderDetail::create([
            'purchase_order_id' => $po4->id,
            'product_id' => $p1->id,
            'expected_price_per_unit' => $p1->price,
            'qty' => 8,
        ]);

        PurchaseOrderDetail::create([
            'purchase_order_id' => $po4->id,
            'product_id' => $p3->id,
            'expected_price_per_unit' => $p3->price,
            'qty' => 2,
        ]);

        // PO 5
        PurchaseOrderDetail::create([
            'purchase_order_id' => $po5->id,
            'product_id' => $p2->id,
            'expected_price_per_unit' => $p2->price,
            'qty' => 6,
        ]);

        PurchaseOrderDetail::create([
            'purchase_order_id' => $po5->id,
            'product_id' => $p4->id,
            'expected_price_per_unit' => $p4->price,
            'qty' => 15,
        ]);

        //8. purchases
        $purchase1 = Purchase::create([
            'purchase_order_id' => $po1->id,
            'user_id' => $pegawai->id,
            'total_amount' => 2750000,
        ]);

        $purchase2 = Purchase::create([
            'purchase_order_id' => $po2->id,
            'user_id' => $pegawai->id,
            'total_amount' => 15000000,
        ]);

        $purchase3 = Purchase::create([
            'purchase_order_id' => $po3->id,
            'user_id' => $pegawai->id,
            'total_amount' => 1400000,
        ]);

        //9. purchase details
        // Purchase 1
        PurchaseDetail::create([
            'purchase_id' => $purchase1->id,
            'product_id' => $p1->id,
            'qty' => 5,
            'price' => $p1->price,
        ]);

        PurchaseDetail::create([
            'purchase_id' => $purchase1->id,
            'product_id' => $p2->id,
            'qty' => 10,
            'price' => $p2->price,
        ]);

        // Purchase 2
        PurchaseDetail::create([
            'purchase_id' => $purchase2->id,
            'product_id' => $p3->id,
            'qty' => 1,
            'price' => $p3->price,
        ]);

        // Purchase 3
        PurchaseDetail::create([
            'purchase_id' => $purchase3->id,
            'product_id' => $p4->id,
            'qty' => 25,
            'price' => $p4->price,
        ]);

        // 10. Sales (Penjualan untuk Customer)
        $customer = User::where('role', 'customer')->first();
        
        $sale1 = Sale::create([
            'user_id' => $customer->id,
            'status' => 'pending',
            'total_amount' => 500000, // (2 x 250rb)
        ]);

        SaleDetail::create([
            'sale_id' => $sale1->id,
            'product_id' => $p1->id, // Sepatu Sneakers
            'qty' => 2,
            'price' => 250000,
        ]);
    }
}
