<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShopController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;


Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class, 'product_details'])->name('shop.product.details');
Route::get('/search', [HomeController::class, 'search'])->name('home.search');



Route::middleware(['auth'])->group(function(){
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
});


Route::middleware(['auth',AuthAdmin::class])->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    Route::get('/admin/categories',[AdminController::class,'categories'])->name('admin.categories');
    Route::get('/admin/category/add',[AdminController::class,'add_category'])->name('admin.category.add');
    Route::post('/admin/category/store',[AdminController::class,'category_store'])->name('admin.category.store');
    Route::get('/admin/category/edit/{id}',[AdminController::class,'category_edit'])->name('admin.category.edit');
    Route::put('/admin/category/update',[AdminController::class,'category_update'])->name('admin.category.update');
    Route::delete('/admin/category/{id}/delete',[AdminController::class,'category_delete'])->name('admin.category.delete');

    Route::get('/admin/products',[AdminController::class,'products'])->name('admin.products');
    Route::get('/admin/product/add',[AdminController::class,'add_product'])->name('admin.product.add');
    Route::post('/admin/product/store',[AdminController::class,'product_store'])->name('admin.product.store');
    //ckeditor_add
    Route::post('/admin/product/upload-editor-image', [AdminController::class, 'upload_editor_image'])->name('admin.product.upload-image');
    
    Route::get('/admin/product/{id}/edit',[AdminController::class,'product_edit'])->name('admin.product.edit');
    Route::put('/admin/product/update',[AdminController::class,'product_update'])->name('admin.product.update');
    Route::delete('/admin/product/{id}/delete',[AdminController::class,'product_delete'])->name('admin.product.delete');

    Route::get('/admin/slides',[AdminController::class,'slides'])->name('admin.slides');
    Route::get('/admin/slide/add',[AdminController::class,'slide_add'])->name('admin.slide.add');
    Route::post('/admin/slide/store',[AdminController::class,'slide_store'])->name('admin.slide.store');
    Route::get('/admin/slide/edit/{id}',[AdminController::class,'slide_edit'])->name('admin.slide.edit');
    Route::put('/admin/slide/update',[AdminController::class,'slide_update'])->name('admin.slide.update');
    Route::delete('/admin/slide/{id}/delete',[AdminController::class,'slide_delete'])->name('admin.slide.delete');

    Route::get('/admin/search', [AdminController::class, 'search'])->name('admin.search');
});