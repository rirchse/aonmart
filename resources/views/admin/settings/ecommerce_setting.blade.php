@extends('layouts.default')
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">E-commerce Setting</h3>
            </div>
            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{session('success')}}
                </div>
            @elseif(session()->has('error'))
                <div class="alert alert-danger">
                    {{session('error')}}
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
            <form class="form" enctype="multipart/form-data" method="POST" action="{{route('ecommerce.update')}}">
                @csrf
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <label for="currency"><b>{{__('Currency')}}</b></label>
                            <select class="form-control select2" name="currency" value="{{ old('currency') ?? ($ecommerce_setting ?  $ecommerce_setting->currency : null) }}">
                                <option value="">-- Select Currency --</option>
                                <option value="BDT" {{ (old('currency') ?? ($ecommerce_setting ?  $ecommerce_setting->currency : null)) == 'BDT' ? 'selected' : null}}>BDT</option>
                                {{-- <option value="USD" {{ (old('currency') ?? ($ecommerce_setting ?  $ecommerce_setting->currency : null)) == 'USD' ? 'selected' : null}}>USD</option> --}}
                            </select>
                            @error('currency')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the e-commerce currency.</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-4">
                            <label for="shipping_cost_dhaka"><b>{{__('Shipping Cost In Dhaka')}}</b></label>
                            <input name="shipping_cost_dhaka" id="shipping_cost_dhaka" min="0" value="{{old('shipping_cost_dhaka') ?? ($ecommerce_setting ? $ecommerce_setting->shipping_cost_dhaka : null)}}"
                                   placeholder="Ex: 50" type="number" class="form-control  @error('shipping_cost_dhaka') is-invalid @enderror">
                            @error('shipping_cost_dhaka')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the cost in dhaka.</span>
                        </div>
                        <div class="col-lg-4">
                            <label for="shipping_cost_outside"><b>{{__('Shipping Cost Outside')}}</b></label>
                            <input name="shipping_cost_outside" id="shipping_cost_outside" min="0"
                                   value="{{old('shipping_cost_outside') ?? ($ecommerce_setting ?  $ecommerce_setting->shipping_cost_outside : null)}}" placeholder="Ex: 100" type="number"
                                   class="form-control  @error('shipping_cost_outside') is-invalid @enderror">
                            @error('shipping_cost_outside')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the cost outside dhaka.</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <label for="tax"><b>{{__('Tax')}}</b></label>
                            <div class="input-group">
                                <input name="tax" id="tax" min="0" value="{{old('tax') ?? ($ecommerce_setting ?  $ecommerce_setting->tax : null)}}" placeholder="Ex: 5" type="number"
                                       class="form-control  @error('tax') is-invalid @enderror">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            @error('tax')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the cost in dhaka.</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-4">
                            <label for="delivery_time_dhaka"><b>{{__('Delivery Time in Dhaka')}}</b></label>
                            <input name="delivery_time_dhaka" id="delivery_time_dhaka" value="{{old('delivery_time_dhaka') ?? ($ecommerce_setting ?  $ecommerce_setting->delivery_time_dhaka : null)}}"
                                   placeholder="Ex: 5" type="number" class="form-control  @error('delivery_time_dhaka') is-invalid @enderror">
                            @error('delivery_time_dhaka')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the delivery time in dhaka.</span>
                        </div>
                        <div class="col-lg-4">
                            <label for="delivery_time_outside"><b>{{__('Delivery Time Outside')}}</b></label>
                            <input name="delivery_time_outside" id="delivery_time_outside" value="{{old('delivery_time_outside') ?? ($ecommerce_setting ?  $ecommerce_setting->delivery_time_outside : null)}}"
                                   placeholder="Ex: 5" type="number" class="form-control @error('delivery_time_outside') is-invalid @enderror">
                            @error('delivery_time_outside')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the delivery time outside.</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <label for="note"><b>{{__('Delivery Note')}}</b></label>
                            <textarea name="note" id="note" rows="3" class="form-control  @error('note') is-invalid @enderror">{{old('note') ?? ($ecommerce_setting ?  $ecommerce_setting->note : null)}}</textarea>
                            @error('note')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            <span class="form-text text-muted">Please enter the cost in dhaka.</span>
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
