<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 'active');

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        $sort = $request->query('sort', 'newest');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();
        return view('pages.shop', compact('products', 'sort'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('category', $product->category)
            ->take(4)
            ->get();

        return view('pages.product-detail', compact('product', 'relatedProducts'));
    }

    // Cart Ajax Methods
    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = max(1, (int)$request->input('quantity', 1));

        $product = Product::findOrFail($productId);
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'slug' => $product->slug,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);
        $totalItems = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success' => true,
            'message' => "Đã thêm '{$product->name}' vào giỏ hàng!",
            'cart_count' => $totalItems,
        ]);
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('pages.cart', compact('cart', 'total'));
    }

    public function updateCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = (int)$request->input('quantity', 1);
        $cart = session()->get('cart', []);

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } elseif (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
        }

        session()->put('cart', $cart);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return response()->json([
            'success' => true,
            'cart_count' => array_sum(array_column($cart, 'quantity')),
            'subtotal' => number_format($subtotal) . ' đ',
            'item_subtotal' => isset($cart[$productId]) ? number_format($cart[$productId]['price'] * $cart[$productId]['quantity']) . ' đ' : 0,
        ]);
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('info', 'Giỏ hàng của bạn đang trống.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return view('pages.checkout', compact('cart', 'subtotal'));
    }

    public function placeOrder(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|regex:/^[0-9]{10,11}$/',
            'customer_email' => 'nullable|email',
            'shipping_address' => 'required|string|max:255',
            'payment_method' => 'required|in:cod,coins',
            'note' => 'nullable|string|max:500',
        ], [
            'customer_name.required' => 'Vui lòng nhập họ và tên nhận hàng.',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại liên lạc.',
            'customer_phone.regex' => 'Số điện thoại không đúng định dạng (10-11 chữ số).',
            'shipping_address.required' => 'Vui lòng nhập địa chỉ nhận hàng.',
        ]);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $orderCode = 'ORD-' . strtoupper(Str::random(8));

        // If paying with coins
        if ($validated['payment_method'] === 'coins') {
            if (!auth()->check()) {
                return back()->with('error', 'Bạn cần đăng nhập để thanh toán bằng Ví Xu.');
            }
            // 1 coin = 100 VND
            $coinsNeeded = ceil($subtotal / 100);
            if (auth()->user()->coin_balance < $coinsNeeded) {
                return back()->with('error', "Số dư Ví Xu không đủ ({auth()->user()->coin_balance} Xu). Cần {$coinsNeeded} Xu để thanh toán.");
            }
        }

        DB::transaction(function () use ($validated, $cart, $subtotal, $orderCode) {
            $order = Order::create([
                'user_id' => auth()->id() ?? null,
                'order_code' => $orderCode,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? (auth()->check() ? auth()->user()->email : null),
                'shipping_address' => $validated['shipping_address'],
                'total_amount' => $subtotal,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_method'] === 'coins' ? 'paid' : 'unpaid',
                'shipping_status' => 'pending',
                'note' => $validated['note'] ?? null,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Reduce stock
                Product::where('id', $item['id'])->decrement('stock_quantity', $item['quantity']);
            }

            if ($validated['payment_method'] === 'coins' && auth()->check()) {
                $coinsNeeded = ceil($subtotal / 100);
                auth()->user()->decrement('coin_balance', $coinsNeeded);
            }

            session()->forget('cart');
        });

        return redirect()->route('shop.orderSuccess', $orderCode);
    }

    public function orderSuccess($code)
    {
        $order = Order::where('order_code', $code)->with('items.product')->firstOrFail();
        return view('pages.order-success', compact('order'));
    }
}
