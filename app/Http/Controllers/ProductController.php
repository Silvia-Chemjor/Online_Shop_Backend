<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function createProduct(Request $request){
        $request->validate([
            'name'=>'required|string|unique:products,name',
            'price'=>'required|numeric',
            'product_image'=>'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category_id'=>'required|integer|exists:categories,id'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->price =  $request->price;
        $product->category_id =  $request->category_id;

        if($request->hasFile('product_image')){
            $filename =$request->file('product_image')->store('product-image', 'public');
        }else{
            $filename = null;
        }

        $product->product_image = $filename;
        try{
            $product->save();
            return response()->json([
                'message'=>'Product saved successfully.',
                'product'=>$product
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Failed to save.',
                'error'=>$e->getMessage()
            ],500);

        }

    }
    public function readAll(){
        try{
            $products = Product::all();
            return response()->json([
                'products'=>$products
            ],200);
        }catch(\Exception $e){
        return response()->json([
            'message'=>'Failed to fetch.',
            'error'=>$e->getMessage()
        ],500);

        }
    }
     public function read($id){
        try{
            $product = Product::where('id',$id) ->first();
            return response()->json([
                'product'=>$product
            ],200);
        }catch(\Exception $e){
        return response()->json([
            'message'=>'Failed to fetch.',
            'error'=>$e->getMessage()
        ],500);
        }

    }
    public function updateProduct($id,Request $request){
       $request->validate([
            'name'=>'required|string',
            'price'=>'required|numeric',
            'product_image'=>'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category_id'=>'required|integer|exists:categories,id'
        ]);

        $product = Product::where('id', $id)->first();
        $product->name = $request->name;
        $product->price =  $request->price;
        $product->category_id =  $request->category_id;

        if($request->hasFile('product_image')){
            $filename =$request->file('product_image')->store('product-image', 'public');
        }else{
            $filename = null;
        }

        $product->product_image = $filename;
        try{
            $product->save();
            return response()->json([
                'message'=>'Product updated successfully.',
                'product'=>$product
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Failed to update.',
                'error'=>$e->getMessage()
            ],500);
            }

    }
    public function deleteProduct($id){
        try{
            $product =Product::where('id',$id)->first();
            if($product){
                $product -> delete();
                return response()->json("Record delete successfully.");
            }
            else{
                return response()->json("Record not found");
            }
        }catch(\Exception $e){
        return response()->json([
            'message'=>'Failed to delete.',
            'error'=>$e->getMessage()
        ],500);
        }
    }
}
