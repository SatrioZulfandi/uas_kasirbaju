@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<style>
    .search-wrapper {
        position: relative;
        flex: 1;
        max-width: 400px;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        width: 18px;
        height: 18px;
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        padding: 0.6rem 1rem 0.6rem 2.5rem;
        border: 1px solid var(--border);
        border-radius: 0.5rem;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
</style>

<h1 style="margin-bottom: 2rem;">Daftar Produk</h1>

<div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; background: #fff; padding: 1rem; border-radius: 0.75rem; border: 1px solid var(--border); align-items: center; flex-wrap: wrap;">
    <div class="search-wrapper" style="flex: 1; min-width: 200px;">
        <svg xmlns="http://www.w3.org/2000/svg" class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input type="text" id="searchInput" class="search-input" placeholder="Cari nama atau kode produk..." onkeyup="filterTable()">
    </div>

    <select id="categoryFilter" class="form-control" style="width: 200px; height: 42px; border-radius: 0.5rem; border: 1px solid var(--border); padding: 0 1rem; cursor: pointer;" onchange="filterTable()">
        <option value="">Semua Kategori</option>
        @foreach($categories as $category)
            <option value="{{ $category->name }}">{{ $category->name }}</option>
        @endforeach
    </select>

    <a href="{{ route('products.create') }}" class="btn btn-primary" style="height: 42px; padding: 0 1.25rem; white-space: nowrap; margin-left: auto;">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Tambah Produk
    </a>
</div>

<div class="card">
    <table id="productsTable">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Kode</th>
                <th>Kategori</th>
                <th>Nama Produk</th>
                <th>Ukuran</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Img" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                        @else
                            <div style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #cbd5e1;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                            </div>
                        @endif
                    </td>
                    <td class="searchable-code"><span class="badge badge-secondary" style="font-family: monospace;">{{ $product->product_code }}</span></td>
                    <td class="searchable-category">{{ $product->category->name }}</td>
                    <td class="searchable-name">{{ $product->name }}</td>
                    <td>{{ $product->sizes }}</td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $product->stock > 10 ? 'badge-success' : ($product->stock > 0 ? 'badge-danger' : 'badge-secondary') }}">
                            {{ $product->stock }} unit
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(this)">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-muted" style="text-align: center;">Belum ada produk</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(button) {
        Swal.fire({
            title: 'Hapus Produk?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the closest form
                button.closest('form').submit();
            }
        });
    }

    function filterTable() {
    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categoryFilter');
    
    const filterText = searchInput.value.toLowerCase();
    const filterCategory = categorySelect.value.toLowerCase();
    
    const table = document.getElementById('productsTable');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) { // Start from 1 to skip header
        // Get cells
        const codeTd = tr[i].getElementsByClassName('searchable-code')[0];
        const categoryTd = tr[i].getElementsByClassName('searchable-category')[0];
        const nameTd = tr[i].getElementsByClassName('searchable-name')[0];
        
        if (codeTd && categoryTd && nameTd) {
            const codeValue = codeTd.textContent || codeTd.innerText;
            const categoryValue = categoryTd.textContent || categoryTd.innerText;
            const nameValue = nameTd.textContent || nameTd.innerText;
            
            // Check matches
            const matchesText = nameValue.toLowerCase().indexOf(filterText) > -1 || 
                                codeValue.toLowerCase().indexOf(filterText) > -1;
            
            const matchesCategory = filterCategory === "" || categoryValue.toLowerCase() === filterCategory;
            
            if (matchesText && matchesCategory) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>
@endsection
