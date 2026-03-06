<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }

    public function list()
    {
        $products = Product::with(['images','user'])->latest()->get();
        return response()->json($products);
    }
}
