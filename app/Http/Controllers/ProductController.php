<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        $products = Product::with(['category', 'variants'])->latest()->get();
        $categories = \App\Models\Category::all(); // Fetch all categories for filter
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('products.form', [
            'product' => new Product(),
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'unique' => ':attribute sudah terdaftar.',
            'exists' => ':attribute tidak valid.',
            'numeric' => ':attribute harus berupa angka.',
            'integer' => ':attribute harus berupa bilangan bulat.',
            'min' => ':attribute minimal :min.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat: :values.',
            'variants.required' => 'Minimal satu varian ukuran wajib diisi.',
            'variants.*.size.required' => 'Ukuran wajib diisi.',
            'variants.*.stock.required' => 'Stok wajib diisi.',
            'variants.*.stock.min' => 'Stok minimal harus :min untuk produk baru.',
        ];

        $attributes = [
            'product_code' => 'Kode Produk',
            'category_id' => 'Kategori',
            'name' => 'Nama Produk',
            'price' => 'Harga',
            'image' => 'Gambar Produk',
        ];

        $validated = $request->validate([
            'product_code' => 'required|string|unique:products|max:20',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|unique:products|max:255',
            'price' => 'required|numeric|min:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'variants' => 'required|array|min:1',
            'variants.*.size' => 'required|string|max:50|distinct',
            'variants.*.stock' => 'required|integer|min:2',
        ], $messages, $attributes);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);

        foreach ($request->variants as $variant) {
            $product->variants()->create([
                'size' => $variant['size'],
                'stock' => $variant['stock'],
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $product->load('variants');
        $categories = \App\Models\Category::all();
        return view('products.form', compact('product', 'categories'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $messages = [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'unique' => ':attribute sudah terdaftar.',
            'exists' => ':attribute tidak valid.',
            'numeric' => ':attribute harus berupa angka.',
            'integer' => ':attribute harus berupa bilangan bulat.',
            'min' => ':attribute minimal :min.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat: :values.',
        ];

        $attributes = [
            'product_code' => 'Kode Produk',
            'category_id' => 'Kategori',
            'name' => 'Nama Produk',
            'price' => 'Harga',
            'image' => 'Gambar Produk',
        ];

        $validated = $request->validate([
            'product_code' => 'required|string|max:20|unique:products,product_code,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'price' => 'required|numeric|min:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'variants' => 'required|array|min:1',
            'variants.*.size' => 'required|string|max:50|distinct',
            'variants.*.stock' => 'required|integer|min:2',
        ], $messages, $attributes);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        // Sync Variants: Delete all and recreate
        $product->variants()->delete();
        foreach ($request->variants as $variant) {
            $product->variants()->create([
                'size' => $variant['size'],
                'stock' => $variant['stock'],
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
