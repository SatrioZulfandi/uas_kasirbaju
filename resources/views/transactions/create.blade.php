@extends('layouts.app')

@section('title', 'Transaksi Kasir')

@section('content')
<style>
    /* Layout Overview */
    .pos-container {
        display: grid;
        grid-template-columns: 1fr 380px; /* Grid for Catalog vs Cart */
        gap: 2rem;
        height: calc(100vh - 100px); /* Adjust based on navbar height */
        align-items: start;
    }

    /* --- Left Side: Product Catalog --- */
    .catalog-section {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }

    /* Search & Filter Bar */
    .catalog-header {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .search-wrapper {
        flex: 1;
        position: relative;
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
        padding: 0.75rem 1rem 0.75rem 2.5rem;
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

    .category-tabs {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
        scrollbar-width: none;
    }

    .category-tab {
        padding: 0.6rem 1.2rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 2rem;
        cursor: pointer;
        white-space: nowrap;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
        color: var(--text-secondary);
    }

    .category-tab.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    /* Product Grid */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 1rem;
        overflow-y: auto;
        padding-right: 0.5rem;
        flex: 1;
        align-content: start; /* Prevent rows from stretching to fill height */
    }

    .product-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        overflow: hidden;
    }

    .product-card:hover {
        border-color: var(--primary);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    
    .product-image-container {
        width: 100%;
        height: 180px; /* Increased height as requested */
        background: #f8fafc; /* Subtle gray background */
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-bottom: 1px solid var(--border);
        position: relative;
    }
    
    .product-image-container img {
        width: 100%;
        height: 100%;
        object-fit: contain; /* Show full image */
        padding: 4px; /* Slight padding */
        transition: transform 0.3s;
    }

    .product-card:hover .product-image-container img {
        transform: scale(1.1); /* Zoom effect */
    }

    .product-info {
        padding: 0.75rem;
    }

    .product-code {
        font-size: 0.75rem;
        color: var(--secondary);
        background: #f1f5f9;
        padding: 0.1rem 0.4rem;
        border-radius: 0.25rem;
        align-self: flex-start;
        display: inline-block;
        margin-bottom: 0.5rem;
    }

    .product-name {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
        line-height: 1.3;
        color: var(--text-main);
    }

    .product-price {
        font-weight: 700;
        color: var(--primary);
        font-size: 1rem;
        margin-top: auto;
    }

    .product-stock {
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    /* --- Right Side: Cart Panel --- */
    .cart-panel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .cart-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cart-items {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .cart-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .cart-item-details h4 {
        font-size: 0.95rem;
        font-weight: 600;
        margin: 0 0 0.25rem 0;
    }

    .cart-item-price {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .qty-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #f8fafc;
        padding: 0.25rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border);
    }

    .qty-btn {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        font-weight: bold;
        transition: 0.2s;
    }

    .qty-btn.minus { background: #fee2e2; color: #dc2626; }
    .qty-btn.plus { background: #dcfce7; color: #166534; }
    
    .qty-display {
        width: 30px;
        text-align: center;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .cart-footer {
        padding: 1.5rem;
        border-top: 1px solid var(--border);
        background: #f8fafc;
        border-bottom-left-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 1.5rem;
    }

    .total-label { font-size: 0.95rem; color: var(--text-secondary); }
    .total-value { font-size: 1.75rem; font-weight: 800; color: var(--primary); }

    .btn-pay {
        width: 100%;
        padding: 1rem;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 0.5rem;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-pay:hover:not(:disabled) { background: var(--primary-dark); }
    .btn-pay:disabled { background: #cbd5e1; cursor: not-allowed; }

    /* Empty State */
    .empty-cart-message {
        text-align: center;
        margin-top: 3rem;
        color: var(--text-secondary);
        font-style: italic;
    }
</style>

<div class="pos-container">
    <!-- Catalog Section -->
    <div class="catalog-section">
        <div class="catalog-header">
            <div class="search-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" id="searchInput" class="search-input" placeholder="Cari nama atau kode produk..." onkeyup="filterProducts()">
            </div>
        </div>

        <div class="category-tabs">
            <button class="category-tab active" onclick="filterCategory('Semua')">Semua</button>
            @php 
                $uniqueCategories = $products->unique('category_id')->pluck('category.name', 'category_id'); 
            @endphp
            @foreach($uniqueCategories as $id => $name)
                <button class="category-tab" onclick="filterCategory('{{ $name }}')">{{ $name }}</button>
            @endforeach
        </div>
        
        <br>

        <div class="product-grid" id="productGrid">
            @foreach($products as $product)
                <div class="product-card" 
                     data-name="{{ strtolower($product->name) }}" 
                     data-code="{{ strtolower($product->product_code) }}"
                     data-category="{{ $product->category->name ?? 'Uncategorized' }}"
                     onclick="handleProductClick({{ $product->id }})">
                    
                    <div class="product-image-container">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                        @endif
                    </div>
                    
                    <div class="product-info">
                        <span class="product-code">{{ $product->product_code }}</span>
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-stock">Total Stok: {{ $product->stock }}</div>
                        <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Cart Section (unchanged) -->
    <div class="cart-panel">
        <div class="cart-header">
            <h3 style="margin:0;">Keranjang</h3>
            <button class="btn btn-sm btn-danger" onclick="clearCart()" style="border:none; background:transparent; color:var(--danger);">Reset</button>
        </div>

        <div class="cart-items" id="cartItems">
            <!-- Items injected by JS -->
            <div class="empty-cart-message">Belum ada barang dipilih</div>
        </div>

        <div class="cart-footer">
            <div class="total-row">
                <span class="total-label">Total Tagihan</span>
                <span class="total-value" id="totalAmount">Rp 0</span>
            </div>
            
            <form action="{{ route('transactions.store') }}" method="POST" id="checkoutForm">
                @csrf
                <input type="hidden" name="cart_json" id="cartJson">
                <button type="submit" class="btn-pay" id="payButton" disabled>
                    <span>Bayar Sekarang</span>
                    <span id="payAmount">Rp 0</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Pass PHP data to JS safely
    const allProducts = @json($products);
    let cart = [];

    async function handleProductClick(productId) {
        const product = allProducts.find(p => p.id === productId);
        if (!product) return;

        const variants = product.variants || [];
        const totalStock = product.stock;

        if (totalStock <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Stok Habis',
                text: 'Produk ini sedang tidak tersedia.',
                timer: 1500,
                showConfirmButton: false
            });
            return;
        }

        // Generate HTML for Size Buttons
        let variantHtml = '';
        if (variants.length > 0) {
            variantHtml = '<div class="d-flex flex-wrap gap-2 justify-content-center mb-3" id="variant-options">';
            variants.forEach(v => {
                const disabled = v.stock <= 0 ? 'disabled' : '';
                const style = v.stock <= 0 ? 'opacity: 0.5; cursor: not-allowed;' : 'cursor: pointer;';
                const stockText = v.stock > 0 ? `Stok: ${v.stock}` : 'Habis';
                
                variantHtml += `
                    <input type="radio" class="btn-check" name="size_option" id="var_${v.id}" value="${v.size}" data-stock="${v.stock}" autocomplete="off" ${disabled}>
                    <label class="btn btn-outline-primary m-1" for="var_${v.id}" style="${style} min-width: 80px;">
                        <strong>${v.size}</strong><br>
                        <small style="font-size: 0.7rem;">${stockText}</small>
                    </label>
                `;
            });
            variantHtml += '</div>';
        }

        const { value: formValues } = await Swal.fire({
            title: `<span style="font-size: 1.2rem; font-weight: 600;">${product.name}</span>`,
            html: `
                <div style="text-align: center;">
                    <p id="swal-total-price" style="color: var(--primary); font-weight: 700; font-size: 1.1rem; margin-bottom: 1rem;">
                        Rp ${new Intl.NumberFormat('id-ID').format(product.price)}
                    </p>
                    
                    ${variants.length > 0 ? '<p style="margin-bottom: 0.5rem; font-size: 0.9rem; color: #64748b;">Pilih Ukuran:</p>' : ''}
                    ${variantHtml}

                    <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-top: 1.5rem;">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="adjustQty(-1)" style="width: 40px; height: 40px; font-size: 1.2rem;">-</button>
                        <input type="number" id="swal-qty" class="form-control" value="1" min="1" max="${totalStock}" style="width: 80px; text-align: center; font-weight: bold;">
                        <button type="button" class="btn btn-primary btn-sm" onclick="adjustQty(1)" style="width: 40px; height: 40px; font-size: 1.2rem;">+</button>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Masukan Keranjang',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            didOpen: () => {
                // Pre-select first available or single variant
                const firstAvailable = document.querySelector('input[name="size_option"]:not([disabled])');
                if (firstAvailable) {
                    firstAvailable.click();
                    // Update max on init
                    const max = firstAvailable.dataset.stock;
                    document.getElementById('swal-qty').max = max; 
                }
                
                // Add event listener for size change to update max qty
                const radios = document.querySelectorAll('input[name="size_option"]');
                radios.forEach(radio => {
                    radio.addEventListener('change', (e) => {
                         const max = e.target.dataset.stock;
                         const qtyInput = document.getElementById('swal-qty');
                         qtyInput.max = max;
                         if (parseInt(qtyInput.value) > parseInt(max)) {
                             qtyInput.value = max;
                         }
                    });
                });

                // Attach adjustQty to window for button clicks inside SweetAlert
                window.adjustQty = (change) => {
                    const input = document.getElementById('swal-qty');
                    let newVal = parseInt(input.value) + change;
                    const max = parseInt(input.max) || 999;
                    
                    if (newVal < 1) newVal = 1;
                    if (newVal > max) newVal = max;
                    
                    input.value = newVal;
                    updateModalPrice(newVal);
                };

                // Helper to update price display in modal
                const updateModalPrice = (qty) => {
                    const priceElement = document.getElementById('swal-total-price');
                    if(priceElement) {
                         const total = qty * product.price;
                         priceElement.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                    }
                };
                
                // Add listener to manual input too
                document.getElementById('swal-qty').addEventListener('input', (e) => {
                    let val = parseInt(e.target.value) || 1;
                    updateModalPrice(val);
                });
            },
            preConfirm: () => {
                const qty = parseInt(document.getElementById('swal-qty').value);
                let selectedSize = null;
                let maxStockForSize = totalStock;

                if (variants.length > 0) {
                    const checked = document.querySelector('input[name="size_option"]:checked');
                    if (!checked) {
                        Swal.showValidationMessage('Silakan pilih ukuran terlebih dahulu');
                        return false;
                    }
                    selectedSize = checked.value;
                    maxStockForSize = parseInt(checked.dataset.stock);
                }

                if (qty > maxStockForSize) {
                    Swal.showValidationMessage(`Stok tidak mencukupi (Max: ${maxStockForSize})`);
                    return false;
                }

                return { qty, selectedSize, maxStockForSize };
            }
        });

        if (formValues) {
            addToCart(product.id, product.name, product.price, formValues.maxStockForSize, formValues.selectedSize, formValues.qty);
        }
    }

    function addToCart(id, name, price, maxStock, size, qty = 1) {
        // Unique key for item in cart is ID + Size
        const existingItem = cart.find(item => item.id === id && item.size === size);

        if (existingItem) {
            if (existingItem.qty + qty <= maxStock) {
                existingItem.qty += qty;
            } else {
                Swal.fire('Stok Terbatas', 'Mencapai batas stok tersedia!', 'warning');
                existingItem.qty = maxStock; // Max out
            }
        } else {
            cart.push({ id, name, price, qty: qty, maxStock, size });
        }

        renderCart();
    }

    function updateQty(id, size, delta) {
        // We need to match size as well, passed as string or null
        // Beware: JS passes null as 'null' string sometimes if not careful, but here we invoke from renderCart with JS value
        const item = cart.find(i => i.id === id && i.size === size);
        if (!item) return;

        const newQty = item.qty + delta;

        if (newQty <= 0) {
            cart = cart.filter(i => !(i.id === id && i.size === size)); // Remove item
        } else if (newQty > item.maxStock) {
            Swal.fire('Stok Terbatas', 'Stok tidak mencukupi!', 'warning');
        } else {
            item.qty = newQty;
        }

        renderCart();
    }

    function clearCart() {
        if(cart.length === 0) return;
        
        Swal.fire({
            title: 'Kosongkan Keranjang?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kosongkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                cart = [];
                renderCart();
            }
        });
    }

    function renderCart() {
        const container = document.getElementById('cartItems');
        container.innerHTML = '';

        let total = 0;

        if (cart.length === 0) {
            container.innerHTML = '<div class="empty-cart-message">Belum ada barang dipilih</div>';
            document.getElementById('payButton').disabled = true;
            document.getElementById('totalAmount').innerText = 'Rp 0';
            document.getElementById('payAmount').innerText = 'Rp 0';
            return;
        }

        cart.forEach(item => {
            total += item.price * item.qty;
            
            // Format size for display (handle null)
            const sizeLabel = item.size ? ` <span class="badge badge-info" style="font-size:0.75rem;">${item.size}</span>` : '';
            // Quote the size string for JS call
            const sizeParam = item.size ? `'${item.size}'` : 'null';

            const html = `
                <div class="cart-item">
                    <div class="cart-item-details">
                        <h4>${item.name}${sizeLabel}</h4>
                        <div class="cart-item-price">Rp ${new Intl.NumberFormat('id-ID').format(item.price)} x ${item.qty}</div>
                    </div>
                    <div class="qty-controls">
                        <button class="qty-btn minus" type="button" onclick="updateQty(${item.id}, ${sizeParam}, -1)">-</button>
                        <span class="qty-display">${item.qty}</span>
                        <button class="qty-btn plus" type="button" onclick="updateQty(${item.id}, ${sizeParam}, 1)">+</button>
                    </div>
                </div>
            `;
            container.innerHTML += html;
        });

        const formattedTotal = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        document.getElementById('totalAmount').innerText = formattedTotal;
        document.getElementById('payAmount').innerText = formattedTotal;
        document.getElementById('payButton').disabled = false;
        
        // Update Hidden Input
        document.getElementById('cartJson').value = JSON.stringify(cart);
    }

    // --- Search & Filter Logic ---
    function filterProducts() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const activeTab = document.querySelector('.category-tab.active');
        const activeCategory = activeTab ? activeTab.innerText : 'Semua';
        const cards = document.querySelectorAll('.product-card');

        cards.forEach(card => {
            const name = card.dataset.name;
            const code = card.dataset.code;
            const category = card.dataset.category;

            const matchesSearch = name.includes(query) || code.includes(query);
            const matchesCategory = activeCategory === 'Semua' || category === activeCategory;

            if (matchesSearch && matchesCategory) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function filterCategory(categoryName) {
        // Update active tab
        document.querySelectorAll('.category-tab').forEach(tab => {
            if(tab.innerText === categoryName) tab.classList.add('active');
            else tab.classList.remove('active');
        });

        filterProducts(); // Re-run filter
    }
</script>
@endsection
