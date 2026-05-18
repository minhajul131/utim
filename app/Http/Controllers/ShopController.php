<?php

namespace App\Http\Controllers;

USE App\Models\Product;
USE App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request){

        $f_categories = $request->query('categories');

        $categories = Category::orderBy('name','ASC')->get();
        $products = Product::where(function($query) use($f_categories){
            $query->whereIn('category_id',explode(',',$f_categories))->orWhereRaw("'".$f_categories."'=''");
        })
        ->orderBy('created_at','DESC')->paginate(20);
        
        return view('shop',compact('products','categories','f_categories'));
    }

    public function product_details($product_slug){
        $product = Product::where('slug',$product_slug)->first();
        $rproducts = Product::where('slug','<>',$product_slug)->get()->take(8);
        return view('details',compact('product','rproducts'));
    }
}
