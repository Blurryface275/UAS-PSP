<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        Product::factory(6)->create()->each(function ($p) use ($allCategories) {
            // Pasangkan setiap produk dummy factory ini ke 1 atau 2 kategori secara acak
            $p->categories()->attach(
                $allCategories->random(rand(1, 2))->pluck('id')->toArray()
            );
        });
    }
}
