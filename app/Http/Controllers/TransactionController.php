<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Show the form for creating a new transaction.
     */
    public function create()
    {
        // Get products that have at least one variant with positive stock
        $products = Product::whereHas('variants', function ($query) {
            $query->where('stock', '>', 0);
        })->with('variants')->get();
        return view('transactions.create', compact('products'));
    }

    /**
     * Store a newly created transaction.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cart_json' => 'required|string',
        ]);

        $cart = json_decode($request->cart_json, true);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang belanja kosong!');
        }

        try {
            DB::transaction(function () use ($cart) {
                $orderId = 'TRX-' . strtoupper(\Illuminate\Support\Str::random(10));

                foreach ($cart as $item) {
                    // Lock product for update to prevent race conditions
                    $product = Product::lockForUpdate()->findOrFail($item['id']);
                    
                    // Find specific variant
                    $variant = $product->variants()->where('size', $item['size'])->first();

                    if (!$variant) {
                        throw new \Exception("Varian ukuran '{$item['size']}' untuk produk {$product->name} tidak ditemukan.");
                    }

                    if ($variant->stock < $item['qty']) {
                        throw new \Exception("Stok {$product->name} ({$item['size']}) tidak mencukupi! Tersisa: {$variant->stock}");
                    }

                    Transaction::create([
                        'user_id' => Auth::id(),
                        'product_id' => $product->id,
                        'order_id' => $orderId,
                        'size' => $item['size'],
                        'quantity' => $item['qty'],
                        'total_price' => $product->price * $item['qty'],
                    ]);

                    // Decrement variant stock
                    $variant->decrement('stock', $item['qty']);
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Transaksi Gagal: ' . $e->getMessage());
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil disimpan!');
    }

    /**
     * Display transaction history.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Fetch query based on role
        $query = Transaction::with(['user', 'product'])->latest();

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        // Date Filter
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $rawTransactions = $query->get();

        // Group by order_id
        $transactions = $rawTransactions->mapToGroups(function ($item) {
            return [$item->order_id ?? 'single_' . $item->id => $item];
        })->map(function ($group) {
            $first = $group->first();
            return (object) [
                'id' => $first->id, // valid ID for receipt link
                'order_id' => $first->order_id,
                'created_at' => $first->created_at,
                'user' => $first->user,
                'items_count' => $group->sum('quantity'),
                'grand_total' => $group->sum('total_price'),
                'product_summary' => $group->map(function ($t) {
                    return $t->size ? $t->product->name . ' (' . $t->size . ')' : $t->product->name;
                })->unique()->join(', ')
            ];
        }); // Collection of objects

        // Pass dates back to view
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        return view('transactions.index', compact('transactions', 'startDate', 'endDate'));
    }

    /**
     * Show print-friendly receipt.
     */
    public function receipt($id)
    {
        $currentTransaction = Transaction::findOrFail($id);

        if ($currentTransaction->order_id) {
            $transactions = Transaction::with(['user', 'product'])
                ->where('order_id', $currentTransaction->order_id)
                ->get();
        } else {
            $transactions = collect([$currentTransaction]);
        }
        
        // Use the first transaction for header info (user, time)
        $transaction = $transactions->first();

        // Check authorization
        if (!Auth::user()->isAdmin() && $transaction->user_id !== Auth::id()) {
            abort(403);
        }

        return view('transactions.receipt', compact('transactions', 'transaction'));
    }
}
