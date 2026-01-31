<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display dashboard with statistics.
     */
    public function index()
    {
        // Calculate total sales
        $totalSales = Transaction::sum('total_price');

        // Get most purchased product
        $mostPurchased = Transaction::select('product_id', DB::raw('COUNT(*) as purchase_count'), DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderBy('purchase_count', 'desc')
            ->with('product')
            ->first();

        return view('dashboard', compact('totalSales', 'mostPurchased'));
    }
}
