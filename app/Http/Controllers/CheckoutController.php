<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $user = auth()->user();
        $minLeadDays = $this->maxLeadDays($cart);
        $earliestDeliveryDate = Carbon::today()->addDays($minLeadDays)->format('Y-m-d');

        return view('checkout.index', compact('cart', 'total', 'user', 'minLeadDays', 'earliestDeliveryDate'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $minLeadDays = $this->maxLeadDays($cart);
        $earliestDate = Carbon::today()->addDays($minLeadDays)->format('Y-m-d');

        $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'required|string|max:20',
            'address'       => 'required|string|max:500',
            'note'          => 'nullable|string|max:1000',
            'delivery_date' => 'required|date|after_or_equal:' . $earliestDate,
            'cake_message'  => 'nullable|string|max:200',
        ], [
            'delivery_date.after_or_equal' => "Bánh cần đặt trước ít nhất {$minLeadDays} ngày. Ngày nhận sớm nhất: " . Carbon::parse($earliestDate)->format('d/m/Y'),
        ]);

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'user_id'       => auth()->id(),
            'name'          => $request->name,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'note'          => $request->note,
            'delivery_date' => $request->delivery_date,
            'cake_message'  => $request->cake_message,
            'total_price'   => $total,
            'status'        => 'pending',
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['product_id'],
                'price'      => $item['price'],
                'quantity'   => $item['quantity'],
                'size_label' => $item['size_label'] ?? null,
            ]);
        }

        session()->forget('cart');

        return redirect()->route('orders.show', $order)->with('success', 'Đặt bánh thành công! Mã đơn: #' . $order->id);
    }

    /**
     * Tính min_lead_days lớn nhất trong giỏ — đơn hàng phải đặt trước
     * theo sản phẩm cần lead time dài nhất.
     */
    private function maxLeadDays(array $cart): int
    {
        $max = 1;
        foreach ($cart as $item) {
            $lead = (int) ($item['min_lead_days'] ?? 1);
            if ($lead > $max) $max = $lead;
        }
        return $max;
    }
}
