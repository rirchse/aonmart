@extends('layouts.default')
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Edit Product</h3>
            </div>
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @elseif(session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="card-body">
            <form class="kt-form kt-form--label-right" enctype="multipart/form-data" method="POST"
                action="{{ route('product.update', $product->id) }}">
                @csrf
                @method('PUT')
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-lg-7">

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="barcode"><b>{{ __('Barcode') }}</b></label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input name="barcode" id="barcode" value="{{ old('barcode', $product->barcode) }}" placeholder="Ex: 012345678901" type="text" class="form-control @error('barcode') is-invalid @enderror" readonly>
                                        <div class="input-group-append">
                                            <button type="button" class="input-group-text" onclick="document.getElementById('barcode').removeAttribute('readonly')">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('barcode')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="name"><b>{{ __('Product Name') }}</b></label>
                                <div class="col-md-8">
                                    <input name="name" id="name" value="{{ old('name') ?? $product->name }}"
                                        placeholder="Ex: " type="text"
                                        class="form-control  @error('name') is-invalid @enderror">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label"
                                    for="short_description"><b>{{ __('Short description') }}</b></label>
                                <div class="col-md-8">
                                    <textarea class="form-control @error('short_description') is-invalid @enderror"
                                        id="short_description" name="short_description"
                                        placeholder="Enter a product Short Description..."
                                        rows="3">{{ old('short_description') ?? $product->short_description }}</textarea>
                                    @error('short_description')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label"
                                    for="full_description"><b>{{ __('Full Description') }}</b></label>
                                <div class="col-md-8">
                                    <textarea class="form-control @error('full_description') is-invalid @enderror"
                                        id="full_description" name="full_description"
                                        placeholder="Enter a product Full Description..."
                                        rows="5">{{ old('full_description') ?? $product->full_description }}</textarea>
                                    @error('full_description')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label"
                                    for="category_id"><b>{{ __('Product Category') }}</b></label>
                                <div class="col-md-8">
                                    <select class="form-control select2-withTag" multiple name="category_id[]"
                                        id="category_id">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ collect(old('category_id', $oldCategories))->contains($category->id) ? 'selected' : null }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span style="display: block" class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label"
                                    for="subcategory_id"><b>{{ __('Sub Category') }}</b></label>
                                <div class="col-md-8">
                                    <select class="form-control select2" multiple name="subcategory_id[]"
                                        id="subcategory_id">
                                        @foreach ($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}"
                                                {{ collect(old('subcategory_id', $oldSubcategories))->contains($subcategory->id) ? 'selected' : null }}>
                                                {{ $subcategory->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('subcategory_id')
                                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label"
                                    for="sub_subcategory_id"><b>{{ __('Sub Subcategory') }}</b></label>
                                <div class="col-md-8">
                                    <select class="form-control select2" multiple name="sub_subcategory_id[]"
                                        id="sub_subcategory_id">
                                        @foreach ($subSubcategories as $subSubcategory)
                                            <option value="{{ $subSubcategory->id }}"
                                                {{ collect(old('sub_subcategory_id', $oldSubSubcategories))->contains($subSubcategory->id) ? 'selected' : null }}>
                                                {{ $subSubcategory->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('sub_subcategory_id')
                                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label"
                                    for="quantity"><b>{{ __('Sale Quantity') }}</b></label>
                                <div class="col-md-8">
                                    <input name="quantity" id="quantity"
                                        value="{{ old('quantity', $product->quantity) }}" placeholder="Ex: 100"
                                        type="number" class="form-control  @error('quantity') is-invalid @enderror">
                                    @error('quantity')
                                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="unit_id"><b>{{ __('Sale Unit') }}</b></label>
                                <div class="col-md-8">
                                    <select class="form-control select2-withTag" name="unit_id" id='unit_id'>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ collect(old('unit_id', $oldUnits))->contains($unit->id) ? 'selected' : null }}>
                                                {{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')
                                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="tag_id"><b>{{ __('Tags') }}</b></label>
                                <div class="col-md-8">
                                    <select class="form-control select2-withTag" id="tag_id" multiple name="tag_id[]">
                                        @foreach ($tags as $tag)
                                            <option value="{{ $tag->id }}"
                                                {{ collect(old('tag_id', $oldTags))->contains($tag->id) ? 'selected' : null }}>
                                                {{ $tag->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('tag_id')
                                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label"
                                    for="regular_price"><b>{{ __('Selling Price') }}</b></label>
                                <div class="col-md-8">
                                    <input name="regular_price" id="regular_price"
                                        value="{{ old('regular_price') ?? $product->regular_price }}"
                                        placeholder="Ex: 1 " type="number"
                                        class="form-control  @error('regular_price') is-invalid @enderror">
                                    @error('regular_price')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label"
                                    for="sell_price"><b>{{ __('Discount Price') }}</b></label>
                                <div class="col-md-8">
                                    <input name="sell_price" id="sell_price"
                                        value="{{ old('sell_price') ?? $product->sell_price }}" placeholder="Ex: 100 "
                                        type="number" class="form-control  @error('sell_price') is-invalid @enderror">
                                    @error('sell_price')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label"
                                    for="discount"><b>{{ __('Discount %') }}</b></label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input name="discount" id="discount" onkeyup="showSellPrice()"
                                            onchange="showSellPrice()"
                                            value="{{ old('discount') ?? $product->discount }}" min="0"
                                            placeholder="Ex: 5" type="number"
                                            class="form-control  @error('discount') is-invalid @enderror">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    @error('discount')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="image"
                                    class=""><b>{{ __('Product Image') }}</b></label>
                                <div class="col-md-8">

                                    <div class="image-input image-input-empty image-input-outline">
                                        <div class="image-input-wrapper" id="image"
                                        style="background-image: url({{ asset('storage/' . $product->image) }});background-size:cover;"></div>

                                        <label
                                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                            data-action="change" data-toggle="tooltip" title=""
                                            data-original-title="Change avatar">
                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                            <input type="file" class="@error('image') is-invalid @enderror"
                                                onchange="readURL(this, '#image')" name="image"
                                                accept=".png, .jpg, .jpeg" />
                                            <input type="hidden" value="{{ $product->image }}" name="old_image" />
                                        </label>

                                        <span
                                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                            data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                        </span>

                                        <span
                                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                            data-action="remove" data-toggle="tooltip" title="Remove avatar">
                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                        </span>
                                    </div>



                                    @error('image')
                                        <span style="display: block" class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center mb-2">
                            <p><b>{{ __('Product Gallery') }}</b></p>
                        </div>
                        <div class="offset-md-1 col-6 col-md-4 col-lg-2">
                            <div class="form-group row">

                                <div class="image-input image-input-outline">
                                    <div class="image-input-wrapper" id="gallery1"
                                        style="background-image: url({{ isset($old_gallery[0]) ? asset('storage/' . $old_gallery[0]) : null }});background-size:cover;">
                                    </div>

                                    <label
                                        class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="change" data-toggle="tooltip" title=""
                                        data-original-title="Change avatar">
                                        <i class="fa fa-pen icon-sm text-muted"></i>
                                        <input type="file" class="@error('gallery') is-invalid @enderror"
                                            onchange="readURL(this, '#gallery1')" name="gallery[]"
                                            accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" value="{{ $product->image }}" name="old_image" />
                                    </label>

                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                                    </span>

                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="remove" data-toggle="tooltip" title="Remove avatar"
                                        onclick="ImageClear('#gallery1')">
                                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                                    </span>
                                </div>

                                {{-- <div class="kt-avatar kt-avatar--outline" id="kt_user_avatar_1">
                                    <div id="gallery1" class="kt-avatar__holder_icon"
                                        style="background-image: url({{ isset($old_gallery[0]) ? asset('storage/' . $old_gallery[0]) : null }})">
                                    </div>
                                    <label class="col-md-4 col-form-label" class="kt-avatar__upload"
                                        data-toggle="kt-tooltip" title="" data-original-title="Change">
                                        <i class="fa fa-pen"></i>
                                        <input type="file" class="@error('gallery') is-invalid @enderror"
                                            onchange="readURL(this, '#gallery1')" name="gallery[]"
                                            accept=".png, .jpg, .jpeg">

                                    </label>
                                    <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title=""
                                        onclick="ImageClear('#gallery1')" data-original-title="Cancel">
                                        <i class="fa fa-times"></i>
                                    </span>
                                </div> --}}
                                @error('gallery')
                                    <span style="display: block" class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="form-group row">


                                <div class="image-input image-input-outline">
                                    <div class="image-input-wrapper" id="gallery2"
                                        style="background-image: url({{ isset($old_gallery[1]) ? asset('storage/' . $old_gallery[1]) : null }});background-size:cover;">
                                    </div>

                                    <label
                                        class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="change" data-toggle="tooltip" title=""
                                        data-original-title="Change avatar">
                                        <i class="fa fa-pen icon-sm text-muted"></i>
                                        <input type="file" class="@error('gallery') is-invalid @enderror"
                                            onchange="readURL(this, '#gallery2')" name="gallery[]"
                                            accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" value="{{ $product->image }}" name="old_image" />
                                    </label>

                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                                    </span>

                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="remove" data-toggle="tooltip" title="Remove avatar"
                                        onclick="ImageClear('#gallery2')">
                                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                                    </span>
                                </div>


                                @error('gallery')
                                    <span style="display: block" class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="form-group row">
                                <div class="image-input image-input-outline">
                                    <div class="image-input-wrapper" id="gallery3"
                                        style="background-image: url({{ isset($old_gallery[2]) ? asset('storage/' . $old_gallery[2]) : null }});background-size:cover;">
                                    </div>

                                    <label
                                        class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="change" data-toggle="tooltip" title=""
                                        data-original-title="Change avatar">
                                        <i class="fa fa-pen icon-sm text-muted"></i>
                                        <input type="file" class="@error('gallery') is-invalid @enderror"
                                            onchange="readURL(this, '#gallery3')" name="gallery[]"
                                            accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" value="{{ $product->image }}" name="old_image" />
                                    </label>

                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                                    </span>

                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="remove" data-toggle="tooltip" title="Remove avatar"
                                        onclick="ImageClear('#gallery3')">
                                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                                    </span>
                                </div>
                                @error('gallery')
                                    <span style="display: block" class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="form-group row">
                                <div class="image-input image-input-outline">
                                    <div class="image-input-wrapper" id="gallery4"
                                        style="background-image: url({{ isset($old_gallery[3]) ? asset('storage/' . $old_gallery[3]) : null }});background-size:cover;">
                                    </div>

                                    <label
                                        class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="change" data-toggle="tooltip" title=""
                                        data-original-title="Change avatar">
                                        <i class="fa fa-pen icon-sm text-muted"></i>
                                        <input type="file" class="@error('gallery') is-invalid @enderror"
                                            onchange="readURL(this, '#gallery4')" name="gallery[]"
                                            accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" value="{{ $product->image }}" name="old_image" />
                                    </label>

                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                                    </span>

                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="remove" data-toggle="tooltip" title="Remove avatar"
                                        onclick="ImageClear('#gallery4')">
                                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                                    </span>
                                </div>
                                @error('gallery')
                                    <span style="display: block" class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="form-group row">


                                <div class="image-input image-input-outline">
                                    <div class="image-input-wrapper" id="gallery5"
                                        style="background-image: url({{ isset($old_gallery[4]) ? asset('storage/' . $old_gallery[4]) : null }});background-size:cover;">
                                    </div>

                                    <label
                                        class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="change" data-toggle="tooltip" title=""
                                        data-original-title="Change avatar">
                                        <i class="fa fa-pen icon-sm text-muted"></i>
                                        <input type="file" class="@error('gallery') is-invalid @enderror"
                                            onchange="readURL(this, '#gallery5')" name="gallery[]"
                                            accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" value="{{ $product->image }}" name="old_image" />
                                    </label>

                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                                    </span>

                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="remove" data-toggle="tooltip" title="Remove avatar"
                                        onclick="ImageClear('#gallery5')">
                                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                                    </span>
                                </div>

                                @error('gallery')
                                    <span style="display: block" class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- <button type="reset" class="btn btn-danger">Delete</button> -->
                            </div>
                            <div class="col-lg-6 kt-align-right">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('product.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('script')
    <script src="{{ asset('assets/plugins/onscan.min.js') }}" type="text/javascript"></script>

    <script>
        let regular_price = document.getElementById('regular_price');
        let sell_price = document.getElementById('sell_price');
        let discount = document.getElementById('discount');

        //when sell input change
        function showDiscount() {
            if (sell_price.value < 0) {
                sell_price.value = '';
            }
            if (parseInt(regular_price.value) > parseInt(sell_price.value)) {
                discount.value = Math.round((regular_price.value - sell_price.value) / (regular_price.value * 0.01));
            } else {
                sell_price.value = '';
            }
        }

        //when discount input change
        function showSellPrice() {
            if (discount.value > 100) {
                discount.value = '';
            }
            sell_price.value = Math.round(regular_price.value - (regular_price.value * (discount.value / 100)));
        }

        // Add existing barcode to product
        onScan.attachTo(document, {
            suffixKeyCodes: [13], // enter-key expected at the end of a scan
            reactToPaste: false, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
            onScan: function(sCode, iQty) { // Alternative to document.addEventListener('scan')
                document.getElementById('barcode').value = sCode;
            },
            onKeyDetect: function(iKeyCode) {
                // console.log('Pressed: ' + iKeyCode);
            }
        });

        //sub category
        $('#category_id').on('change', function() {
            //reset sub subcategory value
            let subSubcategory = $("#sub_subcategory_id");
            subSubcategory.attr('disabled', true);
            subSubcategory.html('');

            let category_id = $('#category_id').select2("val");
            $.ajax({
                method: "post",
                url: "{{ route('load.subcategory') }}",
                data: {
                    category_id: category_id,
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "html",
                success: function(response) {
                    let subcategory = $("#subcategory_id");
                    subcategory.attr("disabled", false);
                    subcategory.html(response);
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });
        //sub subcategory
        $('#subcategory_id').on('change', function() {
            let subcategory_id = $('#subcategory_id').select2("val");
            $.ajax({
                method: "post",
                url: "{{ route('load.subSubcategory') }}",
                data: {
                    subcategory_id: subcategory_id,
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "html",
                success: function(response) {
                    let subSubcategory = $("#sub_subcategory_id");
                    subSubcategory.attr("disabled", false);
                    subSubcategory.html(response);
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });

        function readURL(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(id).css("background-image", "url(" + e.target.result + ")");
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function ImageClear(id) {
            $(id).css("background-image", "url()");
        }




        // var avatar4 = new KTImageInput('#');

        // avatar4.on('cancel', function(imageInput) {
        //     swal.fire({
        //         title: 'Image successfully canceled !',
        //         type: 'success',
        //         buttonsStyling: false,
        //         confirmButtonText: 'Awesome!',
        //         confirmButtonClass: 'btn btn-primary font-weight-bold'
        //     });
        // });

        // avatar4.on('change', function(imageInput) {
        //     swal.fire({
        //         title: 'Image successfully changed !',
        //         type: 'success',
        //         buttonsStyling: false,
        //         confirmButtonText: 'Awesome!',
        //         confirmButtonClass: 'btn btn-primary font-weight-bold'
        //     });
        // });

        // avatar4.on('remove', function(imageInput) {
        //     swal.fire({
        //         title: 'Image successfully removed !',
        //         type: 'error',
        //         buttonsStyling: false,
        //         confirmButtonText: 'Got it!',
        //         confirmButtonClass: 'btn btn-primary font-weight-bold'
        //     });
        // });

    </script>
@endpush
@push('script')
    <!--begin::Page Scripts(used by this page)-->

    <!--end::Page Scripts-->
@endpush
