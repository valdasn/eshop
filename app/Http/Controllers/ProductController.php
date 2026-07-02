<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
   public function index(Request $request) 
{
    $query = Product::query();

    // Promotions
    if ($request->filled('promo')) {
        $promoType = $request->promo;
        
        $promoTitles = [
            'sleep-sale'  => 'Sleep & Relaxation - 35% OFF',
            'supercharge' => 'Supercharge Your Health',
            'fish-oil'    => 'High Purity Fish Oil - 20% OFF'
        ];

        // Filter products that have specific promo_type in DB
        $query->where('promo_type', $promoType);

        $this->applySorting($query, $request->sort);

        return view('home', [
            'products' => $query->get(),
            'displayName' => $promoTitles[$promoType] ?? 'Special Offer'
        ]);
    }

    // Search
    if ($request->filled('search')) {
        $query->where('name', 'LIKE', "%{$request->search}%");
        $this->applySorting($query, $request->sort);
        return view('home', ['products' => $query->get(), 'displayName' => 'Search Results']);
    }

    // Shop All
    if ($request->has('all')) {
        $this->applySorting($query, $request->sort);
        return view('home', ['products' => $query->get(), 'displayName' => 'Shop All']);
    }

    // Default
    $this->applySorting($query, $request->sort);
    return view('home', ['products' => $query->get(), 'displayName' => 'Our Collection']);
}

    public function category(Request $request, $slug) 
    {
        if ($slug === 'discounts') {
            $query = Product::whereNotNull('original_price');
            
            $category = (object) [
                'name' => 'Discounts',
                'slug' => 'discounts',
                'description' => 'Great deals on your favorite products!'
            ];
        } else {
            $category = Category::where('slug', $slug)->firstOrFail();
            $query = $category->products(); 
        }

        $this->applySorting($query, $request->sort);
        $products = $query->get();
        
        return view('home', compact('products', 'category'));
    }

    public function show(Product $product) 
    {
        $product->load('categories'); 
        return view('products.show', compact('product'));
    }

    private function applySorting($query, $sort)
    {
        switch ($sort) {
            case 'price_asc':  
                $query->orderBy('price', 'asc'); 
                break;
            case 'price_desc': 
                $query->orderBy('price', 'desc'); 
                break;
            case 'newest':     
                $query->orderBy('created_at', 'desc'); 
                break;
            default:           
                $query->latest(); 
                break;
        }
    }
}