<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::where('is_admin', false)->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $ordersByStatus = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('admin.dashboard', compact(
            'totalProducts', 'totalOrders', 'totalUsers', 'totalRevenue',
            'recentOrders', 'ordersByStatus'
        ));
    }
}
