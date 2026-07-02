<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $productNames = [
            'Laptop Gaming Layar Lebar', 'Sepatu Lari Olahraga Pria', 'Kemeja Formal Putih', 'Lemari Es 2 Pintu', 
            'Handphone Flagship Terbaru', 'Televisi LED Cerdas', 'Jam Tangan Analog Pria', 'Meja Komputer Kayu Jati', 
            'Sofa Kulit Premium', 'Kamera DSLR Profesional', 'Drone Quadcopter 4K', 'Earphone Noise Cancelling', 
            'Mouse Gaming RGB', 'Keyboard Tenkeyless', 'Kursi Direktur Hidrolik', 'Lampu Meja Belajar', 
            'Rak Sepatu Susun', 'Printer Tinta All-in-One', 'Kacamata Hitam Anti UV', 'Termos Air Panas Stainless',
            'Tas Ransel Punggung', 'Blender Pelumat Buah', 'Kipas Angin Berdiri', 'Setrika Listrik Otomatis', 'Magic Com Rice Cooker'
        ];

        return [
            // unique() Mencegah faker melahirkan nama barang yang double/kembar
            'name' => fake()->unique()->randomElement($productNames),
            'description' => fake()->sentence(8),
            'price' => fake()->numberBetween(25, 2000) * 1000,
            'stock' => fake()->numberBetween(10, 200),
        ];
    }
}
