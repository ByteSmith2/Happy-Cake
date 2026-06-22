<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        $data = $this->prepareData($request, $validated);
        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Bánh đã được tạo thành công!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validatedData($request);

        $data = $this->prepareData($request, $validated);
        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Bánh đã được cập nhật!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Bánh đã được xóa!');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'price'          => 'required|numeric|min:0',
            'sale_price'     => 'nullable|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|max:2048',
            'featured'       => 'nullable|boolean',
            'min_lead_days'  => 'required|integer|min:1|max:14',
            'sizes'          => 'nullable|array',
            'sizes.*.label'  => 'nullable|string|max:100',
            'sizes.*.price'  => 'nullable|numeric|min:0',
        ]);
    }

    private function prepareData(Request $request, array $validated): array
    {
        $data = $request->only('name', 'category_id', 'price', 'sale_price', 'stock', 'description', 'min_lead_days');
        $data['slug'] = Str::slug($request->name);
        $data['featured'] = $request->boolean('featured');

        // size_options: chỉ giữ lại các size có cả label và price
        $sizes = collect($request->input('sizes', []))
            ->filter(fn($s) => !empty($s['label']) && !empty($s['price']))
            ->values()
            ->map(fn($s, $i) => [
                'key'   => 'size' . ($i + 1),
                'label' => $s['label'],
                'price' => (int) $s['price'],
            ])
            ->toArray();
        $data['size_options'] = count($sizes) > 0 ? $sizes : null;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        return $data;
    }
}
