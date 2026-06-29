<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
       public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Elektronik', 
                'Pakaian Pria', 
                'Pakaian Wanita', 
                'Sembako & Makanan', 
                'Minuman Kemasan', 
                'Perabotan Rumah', 
                'Alat Tulis Kantor', 
                'Buku Bacaan', 
                'Kosmetik & Skincare', 
                'Aksesoris Otomotif',
                'Perlengkapan Bayi',
                'Perawatan Hewan',
                'Sepatu Sneakers Pria',
                'Sepatu Heels Wanita',
                'Tas & Ransel',
                'Jam Tangan Analog',
                'Perhiasan Perak',
                'Olahraga & Outdoor',
                'Voucher Belanja',
                'Peralatan Dapur',
                'Komputer & Laptop',
                'Handphone & Aksesoris',
                'Kamera DSLR',
                'Kesehatan & Medis',
                'Obat & Suplemen',
                'Mainan Anak',
                'Gaming & Konsol',
                'Material Bahan Bangunan',
                'Perlengkapan Mandi',
                'Dekorasi Interior'
            ]),
            'description' => fake()->sentence(),
        ];
    }
}
