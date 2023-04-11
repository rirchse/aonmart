@extends('layouts.default')

@section('content')
  <div class="main-body">
    <div class="row">
      <div class="col-lg-4">
        <div class="card card-custom card-stretch gutter-b">
          <div class="card-header border-0 bg-primary py-5">
            <h3 class="card-title font-weight-bolder text-white">Dashboard</h3>
          </div>
          <div class="card-body p-0 position-relative overflow-hidden">
            <div id="kt_mixed_widget_1_chart" class="card-rounded-bottom bg-primary" style="height: 120px; min-height: 120px;">
            </div>
            <div class="card-spacer mt-n25">
              <div class="row m-0">
                <div class="col bg-light-warning px-6 py-8 rounded-xl mr-7 mb-7">
                  <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"></rect>
                        <rect fill="#000000" opacity="0.3" x="13" y="4" width="3" height="16" rx="1.5"></rect>
                        <rect fill="#000000" x="8" y="9" width="3" height="11" rx="1.5"></rect>
                        <rect fill="#000000" x="18" y="11" width="3" height="9" rx="1.5"></rect>
                        <rect fill="#000000" x="3" y="13" width="3" height="7" rx="1.5"></rect>
                      </g>
                    </svg>
                  </span>
                  <a href="{{ route('report.sale') }}" class="text-warning font-weight-bold font-size-h6">Sale Reports</a>
                </div>
                <div class="col bg-light-primary px-6 py-8 rounded-xl mb-7">
                  <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24"/>
                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                      </g>
                    </svg>
                  </span>
                  <a href="{{ route('user.create') }}" class="text-primary font-weight-bold font-size-h6 mt-2">Add Users</a>
                </div>
              </div>
              <div class="row m-0">
                <div class="col bg-light-danger px-6 py-8 rounded-xl mr-7">
                  <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24"></polygon>
                        <path
                          d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z"
                          fill="#000000" fill-rule="nonzero"></path>
                        <path
                          d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z"
                          fill="#000000" opacity="0.3"></path>
                      </g>
                    </svg>
                  </span>
                  <a href="{{ route('product.index') }}" class="text-danger font-weight-bold font-size-h6 mt-2">View Products</a>
                </div>
                <div class="col bg-light-success px-6 py-8 rounded-xl">
                  <span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"></rect>
                        <path
                          d="M12.7037037,14 L15.6666667,10 L13.4444444,10 L13.4444444,6 L9,12 L11.2222222,12 L11.2222222,14 L6,14 C5.44771525,14 5,13.5522847 5,13 L5,3 C5,2.44771525 5.44771525,2 6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,13 C19,13.5522847 18.5522847,14 18,14 L12.7037037,14 Z"
                          fill="#000000" opacity="0.3"></path>
                        <path
                          d="M9.80428954,10.9142091 L9,12 L11.2222222,12 L11.2222222,16 L15.6666667,10 L15.4615385,10 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 L9.80428954,10.9142091 Z"
                          fill="#000000"></path>
                      </g>
                    </svg>
                  </span>
                  <a href="{{ route('report.stock') }}" class="text-success font-weight-bold font-size-h6 mt-2">Store Stock</a>
                </div>
              </div>
            </div>
            <div class="resize-triggers">
              <div class="expand-trigger">
                <div style="width: 506px; height: 447px;"></div>
              </div>
              <div class="contract-trigger"></div>
            </div>
          </div>
        </div>
      </div>
{{--      <div class="col-lg-8">--}}
{{--        <div class="card card-custom">--}}
{{--          <div class="card-header">--}}
{{--            <h3 class="card-title mb-0">Product Stock Alert</h3>--}}
{{--          </div>--}}
{{--          <div class="card-body py-4 px-6">--}}
{{--            <div class="table-responsive">--}}
{{--              <table class="table table-sm table-borderless table-vertical-center">--}}
{{--                <tbody>--}}
{{--                @foreach ($lowStockProducts as $product)--}}
{{--                  <tr>--}}
{{--                    <td>--}}
{{--                      <div class="symbol symbol-50 symbol-light">--}}
{{--                        <span class="symbol-label">--}}
{{--                            <img src="{{ setImage($product->image) }}" class="h-100 w-100 align-self-center" alt="" style="object-fit: cover; object-position: center">--}}
{{--                        </span>--}}
{{--                      </div>--}}
{{--                    </td>--}}
{{--                    <td>--}}
{{--                      <a href="#" class="text-dark-75 font-weight-bolder text-hover-primary">{{ $product->name }}</a>--}}
{{--                    </td>--}}
{{--                    <td class="text-right">--}}
{{--                      <span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $store ? $product->storeStock($store->id) : $product->stock - ($product->Stores->sum('pivot.stock_out')) }}</span>--}}
{{--                    </td>--}}

