<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    private function cart(): array
    {
        return session()->get('cart', []);
    }

    private function storeCart(array $cart): void
    {
        session()->put('cart', $cart);
    }

    private function priceForProduct(Product $product, ?string $sizeKey): array
    {
        if (! $product->hasSizeOptions()) {
            return [(int) $product->display_price, null];
        }

        $size = collect($product->size_options)->firstWhere('key', $sizeKey);

        if (! $size) {
            abort(422, 'Vui lòng chọn size hợp lệ.');
        }

        return [(int) $size['price'], $size['label'] ?? null];
    }

    public function index(): View
    {
        $items = collect($this->cart())->map(function (array $item, string $key) {
            $item['key'] = $key;
            $item['subtotal'] = $item['price'] * $item['quantity'];

            return $item;
        })->values();

        return view('cart.index', [
            'items' => $items,
            'total' => $items->sum('subtotal'),
        ]);
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        $quantity = max(1, (int) $request->input('quantity', 1));
        $sizeKey = $request->input('size');

        [$price, $sizeLabel] = $this->priceForProduct($product, $sizeKey);
        $cart = $this->cart();
        $cartKey = $product->id . ($sizeKey ? '_' . $sizeKey : '');

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'image' => $product->image,
                'price' => $price,
                'quantity' => $quantity,
                'size_key' => $sizeKey,
                'size_label' => $sizeLabel,
                'min_lead_days' => $product->min_lead_days ?? 1,
            ];
        }

        $this->storeCart($cart);

        return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->cart();

        foreach ($validated['items'] as $key => $item) {
            if (isset($cart[$key])) {
                $cart[$key]['quantity'] = (int) $item['quantity'];
            }
        }

        $this->storeCart($cart);

        return redirect()->route('cart.index')->with('success', 'Đã cập nhật giỏ hàng.');
    }

    public function remove(string $key): RedirectResponse
    {
        $cart = $this->cart();
        unset($cart[$key]);

        $this->storeCart($cart);

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }
}