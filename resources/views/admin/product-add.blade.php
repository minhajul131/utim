@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Add Product</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{route('admin.products')}}">
                        <div class="text-tiny">Products</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Add product</div>
                </li>
            </ul>
        </div>
        <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{route('admin.product.store')}}">
            @csrf
            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Product name <span class="tf-color-1">*</span>
                    </div>
                    <input class="mb-10" type="text" placeholder="Enter product name" name="name" tabindex="0" value="{{old('name')}}" aria-required="true" required="">
                    <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                </fieldset>
                @error('name') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                
                <fieldset class="name">
                    <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter product slug" name="slug" tabindex="0" value="{{old('slug')}}" aria-required="true" required="">
                    <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                </fieldset>
                @error('slug') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                
                <div class="gap22 cols">
                    <fieldset class="category">
                        <div class="body-title mb-10">Category <span class="tf-color-1">*</span>
                        </div>
                        <div class="select">
                            <select class="" name="category_id" required="">
                                <option>Choose category</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                            </select>
                        </div>
                    </fieldset>
                    @error('category_id') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                </div>

                <fieldset class="shortdescription">
                    <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10 ht-150" name="short_description" placeholder="Short Description" tabindex="0" aria-required="true" required="">{{old('short_description')}}</textarea>
                    <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                </fieldset>
                @error('short_description') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                
                <fieldset class="description">
                    <div class="body-title mb-10">Description <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10" id="description" name="description" placeholder="Description" tabindex="0">{{old('description')}}</textarea>
                    <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                </fieldset>
                @error('description') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
            </div>
            <div class="wg-box">
                <fieldset>
                    <div class="body-title">Upload images <span class="tf-color-1">*</span>
                    </div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview" style="display:none">
                            <img src="../../../localhost_8000/images/upload/upload-1.png" class="effect8" alt="">
                        </div>
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('image') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                <fieldset>
                    <div class="body-title mb-10">Upload Gallery Images</div>
                    <div class="upload-image mb-16">
                        <div id="galUpload" class="item up-load">
                            <label class="uploadfile" for="gFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="text-tiny">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                <input type="file" id="gFile" name="images[]" accept="image/*" multiple="">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('images[]') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Regular Price <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter regular price" name="regular_price" tabindex="0" value="{{old('regular_price')}}" aria-required="true" required="">
                    </fieldset>
                    @error('regular_price') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                    <fieldset class="name">
                        <div class="body-title mb-10">Sale Price <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter sale price" name="sale_price" tabindex="0" value="{{old('sale_price')}}" aria-required="true" required="">
                    </fieldset>
                    @error('sale_price') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                </div>


                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">SKU <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU" tabindex="0" value="{{old('SKU')}}" aria-required="true" required="">
                    </fieldset>
                    @error('SKU') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                    <fieldset class="name">
                        <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter quantity" name="quantity" tabindex="0" value="{{old('quantity')}}" aria-required="true" required="">
                    </fieldset>
                    @error('quantity') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Stock</div>
                        <div class="select mb-10">
                            <select class="" name="stock_status" required="">
                                <option value="instock">In Stock</option>
                                <option value="outofstock">Out of Stock</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('stock_status') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                    <fieldset class="name">
                        <div class="body-title mb-10">Featured</div>
                        <div class="select mb-10">
                            <select class="" name="featured" required="">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('featured') <span class="alert alert-danger text-center"> {{$message}} </span>@enderror
                </div>
                <div class="cols gap10">
                    <button class="tf-button w-full" type="submit">Add product</button>
                </div>
            </div>
        </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // কাস্টম আপলোড অ্যাডাপ্টার ফাংশন (কোনো প্লাগইন ছাড়াই ছবি আপলোড করবে)
        class MyUploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }

            // আপলোড প্রসেস শুরু করার মেথড
            upload() {
                return this.loader.file
                    .then(file => new Promise((resolve, reject) => {
                        this._initRequest();
                        this._initListeners(resolve, reject, file);
                        this._sendRequest(file);
                    }));
            }

            // আপলোড ক্যানসেল করার মেথড
            abort() {
                if (this.xhr) {
                    this.xhr.abort();
                }
            }

            // XMLHttpRequest ইনিশিয়ালাইজ করা
            _initRequest() {
                const xhr = this.xhr = new XMLHttpRequest();
                // এখানে আপনার Laravel-এর ইমেজ আপলোড রাউট ইউআরএল বসানো হয়েছে
                xhr.open('POST', "{{ route('admin.product.upload-image') }}", true);
                // CSRF টোকেন হেডার যুক্ত করা হলো নিরাপত্তার জন্য
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                xhr.responseType = 'json';
            }

            // আপলোড প্রগ্রেস এবং রেসপন্স লিসেনার
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

                    // আপলোড সফল হলে ছবিটির ইউআরএল এডিটরকে রিটার্ন করবে
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

            // ডাটা পাঠানো শুরু করা
            _sendRequest(file) {
                const data = new FormData();
                // CKEditor ডিফল্টভাবে 'upload' নামে ফাইল পাঠায়, যা কন্ট্রোলারে রিসিভ হবে
                data.append('upload', file);
                this.xhr.send(data);
            }
        }

        // কাস্টম অ্যাডাপ্টারটিকে CKEditor-এর সাথে প্লাগইন হিসেবে যুক্ত করার ফাংশন
        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MyUploadAdapter(loader);
            };
        }

        $(function(){
            // CKEditor 5 ইনিশিয়ালাইজেশন
            if (typeof ClassicEditor !== 'undefined') {
                ClassicEditor
                    .create(document.querySelector('#description'), {
                        // এখানে আমাদের তৈরি করা কাস্টম আপলোড প্লাগইনটি কল করা হলো
                        extraPlugins: [MyCustomUploadAdapterPlugin],
                    })
                    .then(editor => {
                        editor.editing.view.change(writer => {
                            writer.setStyle('min-height', '250px', editor.editing.view.document.getRoot());
                        });
                    })
                    .catch(error => {
                        console.error("CKEditor Error: ", error);
                    });
            } else {
                console.error("ClassicEditor is not defined!");
            }

            // সিঙ্গল ইমেজ প্রিভিউ
            $("#myFile").on("change", function(e){
                const [file] = this.files;
                if(file) {
                    $("#imgpreview img").attr('src', URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });

            // গ্যালারি ইমেজ মাল্টিপল প্রিভিউ
            $("#gFile").on("change", function(e){
                const gphotos = this.files;
                $.each(gphotos, function(key, val){
                    $("#galUpload").prepend(`<div class="item gitems"><img src="${URL.createObjectURL(val)}" /></div>`);
                });
            });

            // নাম থেকে অটো স্লাগ
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