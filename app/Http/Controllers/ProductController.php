<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function list()
    {
        $products = Product::with('images')->latest()->get();
        return response()->json($products);
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'product_price' => 'required|numeric',
            'product_description' => 'required',
        ]);

        $product = Product::create([
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description,
        ]);

        if($request->hasFile('product_image')){
            foreach($request->file('product_image') as $image){

                $name = time().'_'.$image->getClientOriginalName();
                $image->move(public_path('images'), $name);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $name
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Product Added Successfully'
        ]);
    }

   

    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description,
        ]);

        // Add new images if uploaded
        if($request->hasFile('product_image')){
            foreach($request->file('product_image') as $image){

                $name = time().'_'.$image->getClientOriginalName();
                $image->move(public_path('images'), $name);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $name
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Product Updated Successfully'
        ]);
    }

    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);

        $path = public_path('images/'.$image->image);

        if(file_exists($path)){
            unlink($path);
        }

        $image->delete();

        return response()->json(['status'=>true]);
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Deleted Successfully'
        ]);
    }

}