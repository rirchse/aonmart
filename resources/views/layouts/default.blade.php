@php($company = companyInfo())

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $company->name }}</title>

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <link href="{{ asset('assets/css/themes/layout/header/base/light.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/themes/layout/header/menu/light.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/themes/layout/brand/light.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/themes/layout/aside/light.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{asset('css/print.css')}}" rel="stylesheet" type="text/css"/>

    <!--end::Layout Themes-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">

    @stack('style')
    @livewireStyles
</head>

<body id="kt_body"
    class="header-fixed header-mobile-fixed  @unless(request()->routeIs(['sale.create', 'sale.edit'])) aside-enabled aside-fixed @endunless aside-minimize-hoverable page-loading">
    <!--begin::Header Mobile-->
    <div id="kt_header_mobile" class="header-mobile align-items-center header-mobile-fixed no-print">
        <!--begin::Logo-->
        <a href="{{ route('dashboard') }}" style="height:100%">
          @if ($company->logo)
            <img alt="{{ $company->name }}" src="{{ asset('storage/' . $company->logo) }}" style="height:100%; width: auto">
          @else
            <h1 class="py-5 px-2 font-size-h1-lg">{{ $company->name }}</h1>
          @endif
        </a>
        <!--end::Logo-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <!--begin::Aside Mobile Toggle-->
            <button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle">
                <span></span>
            </button>
            <!--end::Aside Mobile Toggle-->
            <!--begin::Header Menu Mobile Toggle-->
            <button class="btn p-0 burger-icon ml-4" id="kt_header_mobile_toggle">
                <span></span>
            </button>
            <!--end::Header Menu Mobile Toggle-->
            <!--begin::Topbar Mobile Toggle-->
            <button class="btn btn-hover-text-primary p-0 ml-2" id="kt_header_mobile_topbar_toggle">
                <span class="svg-icon svg-icon-xl">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24" />
                            <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero"
                                opacity="0.3" />
                            <path
                                d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"
                                fill="#000000" fill-rule="nonzero" />
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                </span>
            </button>
            <!--end::Topbar Mobile Toggle-->
        </div>
        <!--end::Toolbar-->
    </div>
    <!--end::Header Mobile-->
    <div class="kt-grid kt-grid--hor kt-grid--root">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
            @unless(request()->routeIs(['sale.create', 'sale.edit']))
                @include('layouts.includes.sidebar')
            @endunless
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor @unless(request()->routeIs(['sale.create', 'sale.edit'])) kt-wrapper @endunless">
                    @include('layouts.includes.navbar')
                    <div class="content d-flex flex-column flex-column-fluid @if(request()->routeIs(['sale.create', 'sale.edit'])) pt-4 pb-0 @endif" id="kt_content">
                        @unless(request()->routeIs(['sale.create', 'sale.edit']))
                            <div class="container">
                                @yield('content')
                            </div>
                        @else
                            @yield('content')
                        @endunless
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.includes.quickPanel')

    <script>
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1400
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#3699FF",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#8950FC",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#E4E6EF",
                        "dark": "#181C32"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#E1F0FF",
                        "secondary": "#EBEDF3",
                        "success": "#C9F7F5",
                        "info": "#EEE5FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#3F4254",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#EBEDF3",
                    "gray-300": "#E4E6EF",
                    "gray-400": "#D1D3E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#7E8299",
                    "gray-700": "#5E6278",
                    "gray-800": "#3F4254",
                    "gray-900": "#181C32"
                }
            },
            "font-family": "Poppins"
        };
    </script>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}" type="text/javascript"></script>

    <script>
        $(function() {
            $('[data-tooltip="tooltip"]').tooltip();
            $('.alert').delay(4000).fadeOut();

            $('.select2').select2({
                placeholder: 'Select option',
            });

            $('.select2_multiple').select2({
                placeholder: 'Select option',
                closeOnSelect: false
            });

            $('.select2-withTag').select2({
                placeholder: 'Select option',
                tags: "true",
            });

            $('.datetimepicker').daterangepicker({
                timePicker: true,
                singleDatePicker: true,
                locale: {
                    format: 'YYYY/M/DD HH:mm:ss'
                }
            });

            let dtButtonClasses = 'btn-default btn-sm';
            $.extend(true, $.fn.dataTable.defaults, {
                responsive: true,
                lengthMenu: [
                    [-1, 10, 25, 50, 100],
                    ["All", 10, 25, 50, 100]
                ],
                pageLength: 25,
                "order": [],
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'<'d-flex justify-content-center justify-content-md-end'f<'ml-2'B>>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    extend: 'collection',
                    className: dtButtonClasses,
                    text: 'Export',
                    buttons: [{
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }, {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }, {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }, {
                        extend: 'csv',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }, {
                        extend: 'copy',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }, {
                        extend: 'colvis',
                        className: dtButtonClasses,
                        text: 'Columns'
                    }]
                }]
            });
        });
    </script>
    @stack('modals')

    @livewireScripts

    @stack('script')

    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
    </script>
</body>

</html>
