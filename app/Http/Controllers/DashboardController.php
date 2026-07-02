<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB; // Tambahkan import DB facade

class DashboardController extends Controller
{
    public function index()
    {
        // Menyedot total jumlah baris data dari database MySQL
        $categoriesCount = Category::count();
        $productsCount = Product::count();
        $usersCount = User::count();
        
        // Sengaja kita nge-cheat pakai Query Builder karena model Sales buatan Kenny belum beres
        $salesCount = DB::table('sales')->count(); 

        // TUGAS BARU: Ambari 5 data barang & kategori paling anyar yang baru masuk ke sistem!
        $recentCategories = Category::latest()->take(5)->get();
        $recentProducts = Product::latest()->take(5)->get();

        // Alirkan datanya kembali ke layar HTML
        return view('admin.dashboard', compact(
            'categoriesCount', 
            'productsCount', 
            'usersCount', 
            'salesCount',
            'recentCategories',
            'recentProducts'
        ));
    }
}
