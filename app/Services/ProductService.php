<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;

class ProductService
{
    public function getAllProducts()
    {
        return Product::with('images')->latest()->get();
    }

    public function storeProduct($request)
    {
        $product = Product::create([
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description,
        ]);

        $this->storeImages($request, $product->id);

        return $product;
    }

    public function updateProduct($request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description,
        ]);

        if ($request->hasFile('product_image')) {
            ProductImage::where('product_id', $id)->delete();
            $this->storeImages($request, $id);
        }

        return $product;
    }

    public function deleteProduct($id)
    {
        return Product::findOrFail($id)->delete();
    }

    private function storeImages($request, $productId)
    {
        if ($request->hasFile('product_image')) {

            foreach ($request->file('product_image') as $image) {

                $fileName = time().'_'.uniqid().'.'.$image->extension();

                $image->move(public_path('images'), $fileName);

                ProductImage::create([
                    'product_id' => $productId,
                    'image' => $fileName
                ]);
            }
        }
    }
}