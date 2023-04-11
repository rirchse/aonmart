@extends('layouts.default')
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">Company Settings
            </div>
            @if(session()->has('success'))
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
            <form class="form" enctype="multipart/form-data" method="POST" action="{{route('company.update')}}">
                @csrf
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <label for="name"><b>{{__('Name')}} <span class="text-danger">*</span></b></label>
                            <input name="name" id="name" value="{{old('name') ?? $company_setting->name}}" placeholder="Ex: " type="text" class="form-control  @error('name') is-invalid @enderror">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the company_setting name.</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-4">
                            <label for="mobile1"><b>{{__('Mobile')}}</b></label>
                            <input name="mobile1" id="mobile1" value="{{old('mobile1') ?? $company_setting->mobile1}}" placeholder="Ex: 01xxxxxxxxx" type="text"
                                   class="form-control  @error('mobile1') is-invalid @enderror">
                            @error('mobile1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the mobile 1.</span>
                        </div>
                        <div class="col-lg-4">
                            <label for="mobile2"><b>{{__('Mobile')}}</b></label>
                            <input name="mobile2" id="mobile2" value="{{old('mobile2') ?? $company_setting->mobile2}}" placeholder="Ex: 01xxxxxxxxx" type="text"
                                   class="form-control  @error('mobile2') is-invalid @enderror">
                            @error('mobile2')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the mobile 2.</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <label for="email"><b>{{__('Email')}}</b></label>
                            <input name="email" id="email" value="{{old('email') ?? $company_setting->email}}" placeholder="Ex: example@example.com" type="email"
                                   class="form-control  @error('email') is-invalid @enderror">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the email.</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <label for="about"><b>{{__('About')}}</b></label>
                            <textarea name="about" id="about" rows="4" placeholder="Ex: " type="about" class="form-control  @error('about') is-invalid @enderror">{{old('about') ?? $company_setting->about}}</textarea>
                            @error('about')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the about.</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <label for="about_footer"><b>{{__('About Footer')}}</b></label>
                            <textarea name="about_footer" id="about_footer" rows="4" placeholder="Ex: " type="about_footer"
                                      class="form-control  @error('about_footer') is-invalid @enderror">{{old('about_footer') ?? $company_setting->about_footer}}</textarea>
                            @error('about_footer')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the about footer.</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-4">
                            <label for="facebook"><b>{{__('Facebook Link')}}</b></label>
                            <input name="facebook" id="facebook" min="0" value="{{old('facebook') ?? $company_setting->facebook}}" placeholder="Ex: " type="facebook"
                                   class="form-control  @error('facebook') is-invalid @enderror">
                            @error('facebook')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the facebook link.</span>
                        </div>
                        <div class="col-lg-4">
                            <label for="twitter"><b>{{__('Twitter Link')}}</b></label>
                            <input name="twitter" id="twitter" min="0" value="{{old('twitter') ?? $company_setting->twitter}}" placeholder="Ex: " type="twitter"
                                   class="form-control  @error('twitter') is-invalid @enderror">
                            @error('twitter')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the twitter link.</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-4">
                            <label for="instagram"><b>{{__('Instagram Link')}}</b></label>
                            <input name="instagram" id="instagram" min="0" value="{{old('instagram') ?? $company_setting->instagram}}" placeholder="Ex: " type="instagram"
                                   class="form-control  @error('instagram') is-invalid @enderror">
                            @error('instagram')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the instagram link.</span>
                        </div>
                        <div class="col-lg-4">
                            <label for="whatsapp"><b>{{__('WhatsApp')}}</b></label>
                            <input name="whatsapp" id="whatsapp" min="0" value="{{old('whatsapp') ?? $company_setting->whatsapp}}" placeholder="Ex: " type="whatsapp"
                                   class="form-control  @error('whatsapp') is-invalid @enderror">
                            @error('whatsapp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the whatsapp.</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <label for="location"><b>{{__('Location')}}</b></label>
                            <input name="location" id="location" min="0" value="{{old('location') ?? $company_setting->location}}" placeholder="Ex: " type="location"
                                   class="form-control  @error('location') is-invalid @enderror">
                            @error('location')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the location.</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-4">
                            <div>
                                <label for="logo" class=""><b>{{__('Logo')}}</b></label>
                            </div>

                            <div class="image-input image-input-outline">
                                <div class="image-input-wrapper" id="logo" style="background-image: url({{asset('storage/'.$company_setting->logo)}})">
                                </div>

                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" d>
                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <input type="file" class="@error('logo') is-invalid @enderror" onchange="readURL(this)" name="logo" accept=".png, .jpg, .jpeg"/>
                                </label>

                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>

                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar" onclick="ImageClear('#logo')">
                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>
                            </div>

                            @error('logo')
                            <span style="display: block" class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Allowed file types: png, jpg, jpeg. <br> Standard Resulation 940px X 720px (H x W). <br> Maximum size 512kb.</span>
                        </div>

                        <div class="col-lg-4">
                            <div>
                                <label for="footer_logo" class=""><b>{{__('Footer Logo')}}</b></label>
                            </div>

                            <div class="image-input image-input-outline">
                                <div class="image-input-wrapper" id="footer-logo" style="background-image: url({{asset('storage/'.$company_setting->footer_logo)}})">
                                </div>

                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" d>
                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <input type="file" value="{{old('footer_logo')}}" class="@error('footer-logo') is-invalid @enderror" onchange="readFooterLogoURL(this)" name="footer-logo" accept=".png, .jpg, .jpeg"/>
                                </label>

                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>

                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar"
                                      onclick="ImageClear('#footer-logo')">
                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>
                            </div>

                            @error('footer_logo')
                            <span style="display: block" class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Allowed file types: png, jpg, jpeg. <br> Standard Resulation 940px X 720px (H x W). <br> Maximum size 512kb.</span>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-lg-6">
                            </div>
                            <div class="col-lg-6 kt-align-right">
                                <button type="submit" class="btn btn-primary">Update</button>
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
                reader.onload = function (e) {
                    $('#logo').css("background-image", "url(" + e.target.result + ")");
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function readFooterLogoURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#footer-logo').css("background-image", "url(" + e.target.result + ")");
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function ImageClear(id) {
            $(id).css("background-image", "url()");
        }
    </script>
@endsection
