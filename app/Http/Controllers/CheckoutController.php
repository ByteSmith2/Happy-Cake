<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    private function cart(): array
    {
        return session()->get('cart', []);
    }

    private function maxLeadDays(array $cart): int
    {
        $max = 1;

        foreach ($cart as $item) {
            $leadDays = (int) ($item['min_lead_days'] ?? 1);

            if ($leadDays > $max) {
                $max = $leadDays;
            }
        }

        return $max;
    }

    private function totalPrice(array $cart): int
    {
        return array_reduce($cart, function (int $carry, array $item) {
            return $carry + ((int) $item['price'] * (int) $item['quantity']);
        }, 0);
    }

    public function index(): View|RedirectResponse
    {
        $cart = $this->cart();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng đang trống.');
        }

        $minLeadDays = $this->maxLeadDays($cart);
        $earliestDate = now()->addDays($minLeadDays)->toDateString();

        return view('checkout.index', [
            'items' => $cart,
            'total' => $this->totalPrice($cart),
            'minLeadDays' => $minLeadDays,
            'earliestDate' => $earliestDate,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = $this->cart();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng đang trống.');
        }

        $minLeadDays = $this->maxLeadDays($cart);
        $earliestDate = now()->addDays($minLeadDays)->toDateString();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
            'note' => ['nullable', 'string', 'max:1000'],
            'cake_message' => ['nullable', 'string', 'max:200'],
            'delivery_date' => ['required', 'date', 'after_or_equal:' . $earliestDate],
        ], [
            'delivery_date.after_or_equal' => 'Bánh cần đặt trước ít nhất ' . $minLeadDays . ' ngày. Ngày nhận sớm nhất là ' . $earliestDate . '.',
        ]);

        $order = DB::transaction(function () use ($cart, $validated) {
            $order = Order::create([
                'user_id' => null,
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'note' => $validated['note'] ?? null,
                'delivery_date' => $validated['delivery_date'],
                'cake_message' => $validated['cake_message'] ?? null,
                'total_price' => $this->totalPrice($cart),
                'status' => 'pending',
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'size_label' => $item['size_label'] ?? null,
                ]);
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()->route('checkout.success', $order)->with('success', 'Đặt hàng thành công.');
    }

    public function success(Order $order): View
    {
        $order->load('items');

        return view('checkout.success', compact('order'));
    }
}