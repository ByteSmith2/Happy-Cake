<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        $qty = max(1, (int) $request->input('quantity', 1));

        $sizeKey = $request->input('size');
        $sizeLabel = null;
        $price = $product->sale_price ?? $product->price;

        if ($product->hasSizeOptions()) {
            if (!$sizeKey) {
                return redirect()->back()->with('error', 'Vui lòng chọn size bánh trước khi thêm vào giỏ.');
            }
            $matched = collect($product->size_options)->firstWhere('key', $sizeKey);
            if (!$matched) {
                return redirect()->back()->with('error', 'Size đã chọn không hợp lệ.');
            }
            $sizeLabel = $matched['label'];
            $price = $matched['price'];
        }

        // Composite key for cart: id, or id_sizeKey if size selected
        $cartKey = $sizeKey ? $product->id . '_' . $sizeKey : (string) $product->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $qty;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $price,
                'image' => $product->image,
                'quantity' => $qty,
                'size_key' => $sizeKey,
                'size_label' => $sizeLabel,
                'min_lead_days' => $product->min_lead_days,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Đã thêm bánh vào giỏ hàng!');
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        foreach ($request->quantity as $key => $qty) {
            if (isset($cart[$key])) {
                if ($qty <= 0) {
                    unset($cart[$key]);
                } else {
                    $cart[$key]['quantity'] = (int) $qty;
                }
            }
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã được cập nhật!');
    }

    public function remove($key)
    {
        $cart = session()->get('cart', []);
        unset($cart[$key]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Đã xóa bánh khỏi giỏ hàng!');
    }
}
