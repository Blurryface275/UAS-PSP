<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Load semua product, pakai 'categories' karena model Product pakai belongsToMany, paginate 10 per page
        $products = Product::with('categories')->paginate(10);
        // Path wajib lengkap dari folder views/admin/product
        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // tarik semua daftar kategori buat dimasukin ke dropdown select
        $categories = Category::all(); 
        return view('admin.product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input user dulu
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

         // Simpan File Fisiknya ke Folder 'public/products'
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Simpan data product
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image_url' => $imagePath,
        ]);

       
        // Sinkronisasi kategori
        $product->categories()->sync($request->category_id);

        // Redirect ke halaman product
        return redirect()->route('product.index')->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        // tarik semua daftar kategori buat dimasukin ke dropdown select
        $categories = Category::all();
        return view('admin.product.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input user
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // maksimal 2MB dan diubatasi tipe filenya supaya tidak ada input malware
            'category_id' => 'required|exists:categories,id',
        ]);

        // Update data product
        $product = Product::findOrFail($id);
        $product->update($request->all());

        // Handle upload gambar
        if($request->hasFile('image')){
              // Pembersihan: Lennyapkan gambar fisik usang dari Harddisk (agar server tak penuh!)
            if ($product->image_url && \Storage::disk('public')->exists($product->image_url)) {
                \Storage::disk('public')->delete($product->image_url);
            }
            // Simpan gambar baru secara acak (terenkripsi namanya)
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image_url = $imagePath;
        }

        // Update perubahan ke database
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->save();

        // SInkronisasi kategori
        $product->categories()->sync($request->category_id);
        // Redirect ke halaman product
        return redirect()->route('product.index')->with('success', 'Produk berhasil diupdate');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
      
        // Hapus product
        $product->delete();
        // Redirect ke halaman product
        return redirect()->route('product.index')->with('success', 'Produk berhasil dihapus');
    }
}
