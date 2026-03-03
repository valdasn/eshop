<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Home Page, Search, and Shop All logic.
     */
    public function index(Request $request) 
    {
        $query = Product::query();

        // 1. Apply SEARCH if present
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        // 2. Apply SORTING (Always available for Search and Shop All)
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':  $query->orderBy('price', 'asc'); break;
                case 'price_desc': $query->orderBy('price', 'desc'); break;
                case 'newest':     $query->orderBy('created_at', 'desc'); break;
                default:           $query->latest(); break;
            }
        } else {
            $query->latest(); // Default
        }

        // 3. Logic for "SHOP ALL" or "SEARCH RESULTS"
        if ($request->has('all') || $request->has('search')) {
            $products = $query->get();
            
            // Determine which view to use
            $view = $request->has('search') ? 'products.index' : 'home';
            $name = $request->has('search') ? 'Search Results' : 'Shop All';

            return view($view, compact('products'))->with('displayName', $name);
        }

        // 4. DEFAULT: Home Page (Show only 4 featured)
        $products = Product::latest()->take(4)->get();
        return view('home', compact('products'));
    }

    /**
     * Category filtering logic with Sorting.
     */
    public function category(Request $request, $slug) 
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        // Use the relationship as a query to allow sorting
        $query = $category->products(); 

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':  $query->orderBy('price', 'asc'); break;
                case 'price_desc': $query->orderBy('price', 'desc'); break;
                case 'newest':     $query->orderBy('created_at', 'desc'); break;
                default:           $query->latest(); break;
            }
        } else {
            $query->latest();
        }

        $products = $query->get();

        return view('home', compact('products', 'category'));
    }

    /**
     * Product details page.
     */
    public function show($id) 
    {
        $product = Product::with('categories')->findOrFail($id);
        return view('products.show', compact('product'));
    }
}