{{--                    <td class="text-right pr-0">--}}
{{--                      <a href="#" class="btn btn-icon btn-light btn-hover-primary btn-sm">--}}
{{--                        <span class="svg-icon svg-icon-primary svg-icon-md">--}}
{{--                          <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">--}}
{{--                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">--}}
{{--                              <rect x="0" y="0" width="24" height="24"/>--}}
{{--                              <path d="M12,21 C7.581722,21 4,17.418278 4,13 C4,8.581722 7.581722,5 12,5 C16.418278,5 20,8.581722 20,13 C20,17.418278 16.418278,21 12,21 Z"--}}
{{--                                    fill="#000000" opacity="0.3"/>--}}
{{--                              <path--}}
{{--                                d="M13,5.06189375 C12.6724058,5.02104333 12.3386603,5 12,5 C11.6613397,5 11.3275942,5.02104333 11,5.06189375 L11,4 L10,4 C9.44771525,4 9,3.55228475 9,3 C9,2.44771525 9.44771525,2 10,2 L14,2 C14.5522847,2 15,2.44771525 15,3 C15,3.55228475 14.5522847,4 14,4 L13,4 L13,5.06189375 Z"--}}
{{--                                fill="#000000"/>--}}
{{--                              <path--}}
{{--                                d="M16.7099142,6.53272645 L17.5355339,5.70710678 C17.9260582,5.31658249 18.5592232,5.31658249 18.9497475,5.70710678 C19.3402718,6.09763107 19.3402718,6.73079605 18.9497475,7.12132034 L18.1671361,7.90393167 C17.7407802,7.38854954 17.251061,6.92750259 16.7099142,6.53272645 Z"--}}
{{--                                fill="#000000"/>--}}
{{--                              <path--}}
{{--                                d="M11.9630156,7.5 L12.0369844,7.5 C12.2982526,7.5 12.5154733,7.70115317 12.5355117,7.96165175 L12.9585886,13.4616518 C12.9797677,13.7369807 12.7737386,13.9773481 12.4984096,13.9985272 C12.4856504,13.9995087 12.4728582,14 12.4600614,14 L11.5399386,14 C11.2637963,14 11.0399386,13.7761424 11.0399386,13.5 C11.0399386,13.4872031 11.0404299,13.4744109 11.0414114,13.4616518 L11.4644883,7.96165175 C11.4845267,7.70115317 11.7017474,7.5 11.9630156,7.5 Z"--}}
{{--                                fill="#000000"/>--}}
{{--                            </g>--}}
{{--                          </svg>--}}
{{--                        </span>--}}
{{--                      </a>--}}
{{--                    </td>--}}
{{--                  </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}
{{--              </table>--}}
{{--              {{ $lowStockProducts->links() }}--}}
{{--            </div>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--      </div>--}}
    </div>


    <div class="row">
      @foreach($roles as $role)
        <div class="col-xl-3">
          <div class="card card-custom bgi-no-repeat card-stretch gutter-b"
               style="background-position: right top; background-size: 30% auto; background-image: url({{ asset('assets/media/svg/shapes/abstract-1.svg') }})">
            <div class="card-body">
              <i class="fa fa-user-friends text-primary"></i>
              <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block">{{ $role->users_count }}</span>
              <span class="font-weight-bold text-muted font-size-sm">{{ $role->name }}</span>
            </div>
          </div>
        </div>
      @endforeach
      <div class="col-xl-3">
        <div class="card card-custom bgi-no-repeat card-stretch gutter-b"
             style="background-position: right top; background-size: 30% auto; background-image: url({{ asset('assets/media/svg/shapes/abstract-1.svg') }})">
          <div class="card-body">
            <span class="svg-icon svg-icon-2x svg-icon-info">
              <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <rect x="0" y="0" width="24" height="24"></rect>
                  <path
                    d="M6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,12 C19,12.5522847 18.5522847,13 18,13 L6,13 C5.44771525,13 5,12.5522847 5,12 L5,3 C5,2.44771525 5.44771525,2 6,2 Z M7.5,5 C7.22385763,5 7,5.22385763 7,5.5 C7,5.77614237 7.22385763,6 7.5,6 L13.5,6 C13.7761424,6 14,5.77614237 14,5.5 C14,5.22385763 13.7761424,5 13.5,5 L7.5,5 Z M7.5,7 C7.22385763,7 7,7.22385763 7,7.5 C7,7.77614237 7.22385763,8 7.5,8 L10.5,8 C10.7761424,8 11,7.77614237 11,7.5 C11,7.22385763 10.7761424,7 10.5,7 L7.5,7 Z"
                    fill="#000000" opacity="0.3"></path>
                  <path
                    d="M3.79274528,6.57253826 L12,12.5 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 Z"
                    fill="#000000"></path>
                </g>
              </svg>
            </span>
            <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block">{{ $totalProducts }}</span>
            <span class="font-weight-bold text-muted font-size-sm">Product</span>
          </div>
        </div>
      </div>
      @if($totalStores !== FALSE)
        <div class="col-xl-3">
          <div class="card card-custom bg-danger card-stretch gutter-b">
            <div class="card-body">
                          <span class="svg-icon svg-icon-2x svg-icon-white">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                      <rect x="0" y="0" width="24" height="24"></rect>
                                      <rect fill="#000000" opacity="0.3" x="13" y="4" width="3" height="16" rx="1.5"></rect>
                                      <rect fill="#000000" x="8" y="9" width="3" height="11" rx="1.5"></rect>
                                      <rect fill="#000000" x="18" y="11" width="3" height="9" rx="1.5"></rect>
                                      <rect fill="#000000" x="3" y="13" width="3" height="7" rx="1.5"></rect>
                                  </g>
                              </svg>
                          </span>
              <span class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 d-block">{{ $totalStores }}</span>
              <span class="font-weight-bold text-white font-size-sm">Store</span>
            </div>
          </div>
        </div>
      @endif
      <div class="col-xl-3">
        <div class="card card-custom bg-dark card-stretch gutter-b">
          <div class="card-body">
                        <span class="svg-icon svg-icon-2x svg-icon-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path
                                      d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z"
                                      fill="#000000"></path>
                                    <path
                                      d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z"
                                      fill="#000000" opacity="0.3"></path>
                                </g>
                            </svg>
                        </span>
            <span class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 text-hover-primary d-block">{{ $totalCategories }}</span>
            <span class="font-weight-bold text-white font-size-sm">Categories</span>
          </div>
        </div>
      </div>
    </div>
{{--    <div class="row">--}}
{{--      <div class="col-xl-4">--}}
{{--        <div class="card card-custom card-stretch gutter-b">--}}
{{--          <div class="card-body d-flex flex-column p-0" style="position: relative;">--}}
{{--            <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">--}}
{{--              <div class="d-flex flex-column mr-2">--}}
{{--                <a href="#" class="text-dark-75 text-hover-primary font-weight-bolder font-size-h5">Weekly Sales</a>--}}
{{--                <span class="text-muted font-weight-bold mt-2">Your Weekly Sales Chart</span>--}}
{{--              </div>--}}
{{--              <span class="symbol symbol-light-success symbol-45">--}}
{{--                                <span class="symbol-label font-weight-bolder font-size-h6">+57</span>--}}
{{--                            </span>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--      <div class="col-xl-4">--}}
{{--        <div class="card card-custom card-stretch gutter-b">--}}
{{--          <div class="card-body d-flex flex-column p-0" style="position: relative;">--}}
{{--            <div class="d-flex align-items-center justify-content-between card-spacer">--}}
{{--              <div class="d-flex flex-column mr-2">--}}
{{--                <a href="#" class="text-dark-75 text-hover-primary font-weight-bolder font-size-h5">Authors Progress</a>--}}
{{--                <span class="text-muted font-weight-bold mt-2">Marketplace Authors Chart</span>--}}
{{--              </div>--}}
{{--              <span class="symbol symbol-light-danger symbol-45">--}}
{{--                                <span class="symbol-label font-weight-bolder font-size-h6">+94</span>--}}
{{--                            </span>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--      <div class="col-xl-4">--}}
{{--        <div class="card card-custom card-stretch gutter-b">--}}
{{--          <div class="card-body d-flex flex-column p-0" style="position: relative;">--}}
{{--            <div class="d-flex align-items-center justify-content-between card-spacer">--}}
{{--              <div class="d-flex flex-column mr-2">--}}
{{--                <a href="#" class="text-dark-75 text-hover-primary font-weight-bolder font-size-h5">Sales Progress</a>--}}
{{--                <span class="text-muted font-weight-bold mt-2">Marketplace Sales Chart</span>--}}
{{--              </div>--}}
{{--              <span class="symbol symbol-light-primary symbol-45">--}}
{{--                                <span class="symbol-label font-weight-bolder font-size-h6">+28</span>--}}
{{--                            </span>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--    </div>--}}
{{--    <div class="row">--}}
{{--      <div class="col-xl-4">--}}
{{--        <div class="card card-custom card-stretch gutter-b">--}}
{{--          <div class="card-body p-0" style="position: relative;">--}}
{{--            <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">--}}
{{--                            <span class="symbol symbol-50 symbol-light-info mr-2">--}}
{{--                                <span class="symbol-label">--}}
{{--                                    <span class="svg-icon svg-icon-xl svg-icon-info">--}}
{{--                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">--}}
{{--                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">--}}
{{--                                                <rect x="0" y="0" width="24" height="24"></rect>--}}
{{--                                                <path--}}
{{--                                                  d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z"--}}
{{--                                                  fill="#000000" fill-rule="nonzero" opacity="0.3"></path>--}}
{{--                                                <path--}}
{{--                                                  d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z"--}}
{{--                                                  fill="#000000"></path>--}}
{{--                                            </g>--}}
{{--                                        </svg>--}}
{{--                                    </span>--}}
{{--                                </span>--}}
{{--                            </span>--}}
{{--              <div class="d-flex flex-column text-right">--}}
{{--                <span class="text-dark-75 font-weight-bolder font-size-h3">+259</span>--}}
{{--                <span class="text-muted font-weight-bold mt-2">Sales Change</span>--}}
{{--              </div>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--      <div class="col-xl-4">--}}
{{--        <div class="card card-custom card-stretch gutter-b">--}}
{{--          <div class="card-body p-0" style="position: relative;">--}}
{{--            <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">--}}
{{--                            <span class="symbol symbol-50 symbol-light-success mr-2">--}}
{{--                                <span class="symbol-label">--}}
{{--                                    <span class="svg-icon svg-icon-xl svg-icon-success">--}}
{{--                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">--}}
{{--                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">--}}
{{--                                                <rect x="0" y="0" width="24" height="24"></rect>--}}
{{--                                                <rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5"></rect>--}}
{{--                                                <path--}}
{{--                                                  d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z"--}}
{{--                                                  fill="#000000" opacity="0.3"></path>--}}
{{--                                            </g>--}}
{{--                                        </svg>--}}
{{--                                    </span>--}}
{{--                                </span>--}}
{{--                            </span>--}}
{{--              <div class="d-flex flex-column text-right">--}}
{{--                <span class="text-dark-75 font-weight-bolder font-size-h3">750$</span>--}}
{{--                <span class="text-muted font-weight-bold mt-2">Weekly Income</span>--}}
{{--              </div>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--      <div class="col-xl-4">--}}
{{--        <div class="card card-custom card-stretch gutter-b">--}}
{{--          <div class="card-body p-0" style="position: relative;">--}}
{{--            <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">--}}
{{--                            <span class="symbol symbol-50 symbol-light-primary mr-2">--}}
{{--                                <span class="symbol-label">--}}
{{--                                    <span class="svg-icon svg-icon-xl svg-icon-primary">--}}
{{--                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">--}}
{{--                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">--}}
{{--                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>--}}
{{--                                                <path--}}
{{--                                                  d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"--}}
{{--                                                  fill="#000000" fill-rule="nonzero" opacity="0.3"></path>--}}
{{--                                                <path--}}
{{--                                                  d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"--}}
{{--                                                  fill="#000000" fill-rule="nonzero"></path>--}}
{{--                                            </g>--}}
{{--                                        </svg>--}}
{{--                                    </span>--}}
{{--                                </span>--}}
{{--                            </span>--}}
{{--              <div class="d-flex flex-column text-right">--}}
{{--                <span class="text-dark-75 font-weight-bolder font-size-h3">+6,5K</span>--}}
{{--                <span class="text-muted font-weight-bold mt-2">New Users</span>--}}
{{--              </div>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--    </div>--}}
  </div>

@endsection
