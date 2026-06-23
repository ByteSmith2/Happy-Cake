<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('price_from')) {
            $query->where(function ($q) use ($request) {
                $q->whereNotNull('sale_price')->where('sale_price', '>=', $request->price_from)
                  ->orWhereNull('sale_price')->where('price', '>=', $request->price_from);
            });
        }

        if ($request->filled('price_to')) {
            $query->where(function ($q) use ($request) {
                $q->whereNotNull('sale_price')->where('sale_price', '<=', $request->price_to)
                  ->orWhereNull('sale_price')->where('price', '<=', $request->price_to);
            });
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_asc' => $query->orderByRaw('COALESCE(sale_price, price) ASC'),
                'price_desc' => $query->orderByRaw('COALESCE(sale_price, price) DESC'),
                'newest' => $query->latest(),
                'name' => $query->orderBy('name'),
                default => $query->latest(),
            };
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('products')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function byCategory(Category $category)
    {
        $products = $category->products()->paginate(12);
        $categories = Category::withCount('products')->get();

        return view('products.index', compact('products', 'categories', 'category'));
    }
}
