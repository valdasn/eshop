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
    // Show current items in the cart
    public function index() 
    {
        $cart = session()->get('cart', []);
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        
        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, $id) 
{
    if (is_numeric($id)) {
        $product = Product::findOrFail($id);
    } else {
        $product = Product::where('slug', $id)->firstOrFail();
    }

    $actualId = $product->id;
    $cart = session()->get('cart', []);

    if(isset($cart[$actualId])) {
        $cart[$actualId]['quantity']++;
    } else {
        $cart[$actualId] = [
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

    // Update quantity
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

    // Remove item
   public function remove($id)
{
    $cart = session()->get('cart');

    if(isset($cart[$id])) {
        unset($cart[$id]);
        
        if (empty($cart)) {
            session()->forget('cart');
            $cart = []; 
        } else {
            session()->put('cart', $cart);
        }
    }

    $total = 0;
    foreach($cart as $details) {
        $total += $details['price'] * $details['quantity'];
    }

    if(request()->ajax()) {
        return response()->json([
            'status' => 'success',
            'total' => number_format($total, 2),
            'cartCount' => count($cart)
        ]);
    }

    return redirect()->back()->with('success', 'Product removed successfully!');
}

    // Clear entire cart
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    }

    // Stripe Checkout Redirection
    public function stripeCheckout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('cart.index');

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

    // Stripe Success
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

    // Show Checkout
    public function showCheckout() 
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('cart.index');

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $user = Auth::user();

        return view('checkout.index', compact('cart', 'total', 'user'));
    }

    // Order History
    public function orderHistory()
    {
        $orders = Order::with('items.product')
                       ->where('user_id', Auth::id())
                       ->orderBy('created_at', 'desc')
                       ->get();

        return view('orders.history', compact('orders'));
    }
}