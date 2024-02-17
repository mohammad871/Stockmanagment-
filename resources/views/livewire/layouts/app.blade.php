<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> صفحه - @yield('title') </title>
    @livewireStyles



    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset("assets/css/soft-ui-dashboard.css") }}" rel="stylesheet" scope="impo" />
    <link rel="stylesheet" href="{{ asset("vendor/fontawesome/css/all.min.css") }}">
    <link rel="stylesheet" href="{{ asset("vendor/datatable/css/datatables.min.css") }}">
    <link rel="stylesheet" href="{{ asset("vendor/persian-calendar/css/persian-datepicker.css") }}">
    <link rel="stylesheet" href="{{ asset("vendor/select2/css/select2.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/css/style.css") }}">

    @stack('styles')
</head>
<body class="g-sidenav-show rtl bg-gray-100">
    <div>

        @if(!request()->is('login'))
        <aside class="sidenav navbar d-print-none navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-end me-3 rotate-caret" id="sidenav-main">
            <div class="sidenav-header">
                <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute start-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
                <a class="navbar-brand m-0" href="/">
                    <img src="{{ asset("images/store.svg") }}" class="navbar-brand-img h-100" alt="main_logo">
                    <span class="me-1 font-weight-bold">داشبورد فورشگاه</span>
                </a>
            </div>
            <hr class="horizontal dark mt-0">
            <div class="collapse navbar-collapse px-0 w-auto " id="sidenav-collapse-main">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-home"></i>
                            </div>
                            <span class="nav-link-text me-1">صفحه اصلی</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('stock') ? 'active' : '' }}" href="/stock">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <span class="nav-link-text me-1">گدام / انبار</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('stockItem') ? 'active' : '' }}" href="/stockItem">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <span class="nav-link-text me-1"> اجناس داخل گدام</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('item') ? 'active' : '' }}" href="/item">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-file"></i>
                            </div>
                            <span class="nav-link-text me-1">اجناس</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('customer') ? 'active' : '' }}" href="/customer">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="nav-link-text me-1">مشتریان</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('purchase') ? 'active' : '' }}" href="/purchase">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <span class="nav-link-text me-1">دخولی</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('sell') ? 'active' : '' }}" href="/sell">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <span class="nav-link-text me-1">خروجی</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('return') ? 'active' : '' }}" href="/return">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <span class="nav-link-text me-1">برگشتی</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('transfer') ? 'active' : '' }}" href="/transfer">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-sync"></i>
                            </div>
                            <span class="nav-link-text me-1">انتقال</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('report') ? 'active' : '' }}" href="/report">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-file"></i>
                            </div>
                            <span class="nav-link-text me-1">راپور</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('user') ? 'active' : '' }}" href="/user">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <span class="nav-link-text me-1">کاربران</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('backupList') ? 'active' : '' }}" href="/backupList">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-database"></i>
                            </div>
                            <span class="nav-link-text me-1">بک اپ گرفتن</span>
                        </a>
                    </li>


                </ul>
            </div>
            <div class="sidenav-footer bottom-0 position-absolute w-70 me-4">
                <a class="btn bg-gradient-primary mt-3 w-100" href="/logout">خروج</a>
            </div>
        </aside>
        @endif

        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">

            @if(!request()->is('login'))
            <!-- Navbar -->
            <nav class="navbar d-print-none navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
                <div class="container-fluid py-1 px-3">
                    <div class="collapse navbar-collapse mt-sm-0 mt-2 px-0" id="navbar">
                        <ul class="navbar-nav me-auto ms-0 justify-content-end">
                            <li class="nav-item ps-2 d-flex align-items-center">
                                <a href="/user" class="nav-link text-body fw-bold p-0">
                                    {{ \Illuminate\Support\Facades\Auth::user()->name }}
                                </a>
                            </li>
                            <li class="nav-item ps-2 d-flex align-items-center">
                                <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-bell cursor-pointer"></i>
                                </a>
                            </li>
                            <li class="nav-item d-xl-none pe-3 d-flex align-items-center">
                                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                    <div class="sidenav-toggler-inner">
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
            @endif

            {{ $slot }}

        </main>
    </div>
    @livewireScripts

    <script src="{{ asset("assets/js/core/popper.min.js") }}"></script>
    <script src="{{ asset("assets/js/core/bootstrap.min.js") }}"></script>
    <script src="{{ asset("assets/js/plugins/perfect-scrollbar.min.js") }}"></script>
    <script src="{{ asset("assets/js/plugins/smooth-scrollbar.min.js") }}"></script>

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>

    <!-- Github buttons -->
    <script src="{{ asset("vendor/persian-calendar/js/jquery.min.js") }}"></script>
    <script src="{{ asset("vendor/select2/js/select2.min.js") }}"></script>
    <script src="{{ asset("assets/js/plugins/buttons.js") }}"></script>
    <script src="{{ asset("assets/js/soft-ui-dashboard.min.js") }}"></script>
    <script src="{{ asset("assets/js/script.js") }}"></script>
    <script src="{{ asset("vendor/fontawesome/js/all.min.js") }}"></script>
    <script src="{{ asset("vendor/datatable/js/datatables.min.js") }}"></script>
    <script src="{{ asset("vendor/persian-calendar/js/persian-datepicker.js") }}"></script>

    <script>
        $(document).ready(function() {
            let index = 0;
            Livewire.hook('message.processed', (message, component) => {
                dataTable();
            })
            dataTable();
            function dataTable() {

                $('.data-table').DataTable({
                    language: {
                        searchPlaceholder: "\u062C\u0633\u062A\u062C\u0648 \u06A9\u0631\u062F\u0646.... ",
                        search: "",
                        lengthMenu: "\u0646\u0634\u0627\u0646 \u062F\u0627\u062F\u0646 _MENU_ \u0631\u06CC\u06A9\u0627\u0631\u062F \u062F\u0631\u06CC\u06A9 \u0635\u0641\u062D\u0647",
                        zeroRecords: "\u0631\u06CC\u06A9\u0627\u0631\u062F \u06CC\u0627\u0641\u062A \u0646\u0634\u062F!",
                        info: "",
                        infoEmpty: "\u0631\u06CC\u06A9\u0627\u0631\u062F \u0628\u0631\u0627\u06CC \u0646\u0634\u0627\u0646 \u062F\u0627\u062F\u0646 \u06CC\u0627\u0641\u062A \u0646\u0634\u062F!",
                        infoFiltered: "(\u0646\u0634\u0627\u0646 \u062F\u0627\u062F\u0646 _MAX_ \u0627\u0632)",
                        paginate: {previous: " << \u0642\u0628\u0644\u06CC", next: "  \u0628\u0639\u062F\u06CC >> "},
                        retrieve: true,
                        processing: true,
                        deferRender: true,
                        destroy: true,
                    }
                })
            }



            $(document).on("focusin", '.persian-calendar',function(e) {
                $('.persian-calendar').datepicker({
                    dateFormat: 'yy-mm-dd',
                    autoSize: true,
                    gotoCurrent: true
                });
            })
        })

    </script>

    <script>
        // $('.form-select').select2({
        //     dir: "rtl",
        //     allowClear: false
        // });
        //
        // $(document).on("change", '.form-select',function(e) {
        //
        // });
    </script>

    @stack('scripts')
</body>
</html>
