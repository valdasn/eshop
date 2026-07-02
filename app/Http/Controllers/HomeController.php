<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
    $featuredProducts = Product::whereNull('original_price')->take(4)->get(); 
    $saleProducts = Product::whereNotNull('original_price')->get(); 

    return view('home', compact('featuredProducts', 'saleProducts'));
    }
}
