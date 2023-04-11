@extends('layouts.default')
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Create Supplier</h3>
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
                action="{{ route('supplier.store') }}">
                @csrf

                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <label for="name" class=""><b>{{ __('Supplier Name') }} <span class="text-danger">*</span></b></label>
                            <input name="name" id="name" value="{{ old('name') }}" placeholder="Ex: Smith Jones" type="text" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-lg-2"></div>
                      <div class="col-md-8">
                        <label for="company"><b>{{__('Company')}}</b></label>
                        <select class="form-control select2-withTag" name="company" id='company'>
                          <option value=""></option>
                          @foreach ($companies as $company)
                            <option value="{{ $company->name }}" {{ collect(old('company'))->contains($company->name) ? 'selected' : null }}>{{ $company->name }}</option>
                          @endforeach
                        </select>
                        @error('company')
                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                      </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <label for="mobile" class=""><b>{{ __('Mobile Number') }} <span class="text-danger">*</span></b></label>
                            <input name="mobile" id="mobile" value="{{ old('mobile') }}" placeholder="Ex: 01xxxxxxxxxx" type="tel" class="form-control  @error('mobile') is-invalid @enderror" required>
                            @error('mobile')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-4">
                            <label for="password" class=""><b>{{ __('Password') }} <span
                                        class="text-danger">*</span></b></label>
                            <input name="password" id="password" placeholder="Ex: " type="password"
                                class="form-control  @error('password') is-invalid @enderror" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-lg-4">
                            <label for="password_confirmation" class=""><b>{{ __('Confirm Password') }} <span
                                        class="text-danger">*</span></b></label>
                            <input name="password_confirmation" id="password_confirmation" placeholder="Ex: "
                                type="password" class="form-control  @error('password_confirmation') is-invalid @enderror" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <label for="email" class=""><b>{{ __('Supplier Email') }}</b></label>
                            <input name="email" id="email" value="{{ old('email') }}"
                                   placeholder="Ex: example@example.com" type="email"
                                   class="form-control  @error('email') is-invalid @enderror">
                            @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="offset-lg-2 col-lg-8">
                            <label for="product_id"><b>{{ __('Product From Supplier') }} <span class="text-danger">*</span></b></label>
                            <select class="form-control select2" multiple id="product_id" name="product_id[]">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <span class="text-danger"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <label for="about" class=""><b>{{ __('Supplier Information') }}</b></label>
                            <textarea class="form-control @error('about') is-invalid @enderror" name="about"
                                placeholder="Enter Supplier Information..." minlength="100" maxlength="1000"
                                rows="5">{{ old('about') }}</textarea>
                            @error('about')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <label for="status" class=""><b>{{ __('Status') }} <span
                                        class="text-danger">*</span></b></label>
                            <select name="status" id="status" value="{{ old('status') }}"
                                class="custom-select  @error('status') is-invalid @enderror">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <div>
                                <label for="image" class=""><b>{{ __('Supplier Image') }}</b></label>
                            </div>
                            <div class="image-input image-input-outline">
                                <div class="image-input-wrapper" id="image" style="background-image: url()">
                                </div>

                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" d>
                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <input type="file" class="@error('image') is-invalid @enderror" onchange="readURL(this)" name="image" accept=".png, .jpg, .jpeg" />
                                </label>

                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>

                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar" onclick="ImageClear('#image')">
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

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <div>
                                <label for="cover_image" class=""><b>{{ __('Cover Image') }}</b></label>
                            </div>
                            <div class="image-input image-input-outline">
                                <div class="image-input-wrapper" id="cover_image" style="background-image: url()">
                                </div>

                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" d>
                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <input type="file" class="@error('cover_image') is-invalid @enderror" onchange="readURLCover(this)" name="cover_image" accept=".png, .jpg, .jpeg" />
                                </label>

                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>

                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar" onclick="ImageClear('#cover_image')">
                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>
                            </div>
                            @error('cover_image')
                                <span style="display: block" class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('product.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#image').css("background-image", "url(" + e.target.result + ")");

                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURLCover(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#cover_image').css("background-image", "url(" + e.target.result + ")");

                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function ImageClear(id) {
            $(id).css("background-image", "url()");

        }

    </script>
@endsection
