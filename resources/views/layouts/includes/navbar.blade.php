<!--begin::Header-->
<div id="kt_header" class="header header-fixed no-print">
    <!--begin::Container-->
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        <!--begin::Header Menu Wrapper-->
        <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
            <!--begin::Header Menu-->
            <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
                <!--begin::Header Nav-->
                <ul class="menu-nav">
                    @unless(request()->routeIs(['sale.create', 'sale.edit']))
                        <li class="menu-item menu-item-submenu menu-item-rel">
                            <a href="{{ route('sale.create') }}" class="btn btn-primary">
                                <span class="menu-text">POS</span>
                                <i class="menu-arrow"></i>
                            </a>
                        </li>
                    @else
                        <li class="menu-item menu-item-submenu menu-item-rel">
                            <a href="{{ url('/') }}" class="btn btn-primary">
                                <span class="menu-text">Dashboard</span>
                                <i class="menu-arrow"></i>
                            </a>
                        </li>
                    @endunless
                </ul>
            </div>
        </div>

        <div class="topbar">
                <div class="topbar-item mr-2">
                @if(auth()->user()->roles[0]->name == 'Super Admin')
                @can('access.all.store')
                    <form action="{{ route('change_store') }}" method="post" id="store_filter_form">
                        @csrf
                        <select class="form-control select2 w-100" name="store" id="store" onchange="document.getElementById('store_filter_form').submit()">
                            
                            <option value="all" selected>All</option>
                            @foreach (\App\Models\Store::orderBy('name')->get() as $store)
                                <option value="{{ $store->id }}" {{ session('store_id') == $store->id ? 'selected' : null }}>{{ $store->name }}</option>
                            @endforeach
                            
                        </select>
                    </form>
                    @endcan
                    @else
                    <?php
                    $getstore = \App\Models\Store::find(auth()->user()->store_id);
                    ?>
                    <div style="border:1px solid #ddd; padding:7px 15px; border-radius:5px">{{$getstore->name}}</div>
                    @endif
            
                </div>

            <!--begin::User-->
            <div class="topbar-item">
                <div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                    <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
                    <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">{{ auth()->user()->name }}</span>
                    <span class="symbol symbol-lg-35 symbol-25 symbol-light-success">
                        <span class="symbol-label font-size-h5 font-weight-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </span>
                </div>
            </div>
            <!--end::User-->
        </div>
        <!--end::Topbar-->
    </div>
    <!--end::Container-->
</div>
<!--end::Header-->
