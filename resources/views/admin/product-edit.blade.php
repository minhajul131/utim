@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Edit Product</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <a href="{{route('admin.products')}}">
                        <div class="text-tiny">Products</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <div class="text-tiny">Edit product</div>
                </li>
            </ul>
        </div>
        
        <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{route('admin.product.update')}}">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{ $product->id }}"/>
            
            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Product name <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter product name" name="name" tabindex="0" value="{{$product->name}}" aria-required="true" required="">
                </fieldset>
                @error('name') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                
                <fieldset class="name">
                    <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter product slug" name="slug" tabindex="0" value="{{$product->slug}}" aria-required="true" required="">
                </fieldset>
                @error('slug') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                
                <div class="gap22 cols">
                    <fieldset class="category">
                        <div class="body-title mb-10">Category <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="category_id">
                                <option>Choose category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}" {{$product->category_id == $category->id ? "selected":""}}>{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                    @error('category_id') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                </div>

                <fieldset class="shortdescription">
                    <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10 ht-150" name="short_description" placeholder="Short Description" tabindex="0" aria-required="true" required="">{{$product->short_description}}</textarea>
                </fieldset>
                @error('short_description') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                
                <fieldset class="description">
                    <div class="body-title mb-10">Description <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10" name="description" id="description" placeholder="Description" tabindex="0" aria-required="true">{{$product->description}}</textarea>
                </fieldset>
                @error('description') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
            </div>
            
            <div class="wg-box">
                <fieldset>
                    <div class="body-title">Upload images <span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview" style="{{ !$product->image ? 'display:none;' : '' }}">
                            <img src="{{ $product->image ? asset('uploads/products/'.$product->image) : '#' }}" class="effect8" alt="">
                        </div>
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('image') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                
                <fieldset>
                    <div class="body-title mb-10">Upload Gallery Images</div>
                    <div class="upload-image mb-16" id="gallery-container">
                        @if($product->images)
                            @foreach(explode(',',$product->images) as $img)
                                @if(!empty(trim($img)))
                                    <div class="item old-gitems">
                                        <img src="{{asset('uploads/products')}}/{{trim($img)}}" alt="">
                                    </div>        
                                @endif
                            @endforeach
                        @endif
                        <div id="galUpload" class="item up-load">
                            <label class="uploadfile" for="gFile">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <span class="text-tiny">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                <input type="file" id="gFile" name="images[]" accept="image/*" multiple="">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('images') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                
                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Regular Price <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter regular price" name="regular_price" tabindex="0" value="{{$product->regular_price}}" aria-required="true" required="">
                    </fieldset>
                    @error('regular_price') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                    
                    <fieldset class="name">
                        <div class="body-title mb-10">Sale Price <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter sale price" name="sale_price" tabindex="0" value="{{$product->sale_price}}" aria-required="true" required="">
                    </fieldset>
                    @error('sale_price') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">SKU <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU" tabindex="0" value="{{$product->SKU}}" aria-required="true" required="">
                    </fieldset>
                    @error('SKU') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                    
                    <fieldset class="name">
                        <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter quantity" name="quantity" tabindex="0" value="{{$product->quantity}}" aria-required="true" required="">
                    </fieldset>
                    @error('quantity') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Stock</div>
                        <div class="select mb-10">
                            <select name="stock_status">
                                <option value="instock" {{$product->stock_status == "instock" ? "selected":""}}>In Stock</option>
                                <option value="outofstock" {{$product->stock_status == "outofstock" ? "selected":""}}>Out of Stock</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('stock_status') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                    
                    <fieldset class="name">
                        <div class="body-title mb-10">Featured</div>
                        <div class="select mb-10">
                            <select name="featured">
                                <option value="0" {{$product->featured == "0" ? "selected":""}}>No</option>
                                <option value="1" {{$product->featured == "1" ? "selected":""}}>Yes</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('featured') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                </div>
                
                <div class="cols gap10">
                    <button class="tf-button w-full" type="submit">Update product</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    /* ডেসক্রিপশন বক্সের সাইজ সুন্দর করার জন্য */
    .ck-editor__editable_inline {
        min-height: 250px;
    }
</style>
@endpush

@push('scripts')
    
    
    <script>
        // CKEditor কাস্টম আপলোড অ্যাডাপ্টার (যা আপনার কন্ট্রোলারের upload_editor_image মেথডে রিকোয়েস্ট পাঠাবে)
        class MyUploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }
            upload() {
                return this.loader.file
                    .then(file => new Promise((resolve, reject) => {
                        this._initRequest();
                        this._initListeners(resolve, reject, file);
                        this._sendRequest(file);
                    }));
            }
            abort() {
                if (this.xhr) {
                    this.xhr.abort();
                }
            }
            _initRequest() {
                const xhr = this.xhr = new XMLHttpRequest();
                // এডিট পেজ থেকেও ইমেজটি আপলোড করার জন্য সেম রাউট ব্যবহার করা হচ্ছে
                xhr.open('POST', "{{ route('admin.product.upload-image') }}", true);
                xhr.setRequestHeader('x-csrf-token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                xhr.responseType = 'json';
            }
            _initListeners(resolve, reject, file) {
                const xhr = this.xhr;
                const loader = this.loader;
                const genericErrorText = `Couldn't upload file: ${file.name}.`;

                xhr.addEventListener('error', () => reject(genericErrorText));
                xhr.addEventListener('abort', () => reject());
                xhr.addEventListener('load', () => {
                    const response = xhr.response;
                    if (!response || response.error) {
                        return reject(response && response.error ? response.error.message : genericErrorText);
                    }
                    // আপলোড সফল হলে এডিটরের ভেতরে ইমেজ দেখাবে
                    resolve({
                        default: response.url
                    });
                });
                if (xhr.upload) {
                    xhr.upload.addEventListener('progress', evt => {
                        if (evt.lengthComputable) {
                            loader.uploadTotal = evt.total;
                            loader.uploaded = evt.loaded;
                        }
                    });
                }
            }
            _sendRequest(file) {
                const data = new FormData();
                data.append('upload', file);
                this.xhr.send(data);
            }
        }

        // এডিটরে প্লাগইন রেজিস্টার করার ফাংশন
        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MyUploadAdapter(loader);
            };
        }

        $(function(){
            // এডিট পেজে CKEditor 5 চালু করা
            if (document.querySelector('#description')) {
                ClassicEditor
                    .create(document.querySelector('#description'), {
                        extraPlugins: [MyCustomUploadAdapterPlugin],
                    })
                    .catch(error => {
                        console.error("Editor Error: ", error);
                    });
            }

            // মেইন ইমেজ প্রিভিউ চেঞ্জ করার কোড (আগে যা ছিল)
            $("#myFile").on("change", function(e){
                const [file] = this.files;
                if(file) {
                    $("#imgpreview img").attr('src', URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });

            // গ্যালারি ইমেজ প্রিভিউ (আগে যা ছিল)
            $("#gFile").on("change", function(e){
                const gphotos = this.files;
                $.each(gphotos, function(key, val){
                    $("#galUpload").prepend(`<div class="item gitems"><img src="${URL.createObjectURL(val)}" /></div>`);
                });
            });

            // নাম থেকে স্লাগ জেনারেট (আগে যা ছিল)
            $("input[name='name']").on("change", function(){
                $("input[name='slug']").val(StringToSlug($(this).val()));
            });
        });

        function StringToSlug(Text) {
            return Text.toLowerCase()
                .replace(/[^\w ]+/g, "")
                .replace(/ +/g, "-");
        }
    </script>
@endpush