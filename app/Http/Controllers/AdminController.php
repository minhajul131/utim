<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
USE App\Models\Category;
USE App\Models\Product;
USE App\Models\Slide;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index(){


        return view('admin.index');
    }

    public function categories(){
        $categories = Category::orderBy('id','DESC')->paginate(10);
        return view('admin.categories',compact('categories'));
    }

    public function add_category(){
        
        return view('admin.category-add');
    }

    public function category_store(Request $request){
        
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg,webp|max:2048'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extention;
        $this->GenerateCategoryThumbailsImage($image,$file_name);
        $category->image = $file_name;
        $category->save();
        return redirect()->route('admin.categories')->with('status','Category has been added succesfully!');
    }

    public function category_edit($id){
        
        $category = Category::find($id);
        return view('admin.category-edit',compact('category'));
    }
    
    public function category_update(Request $request){
        
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $request->id,
            'image' => 'mimes:png,jpg,jpeg,webp|max:2048'
        ]);

        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        if($request->hasFile('image')){ 
            if(File::exists(public_path('uploads/categories').'/'.$category->image)){
                File::delete(public_path('uploads/categories').'/'.$category->image);
            }
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extention;
            $this->GenerateCategoryThumbailsImage($image,$file_name);
            $category->image = $file_name;
        }
        $category->save();
        return redirect()->route('admin.categories')->with('status','Category has been updated succesfully!');
    }

    public function category_delete($id){
        $category = Category::find($id);
        if(File::exists(public_path('uploads/categories').'/'.$category->image)){
                File::delete(public_path('uploads/categories').'/'.$category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status', "Category has been deleted successfully!");
    }

    public function GenerateCategoryThumbailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/categories');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }

    public function products(){
        $products = Product::orderBy('created_at','DESC')->paginate(10);
        return view('admin.products',compact('products'));
    }

    public function add_product(){
        
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-add',compact('categories'));
    }

    public function product_store(Request $request){

        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg,webp|max:2048',
            'category_id' => 'required|integer|exists:categories,id'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;

        $current_timestamp = Carbon::now()->timestamp;

        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailImage($image,$imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('images'))
        {
            $allowedfileExtion = ['jpg','png','jpeg','webp'];
            $files = $request->file('images');
            foreach($files as $file)
            {
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension,$allowedfileExtion);
                if($gcheck)
                {
                    $gfileName = $current_timestamp . "-" . $counter . "." . $gextension;
                    $this->GenerateProductThumbnailImage($file,$gfileName);
                    array_push($gallery_arr,$gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',',$gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();
        return redirect()->route('admin.products')->with('status','Product has been added successfully!');
    }

    public function GenerateProductThumbnailImage($image, $imageName)
    {
        $destinationPathThumbnail = public_path('uploads/products/thumbnails');
        $destinationPath = public_path('uploads/products');
        $img = Image::read($image->path());

        $img->cover(540,689,"top");
        $img->resize(540,689,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);

        $img->resize(104,104,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPathThumbnail.'/'.$imageName);
    }

    public function product_edit($id){
        
        $product = Product::find($id);
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-edit',compact('product','categories'));
    }

    public function product_update(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug,'.$request->id,
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'mimes:png,jpg,jpeg,webp|max:2048',
            'category_id' => 'required'
        ]);

        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;

        $current_timestamp = Carbon::now()->timestamp;

        if($request->hasFile('image'))
        {
            if(File::exists(public_path('uploads/products').'/'.$product->image)){
                File::delete(public_path('uploads/products').'/'.$product->image);
            }
            if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image)){
                File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
            }
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailImage($image,$imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('images'))
        {
            foreach(explode(',',$product->images) as $ofile){
                if(File::exists(public_path('uploads/products').'/'.$ofile)){
                    File::delete(public_path('uploads/products').'/'.$ofile);
                }
                if(File::exists(public_path('uploads/products/thumbnails').'/'.$ofile)){
                    File::delete(public_path('uploads/products/thumbnails').'/'.$ofile);
                }
            }
            $allowedfileExtion = ['jpg','png','jpeg','webp'];
            $files = $request->file('images');
            foreach($files as $file)
            {
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension,$allowedfileExtion);
                if($gcheck)
                {
                    $gfileName = $current_timestamp . "-" . $counter . "." . $gextension;
                    $this->GenerateProductThumbnailImage($file,$gfileName);
                    array_push($gallery_arr,$gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',',$gallery_arr);
            $product->images = $gallery_images;
        }
        
        $product->save();
        return redirect()->route('admin.products')->with('status','Product has been updated successfully!');
    }

    // CKEditor 5
    public function upload_editor_image(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            
            // ছবির নাম স্লাগ আকারে সুন্দর করা
            $fileName = Str::slug($fileName) . '_' . time() . '.' . $extension;
            
            // আপলোড পাথ নিশ্চিত করা
            $destinationPath = public_path('uploads/products/editor');
            
            // ফোল্ডার না থাকলে তৈরি করবে পারমিশনসহ
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // ফাইল মুভ করা
            $request->file('upload')->move($destinationPath, $fileName);
            
            // ব্রাউজারে দেখানোর জন্য সঠিক URL জেনারেট করা
            $url = asset('uploads/products/editor/' . $fileName);
            
            // CKEditor 5 এর জন্য স্ট্যান্ডার্ড JSON রেসপন্স
            return response()->json([
                'uploaded' => true,
                'url' => $url
            ]);
        }
        
        return response()->json([
            'uploaded' => false, 
            'error' => ['message' => 'Image upload failed.']
        ], 400);
    }

    public function product_delete($id){

        $product = Product::find($id);
        if(File::exists(public_path('uploads/products').'/'.$product->image)){
                File::delete(public_path('uploads/products').'/'.$product->image);
        }
        if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image)){
                File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
        }

        foreach(explode(',',$product->images) as $ofile){
                if(File::exists(public_path('uploads/products').'/'.$ofile)){
                    File::delete(public_path('uploads/products').'/'.$ofile);
                }
                if(File::exists(public_path('uploads/products/thumbnails').'/'.$ofile)){
                    File::delete(public_path('uploads/products/thumbnails').'/'.$ofile);
                }
            }

        // ৩. CKEditor (Description) এর ভেতরের ইমেজগুলো ডিলিট করার লজিক
        if(!empty($product->description)) {
            // ডেসক্রিপশনের HTML থেকে সব <img src="..."> খুঁজে বের করার প্যাটার্ন
            preg_match_all('/<img[^>]+src="([^">]+)"/i', $product->description, $matches);
            
            if(!empty($matches[1])) {
                foreach($matches[1] as $imageUrl) {
                    // URL থেকে শুধু ফাইলের নাম বা আপলোড ফোল্ডারের পরের অংশটুকু আলাদা করা
                    if (str_contains($imageUrl, 'uploads/products/editor/')) {
                        $filename = basename($imageUrl); 
                        $editorImagePath = public_path('uploads/products/editor/' . $filename);
                        
                        // ফাইলটি সার্ভারে থাকলে ডিলিট করবে
                        if(File::exists($editorImagePath)){
                            File::delete($editorImagePath);
                        }
                    }
                }
            }
        }

        $product->delete();
        return redirect()->route('admin.products')->with('status', "Product has been deleted successfully!");
    }

    public function slides(){

        $slides = Slide::orderBy('id','DESC')->paginate(12);
        return view('admin.slides',compact('slides'));
    }

    public function slide_add(){

        return view('admin.slide-add');
    }

    public function slide_store(Request $request){

        $request->validate([
            'tagline' => 'required',
            'title' => 'required',
            'subtitle' => 'required',
            'link' => '',
            'status' => 'required',
            'image' => 'mimes:png,jpg,jpeg,webp|max:2048'
        ]);

        $slide = new Slide();
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->link = $request->link;
        $slide->status = $request->status;
        
        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extention;
        $this->GenerateSildeThumbailsImage($image,$file_name);
        $slide->image = $file_name;

        $slide->save();
        return redirect()->route('admin.slides')->with('status','Slide has been added succesfully!');
    }

    public function GenerateSildeThumbailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/slides');
        $img = Image::read($image->path());
        $img->cover(750,690,"top");
        $img->resize(750,690,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }

    public function slide_edit($id){
        
        $slide = Slide::find($id);
        return view('admin.slide-edit',compact('slide'));
    }

    public function slide_update(Request $request){

        $request->validate([
            'tagline' => 'required',
            'title' => 'required',
            'subtitle' => 'required',
            'link' => '',
            'status' => 'required',
            'image' => 'mimes:png,jpg,jpeg,webp|max:2048'
        ]);

        $slide = Slide::find($request->id);
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->status = $request->status;
        $slide->link = $request->link;

        if($request->hasFile('image')){ 
            if(File::exists(public_path('uploads/slides').'/'.$slide->image)){
                File::delete(public_path('uploads/slides').'/'.$slide->image);
            }
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extention;
            $this->GenerateSildeThumbailsImage($image,$file_name);
            $slide->image = $file_name;
        }

        $slide->save();
        return redirect()->route('admin.slides')->with('status','slide has been updated succesfully!');
    }

    public function slide_delete($id){
        $slide = Slide::find($id);
        if(File::exists(public_path('uploads/slides').'/'.$slide->image)){
            File::delete(public_path('uploads/slides').'/'.$slide->image);
        }
        $slide->delete();
        return redirect()->route('admin.slides')->with('status', "slide has been deleted successfully!");
    }

    public function search(Request $request){

        $query = $request->input('query');
        $results = Product::where('name','LIKE',"%{$query}%")->get()->take(8);
        return response()->json($results);
    }
}