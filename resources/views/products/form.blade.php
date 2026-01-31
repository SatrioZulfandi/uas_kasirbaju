@extends('layouts.app')

@section('title', isset($product->id) ? 'Edit Produk' : 'Tambah Produk')

@section('content')
<h1>{{ isset($product->id) ? 'Edit Produk' : 'Tambah Produk' }}</h1>

<style>
    .error-text {
        color: #dc2626; /* Red color */
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }
</style>

<div class="card">
    <form action="{{ isset($product->id) ? route('products.update', $product) : route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm" novalidate>
        @csrf
        @if(isset($product->id))
            @method('PUT')
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label for="product_code">Kode Produk</label>
                <input type="text" name="product_code" id="product_code" class="form-control" 
                    value="{{ old('product_code', $product->product_code) }}" required
                    {{ isset($product->id) ? 'readonly' : '' }}
                    placeholder="Contoh: P001">
                @error('product_code') <p class="error-text">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="category_id">Kategori</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="error-text">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="name">Nama Produk</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            @error('name') <p class="error-text">{{ $message }}</p> @enderror
        </div>

        {{-- Variants Section --}}
        <div class="form-group">
            <label>Varian Produk (Ukuran & Stok)</label>
            <table class="table" id="variantsTable" style="margin-top: 5px; border: 1px solid #e2e8f0;">
                <thead>
                    <tr>
                        <th style="width: 45%;">Ukuran</th>
                        <th style="width: 45%;">Stok</th>
                        <th style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Rows will be populated by JS --}}
                </tbody>
            </table>
            <button type="button" class="btn btn-sm btn-primary" onclick="addVariantRow()">+ Tambah Varian</button>
            @error('variants') <p class="error-text">Minimal satu varian harus diisi.</p> @enderror
        </div>

        {{-- Removed standalone 'sizes' and 'stock' inputs --}}
        
        <div class="form-group" style="margin-top: 1rem;">
            <label for="price">Harga (Rp)</label>
            <input type="number" id="price" name="price" class="form-control" 
                value="{{ old('price', $product->price) }}" min="1000" step="0.01" required>
            @error('price')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="image">Gambar Produk</label>
            
            @if(isset($product) && $product->image)
                <div style="margin-bottom: 10px;">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Preview" style="max-height: 100px; border-radius: 5px; border: 1px solid #ddd;">
                </div>
            @endif

            <input type="file" name="image" id="image" class="form-control" accept="image/*" onchange="previewImage(event)">
            @error('image') <p class="error-text">{{ $message }}</p> @enderror
            
            <img id="imagePreview" src="#" alt="Preview Gambar" style="display: none; max-height: 100px; margin-top: 10px; border-radius: 5px; border: 1px solid #ddd;">
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                {{ isset($product->id) ? 'Update' : 'Simpan' }}
            </button>
            <a href="{{ route('products.index') }}" class="btn" style="background: #e2e8f0;">Batal</a>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- Global Functions (Accessible via HTML attributes) ---
    
    // Determine min stock based on Create vs Edit mode
    // (If creating new product, enforce min 2. If editing, allow 0)
    const minStock = 2; // Always enforce min 2 as requested

    // Image Preview
    function previewImage(event) {
        const output = document.getElementById('imagePreview');
        if(event.target.files && event.target.files[0]) {
            output.src = URL.createObjectURL(event.target.files[0]);
            output.style.display = 'block';
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        }
    }

    // Dynamic Variants Logic
    let variantIndex = 0;

    function addVariantRow(size = '', stock = '') {
        const tbody = document.querySelector('#variantsTable tbody');
        if (!tbody) return; // Guard clause

        // Default stock if empty string passed
        if (stock === '') stock = '';

        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>
                <input type="text" name="variants[${variantIndex}][size]" class="form-control" placeholder="Size" value="${size}" required>
            </td>
            <td>
                <input type="number" name="variants[${variantIndex}][stock]" class="form-control" placeholder="Stok" value="${stock}" min="${minStock}" required>
            </td>
            <td style="text-align: center;">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeVariantRow(this)" style="padding: 0.4rem 0.6rem;">✕</button>
            </td>
        `;
        
        tbody.appendChild(row);
        variantIndex++;
    }

    function removeVariantRow(btn) {
        const row = btn.closest('tr');
        row.remove();
    }

    // --- DOM Dependent Logic ---
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('productForm');
        
        // 1. Form Validation
        if (form) {
            form.addEventListener('submit', function(e) {
                let isValid = true;
                let emptyFields = [];
                
                // Checks inputs with 'required' attribute
                const requiredInputs = form.querySelectorAll('[required]');
                
                requiredInputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                        const label = document.querySelector(`label[for="${input.id}"]`);
                        const labelText = label ? label.innerText : input.name;
                        emptyFields.push(labelText);
                        
                        input.style.borderColor = '#ef4444';
                        input.style.backgroundColor = '#fef2f2';
                    } else {
                        input.style.borderColor = '';
                        input.style.backgroundColor = '';
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap!',
                        html: `Mohon lengkapi field berikut:<br><br>
                               <ul style="text-align: left; list-style: circle; margin-left: 1rem;">
                                   ${emptyFields.map(field => `<li><b>${field}</b></li>`).join('')}
                               </ul>`,
                        confirmButtonText: 'Oke, Saya Lengkapi',
                        confirmButtonColor: '#4f46e5'
                    });
                    return; // Stop here if empty fields exist
                }

                // New: Check for Minimum Stock
                let lowStockItems = [];
                const stockInputs = form.querySelectorAll('input[name^="variants"][name$="[stock]"]');
                
                stockInputs.forEach(input => {
                    const val = parseInt(input.value);
                    if (!isNaN(val) && val < minStock) {
                        isValid = false;
                        lowStockItems.push(input);
                        input.style.borderColor = '#ef4444';
                        input.style.backgroundColor = '#fef2f2';
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Stok Tidak Valid!',
                        text: `Untuk produk baru, stok minimal adalah ${minStock} per ukuran.`,
                        confirmButtonText: 'Perbaiki',
                        confirmButtonColor: '#dc2626'
                    });
                }
            });

            // Clear error style on input
            form.querySelectorAll('[required]').forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.style.borderColor = '';
                        this.style.backgroundColor = '';
                    }
                });
            });
        }

        // 2. Auto-populate Sizes based on Category
        const categorySelect = document.getElementById('category_id');
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                const select = this;
                const selectedText = select.options[select.selectedIndex].text.toLowerCase();
                
                const tbody = document.querySelector('#variantsTable tbody');
                // Only populate if empty or has 1 empty row
                // Check if any input has value
                let hasValue = false;
                if (tbody) {
                    tbody.querySelectorAll('input').forEach(input => {
                        if(input.value.trim() !== '') hasValue = true;
                    });
                }

                if (hasValue) return;

                let suggestions = [];

                if (selectedText.includes('baju') || selectedText.includes('kaos') || selectedText.includes('jaket') || selectedText.includes('kemeja') || selectedText.includes('dress') || selectedText.includes('hoodie')) {
                    suggestions = ['S', 'M', 'L', 'XL', 'XXL'];
                } else if (selectedText.includes('celana') || selectedText.includes('jeans') || selectedText.includes('rok')) {
                    suggestions = ['27', '28', '29', '30', '31', '32', '33', '34'];
                } else if (selectedText.includes('sepatu') || selectedText.includes('sneakers') || selectedText.includes('sandal')) {
                    suggestions = ['38', '39', '40', '41', '42', '43', '44'];
                }

                if (suggestions.length > 0 && tbody) {
                    tbody.innerHTML = ''; 
                    suggestions.forEach(size => {
                         // Default stock to current min requirement (e.g. 2 for new products)
                        addVariantRow(size, minStock || 0); 
                    });
                }
            });
        }

        // 3. Populate existing variants (Edit Mode or Old Input)
        const productVariants = @json(isset($product) && $product->variants ? $product->variants : []);
        const oldVariants = @json(old('variants', []));

        let variantsPopulated = false;

        if (Array.isArray(oldVariants) && oldVariants.length > 0) {
             oldVariants.forEach(v => { addVariantRow(v.size, v.stock); variantsPopulated = true; });
        } else if (typeof oldVariants === 'object' && Object.keys(oldVariants).length > 0) {
            Object.values(oldVariants).forEach(v => { addVariantRow(v.size, v.stock); variantsPopulated = true; });
        } else {
             if (productVariants.length > 0) {
                productVariants.forEach(v => { addVariantRow(v.size, v.stock); variantsPopulated = true; });
             } else {
                // Determine if we should auto-populate from category immediately
                const categorySelect = document.getElementById('category_id');
                if (categorySelect && categorySelect.value) {
                     // Trigger change event to populate
                     categorySelect.dispatchEvent(new Event('change'));
                } else {
                    // Default empty row
                    addVariantRow();
                }
             }
        }
    });
</script>
@endsection
