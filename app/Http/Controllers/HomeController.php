<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
USE App\Models\Slide;
USE App\Models\Category;
USE App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slide::where('status',1)->get()->take(3);
        $categories = Category::orderBy('name')->get();
        $fproducts = Product::where('featured',1)->get()->take(8);
        $aproducts = Product::all();
        $stills = Slide::where('status',0)->get()->take(2);
        return view('index',compact('slides','categories','fproducts','aproducts','stills'));
    }

    public function search(Request $request){

        $query = $request->input('query');
        $results = Product::where('name','LIKE',"%{$query}%")->get()->take(8);
        return response()->json($results);
    }
}
