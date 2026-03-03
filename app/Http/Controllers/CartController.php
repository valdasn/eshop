<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CartController extends Controller
{
    // 1. Show the items currently in the cart
    public function index() 
    {
        $cart = session()->get('cart', []);
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        
        return view('cart.index', compact('cart', 'total'));
    }

    // 2. Add product (+1 increment)
    public function add(Request $request, $id) 
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax()) {
            $totalQty = array_sum(array_column(session('cart', []), 'quantity'));
            return response()->json([
                'status' => 'success',
                'cartCount' => $totalQty 
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    // 3. Update quantity (AJAX for Cart Page)
    public function update(Request $request)
    {
        if($request->id && $request->quantity) {
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            
            return response()->json([
                'status' => 'success',
                'cartCount' => array_sum(array_column($cart, 'quantity')),
                'itemSubtotal' => number_format($cart[$request->id]['price'] * $request->quantity, 2),
                'total' => number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)), 2)
            ]);
        }
    }

    // 4. Remove item
    public function remove($id) 
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        // If cart is now empty, it's better to forget the session key entirely
        if (empty($cart)) {
            session()->forget('cart');
        }

        return redirect()->back()->with('success', 'Product removed successfully!');
    }

    // NEW: 4b. Clear entire cart
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    }

    // 5. Stripe Checkout Redirection
    public function stripeCheckout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('cart.index');

        // SAVE SHIPPING INFO TO SESSION BEFORE REDIRECT
        session()->put('pending_order_data', $request->only(['phone', 'address', 'city', 'postal_code']));

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = [];
        foreach ($cart as $id => $details) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => $details['name']],
                    'unit_amount' => $details['price'] * 100, // Stripe expects amounts in cents
                ],
                'quantity' => $details['quantity'],
            ];
        }

        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success'),
            'cancel_url' => route('checkout'),
        ]);

        return redirect($checkoutSession->url);
    }

    // 6. Stripe Success: Save Order with Shipping Details
    public function stripeSuccess()
    {
        $cart = session()->get('cart', []);
        $shipping = session()->get('pending_order_data');
        
        if (empty($cart)) return redirect()->route('home');

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id'     => Auth::id(),
                'total_price' => $total,
                'status'      => 'paid',
                'phone'       => $shipping['phone'] ?? null,
                'address'     => $shipping['address'] ?? null,
                'city'        => $shipping['city'] ?? null,
                'postal_code' => $shipping['postal_code'] ?? null,
            ]);

            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);
            }

            DB::commit();
            session()->forget(['cart', 'pending_order_data']);

            return view('checkout.success');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Stripe Success Error: " . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Error creating order record.');
        }
    }

    // 7. Show Checkout
    public function showCheckout() 
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('cart.index');

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $user = Auth::user();

        return view('checkout.index', compact('cart', 'total', 'user'));
    }

    // 8. Order History
    public function orderHistory()
    {
        $orders = Order::with('items.product')
                       ->where('user_id', Auth::id())
                       ->orderBy('created_at', 'desc')
                       ->get();

        return view('orders.history', compact('orders'));
    }
}