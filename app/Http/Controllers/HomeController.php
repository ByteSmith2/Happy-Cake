<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('featured', true)->take(8)->get();
        $categories = Category::withCount('products')->get();
        $latestProducts = Product::latest()->take(8)->get();

        // Section dành riêng cho bánh sinh nhật (category slug 'banh-sinh-nhat')
        $birthdayCakes = Product::whereHas('category', fn($q) => $q->where('slug', 'banh-sinh-nhat'))
            ->take(4)
            ->get();

        return view('home', compact('featuredProducts', 'categories', 'latestProducts', 'birthdayCakes'));
    }
}
