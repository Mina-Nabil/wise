<!DOCTYPE html>
<!-- Template Name: DashCode - HTML, React, Vue, Tailwind Admin Dashboard Template Author: Codeshaper Website: https://codeshaper.net Contact: support@codeshaperbd.net Like: https://www.facebook.com/Codeshaperbd Purchase: https://themeforest.net/item/dashcode-admin-dashboard-template/42600453 License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project. -->
<html lang="zxx" dir="ltr" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <title>Wise @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo/wise-favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" href="{{ asset('assets/css/rt-plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    @yield('head')

    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />

    <!-- End : Theme CSS-->
    <script src="{{ asset('assets/js/settings.js') }}" sync></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    @auth
        {{-- Sweet Alert --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        {{-- Pusher file --}}
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script>
            @env(['development', 'staging'])

                // Enable pusher logging - not included in prod
                Pusher.logToConsole = true;
            @endenv

            var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
            });

            var channel = pusher.subscribe('user{{ Auth::user()->id }}-channel');
            channel.bind('notifications-event', function(data) {
                console.log(data)
                Swal.fire({
                    title: data.message.title,
                    toast: true,
                    position: 'bottom-end',
                    timer: 7000,
                    text: data.message.message,
                    icon: 'info',
                    confirmButtonText: 'Ok',
                    showDenyButton: data.message.route != null,
                    denyButtonText: "Check now!",
                    denyButtonColor: "#5580dd",
                }).then((result) => {
                    if (result.isDenied) {
                        window.location.replace(data.message.route)
                    }
                });
            });
        </script>
    @endauth
    @livewireStyles
</head>

<body class=" font-inter dashcode-app">
    <!-- [if IE]> <p class="browserupgrade"> You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security. </p> <![endif] -->
    <main class="app-wrapper">
        <!-- BEGIN: Sidebar -->
        <!-- BEGIN: Sidebar -->
        <div class="sidebar-wrapper group">
            <div id="bodyOverlay"
                class="w-screen h-screen fixed top-0 bg-slate-900 bg-opacity-50 backdrop-blur-sm z-10 hidden"></div>
            <div class="logo-segment">
                <a class="flex items-center" href="{{ url('/') }}">
                    <img src="{{ asset('assets/images/logo/wise-logo-wide.png') }}" class="black_logo" alt="logo">
                    <img src="{{ asset('assets/images/logo/wise-logo-wide-white.png') }}" class="white_logo"
                        alt="logo">
                    {{-- <span class="ltr:ml-3 rtl:mr-3 text-xl font-Inter font-bold text-slate-900 dark:text-white">Wise
                        Ins.</span> --}}
                </a>
                <!-- Sidebar Type Button -->
                <div id="sidebar_type" class="cursor-pointer text-slate-900 dark:text-white text-lg">
                    <span class="sidebarDotIcon extend-icon cursor-pointer text-slate-900 dark:text-white text-2xl">
                        <div
                            class="h-4 w-4 border-[1.5px] border-slate-900 dark:border-slate-700 rounded-full transition-all duration-150 ring-2 ring-inset ring-offset-4 ring-black-900 dark:ring-slate-400 bg-slate-900 dark:bg-slate-400 dark:ring-offset-slate-700">
                        </div>
                    </span>
                    <span class="sidebarDotIcon collapsed-icon cursor-pointer text-slate-900 dark:text-white text-2xl">
                        <div
                            class="h-4 w-4 border-[1.5px] border-slate-900 dark:border-slate-700 rounded-full transition-all duration-150">
                        </div>
                    </span>
                </div>
                <button class="sidebarCloseIcon text-2xl">
                    <iconify-icon class="text-slate-900 dark:text-slate-200"
                        icon="clarity:window-close-line"></iconify-icon>
                </button>
            </div>
            <div id="nav_shadow"
                class="nav_shadow h-[60px] absolute top-[80px] nav-shadow z-[1] w-full transition-all duration-200 pointer-events-none
      opacity-0">
            </div>
            <div class="sidebar-menus bg-white dark:bg-slate-800 py-2 px-4 h-[calc(100%-80px)] overflow-y-auto z-50"
                id="sidebar_menus">
                <ul class="sidebar-menu">
                    <li class="sidebar-menu-title">Main</li>
                    {{-- <li>
                        <a href="#" class="navItem">
                            <span class="flex items-center">
                                <iconify-icon class=" nav-icon" icon="heroicons-outline:home"></iconify-icon>
                                <span>Home</span>
                            </span>
                            <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="{{url('/')}}">Dashboard</a>
                            </li>
                        </ul>
                    </li> --}}
                    <li>
                        <a href="{{ url('/') }}" class="navItem @yield('home')">
                            <span class="flex items-center">
                                <iconify-icon class=" nav-icon" icon="heroicons-outline:home"></iconify-icon>
                                <span>Home</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/calendar') }}" class="navItem @yield('calendar')">
                            <span class="flex items-center">
                                <iconify-icon class=" nav-icon" icon="heroicons-outline:calendar"></iconify-icon>
                                <span>Calendar</span>
                            </span>
                        </a>
                    </li>
                    {{-- <li>
                        <a href="{{ url('/tasks') }}" class="navItem @yield('tasks')">
                            <span class="flex items-center">
                                <iconify-icon class="nav-icon" icon="ic:round-add-task"></iconify-icon>
                                <span>Tasks</span>
                            </span>
                        </a>
                    </li> --}}

                    <li class="">
                        <a href="javascript:void(0)" class="navItem">
                            <span class="flex items-center">
                                <iconify-icon class=" nav-icon" icon="ic:round-add-task"></iconify-icon>
                                <span>Operational</span>
                            </span>
                            <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="{{ url('/tasks') }}" class="@yield('tasks')">Tasks</a>
                            </li>
                            <li>
                                <a href="{{ url('/claims') }}" class="@yield('claims')">Claims</a>
                            </li>
                            <li>
                                <a href="{{ url('/endorsement') }}" class="@yield('endorsement')">Endorsement</a>
                            </li>
                        </ul>
                    </li>


                    <!-- Apps Area -->
                    <li class="sidebar-menu-title">CRM</li>
                    <li>
                        <a href="{{ url('/offers') }}" class="navItem @yield('offers')">
                            <span class="flex items-center">
                                <iconify-icon class="nav-icon" icon="ic:outline-local-offer"></iconify-icon>
                                <span>Offers</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/sold-policies') }}" class="navItem @yield('sold-policies')">
                            <span class="flex items-center">
                                <iconify-icon class="nav-icon" icon="iconoir:privacy-policy"></iconify-icon>
                                <span>Sold Policies</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/outstanding-sold-policies') }}" class="navItem @yield('outstanding-sold-policies')">
                            <span class="flex items-center">
                                <iconify-icon class="nav-icon" icon="ic:round-security"></iconify-icon>
                                <span>Outstanding Policies</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/exp-sold-policies') }}" class="navItem @yield('exp-sold-policies')">
                            <span class="flex items-center">
                                <iconify-icon class="nav-icon" icon="iconoir:privacy-policy"></iconify-icon>
                                <span>Expiry Sold Policies</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/payments') }}" class="navItem @yield('client-payment-index')">
                            <span class="flex items-center">
                                <iconify-icon class="nav-icon" icon="material-symbols:payments"></iconify-icon>
                                <span>Client Payments</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/customers') }}" class="navItem @yield('customers')">
                            <span class="flex items-center">
                                <iconify-icon class="nav-icon" icon="raphael:customer"></iconify-icon>
                                <span>Customers</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/corporates') }}" class="navItem @yield('corporates')">
                            <span class="flex items-center">
                                <iconify-icon class="nav-icon" icon="material-symbols:corporate-fare"></iconify-icon>
                                <span>Corporates</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/followups') }}" class="navItem @yield('followups')">
                            <span class="flex items-center">
                                <iconify-icon class="nav-icon" icon="icon-park-outline:cycle-arrow"></iconify-icon>
                                <span>Follow Ups</span>
                            </span>
                        </a>
                    </li>

                    <li class="">
                        <a href="javascript:void(0)" class="navItem">
                            <span class="flex items-center">
                                <iconify-icon class=" nav-icon" icon="ic:round-add-task"></iconify-icon>
                                <span>Reports</span>
                            </span>
                            <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="{{ url('/reports/offers') }}" class="@yield('reports.offers')">Offers</a>
                            </li>
                            <li>
                                <a href="{{ url('/reports/tasks') }}" class="@yield('reports.tasks')">Tasks</a>
                            </li>
                            <li>
                                <a href="{{ url('/reports/followups') }}" class="@yield('reports.followups')">Followups</a>
                            </li>
                            <li>
                                <a href="{{ url('/reports/sold-policy') }}" class="@yield('reports.sold-policy')">Sold
                                    Policies</a>
                            </li>
                            <li>
                                <a href="{{ url('/reports/client-payment-finance') }}"
                                    class="@yield('client-payment-finance')">Payments Due</a>
                            </li>
                            <li>
                                <a href="{{ url('/reports/client-payments') }}" class="@yield('client-payments')">Client
                                    Payments</a>
                            </li>
                            <li>
                                <a href="{{ url('/reports/client-interest') }}" class="@yield('client-interests-report')">Customer
                                    Interests</a>
                            </li>
                            <li>
                                <a href="{{ url('/reports/corporate-interest') }}"
                                    class="@yield('corporate-interests-report')">Corporate Interests</a>
                            </li>
                            <li>
                                <a href="{{ url('/reports/invoices-report') }}" class="@yield('invoices-report')">Invoices
                                    Report</a>
                            </li>
                        </ul>
                    </li>
                    @if (Auth::user()->is_admin || Auth::user()->is_hr || Auth::user()->is_finance || Auth::user()->is_operations)
                        <li class="sidebar-menu-title">Settings</li>
                        @if (Auth::user()->is_admin || Auth::user()->is_finance)
                            <li>
                                <a href="{{ url('/commissions') }}" class="navItem @yield('commissions')">
                                    <span class="flex items-center">
                                        <iconify-icon class="nav-icon"
                                            icon="flowbite:sale-percent-outline"></iconify-icon>
                                        <span>Commissions</span>
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->is_admin || Auth::user()->is_hr)
                            <li>
                                <a href="{{ url('/contacts') }}" class="navItem @yield('contacts')">
                                    <span class="flex items-center">
                                        <iconify-icon class="nav-icon"
                                            icon="material-symbols:corporate-fare"></iconify-icon>
                                        <span>Contacts</span>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/users') }}" class="navItem @yield('users')">
                                    <span class="flex items-center">
                                        <iconify-icon class="nav-icon" icon="raphael:users"></iconify-icon>
                                        <span>Users</span>
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->is_admin)
                            <li>
                                <a href="{{ url('/policies') }}" class="navItem @yield('policies')">
                                    <span class="flex items-center">
                                        <iconify-icon class="nav-icon"
                                            icon="material-symbols:policy-outline-rounded"></iconify-icon>
                                        <span>Policies</span>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/fields') }}" class="navItem @yield('fields')">
                                    <span class="flex items-center">
                                        <iconify-icon class="nav-icon"
                                            icon="solar:text-field-focus-bold"></iconify-icon>
                                        <span>Line Fields</span>
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->is_admin || Auth::user()->is_operations)
                            <li>
                                <a href="{{ url('/cars') }}" class="navItem @yield('cars')">
                                    <span class="flex items-center">
                                        <iconify-icon class="nav-icon" icon="mingcute:car-line"></iconify-icon>
                                        <span>Cars</span>
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->is_admin || Auth::user()->is_finance)
                            <li>
                                <a href="{{ url('/companies') }}" class="navItem @yield('companies')">
                                    <span class="flex items-center">
                                        <iconify-icon class="nav-icon"
                                            icon="heroicons:building-storefront"></iconify-icon>
                                        <span>Companies</span>
                                    </span>
                                </a>
                            </li>
                        @endif
                    @endif




                    @can('viewAny', \App\Models\Users\AppLog::class)
                        <li>
                            <a href="{{ url('/logs') }}" class="navItem @yield('logs')">
                                <span class="flex items-center">
                                    <iconify-icon class="nav-icon" icon="material-symbols:list"></iconify-icon>
                                    <span>Logs</span>
                                </span>
                            </a>
                        </li>

                        {{-- <li>
                            <a href="{{ url('/slarecords') }}" class="navItem @yield('sla-record')">
                                <span class="flex items-center">
                                    <iconify-icon class="nav-icon" icon="vaadin:records"></iconify-icon>
                                    <span>SLA Records</span>
                                </span>
                            </a>
                        </li> --}}
                    @endcan
                </ul>
            </div>
        </div>
        <!-- End: Sidebar -->
        <!-- End: Sidebar -->
        <!-- BEGIN: Settings -->

        <!-- BEGIN: Settings -->
        <!-- Settings Toggle Button -->
        {{-- <button
            class="fixed ltr:md:right-[-29px] ltr:right-0 rtl:left-0 rtl:md:left-[-29px] top-1/2 z-[888] translate-y-1/2 bg-slate-800 text-slate-50 dark:bg-slate-700 dark:text-slate-300 cursor-pointer transform rotate-90 flex items-center text-sm font-medium px-2 py-2 shadow-deep ltr:rounded-b rtl:rounded-t"
            data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvas">
            <iconify-icon class="text-slate-50 text-lg animate-spin"
                icon="material-symbols:settings-outline-rounded"></iconify-icon>
            <span class="hidden md:inline-block ltr:ml-2 rtl:mr-2">Settings</span>
        </button> --}}

        <!-- BEGIN: Settings Modal -->
        {{-- <div class="offcanvas offcanvas-end fixed bottom-0 flex flex-col max-w-full bg-white dark:bg-slate-800 invisible bg-clip-padding shadow-sm outline-none transition duration-300 ease-in-out text-gray-700 top-0 ltr:right-0 rtl:left-0 border-none w-96"
            tabindex="-1" id="offcanvas" aria-labelledby="offcanvas">
            <div class="offcanvas-header flex items-center justify-between p-4 pt-3 border-b border-b-slate-300">
                <div>
                    <h3 class="block text-xl font-Inter text-slate-900 font-medium dark:text-[#eee]">
                        Theme customizer
                    </h3>
                    <p class="block text-sm font-Inter font-light text-[#68768A] dark:text-[#eee]">Customize & Preview
                        in Real Time</p>
                </div>
                <button type="button"
                    class="box-content text-2xl w-4 h-4 p-2 pt-0 -my-5 -mr-2 text-black dark:text-white border-none rounded-none opacity-100 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                    data-bs-dismiss="offcanvas">
                    <iconify-icon icon="line-md:close"></iconify-icon>
                </button>
            </div>
            <div class="offcanvas-body flex-grow overflow-y-auto">
                <div class="settings-modal">
                    <div class="p-6">

                        <h3 class="mt-4">Theme</h3>
                        <form class="input-area flex items-center space-x-8 rtl:space-x-reverse" id="themeChanger">
                            <div class="input-group flex items-center">
                                <input type="radio" id="light" name="theme" value="light"
                                    class="themeCustomization-checkInput">
                                <label for="light" class="themeCustomization-checkInput-label">Light</label>
                            </div>
                            <div class="input-group flex items-center">
                                <input type="radio" id="dark" name="theme" value="dark"
                                    class="themeCustomization-checkInput">
                                <label for="dark" class="themeCustomization-checkInput-label">Dark</label>
                            </div>
                            <div class="input-group flex items-center">
                                <input type="radio" id="semiDark" name="theme" value="semiDark"
                                    class="themeCustomization-checkInput">
                                <label for="semiDark" class="themeCustomization-checkInput-label">Semi Dark</label>
                            </div>
                        </form>
                    </div>
                    <div class="divider"></div>
                    <div class="p-6">

                        <div class="flex items-center justify-between mt-5">
                            <h3 class="!mb-0">Rtl</h3>
                            <label id="rtl_ltr"
                                class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input type="checkbox" value="" class="sr-only peer">
                                <span
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-600"></span>
                            </label>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="p-6">
                        <h3>Content Width</h3>
                        <div class="input-area flex items-center space-x-8 rtl:space-x-reverse">
                            <div class="input-group flex items-center">
                                <input type="radio" id="fullWidth" name="content-width" value="fullWidth"
                                    class="themeCustomization-checkInput">
                                <label for="fullWidth" class="themeCustomization-checkInput-label ">Full Width</label>
                            </div>
                            <div class="input-group flex items-center">
                                <input type="radio" id="boxed" name="content-width" value="boxed"
                                    class="themeCustomization-checkInput">
                                <label for="boxed" class="themeCustomization-checkInput-label ">Boxed</label>
                            </div>
                        </div>
                        <h3 class="mt-4">Menu Layout</h3>
                        <div class="input-area flex items-center space-x-8 rtl:space-x-reverse">
                            <div class="input-group flex items-center">
                                <input type="radio" id="vertical_menu" name="menu_layout" value="vertical"
                                    class="themeCustomization-checkInput" selected>
                                <label for="vertical_menu"
                                    class="themeCustomization-checkInput-label ">Vertical</label>
                            </div>
                            <div class="input-group flex items-center">
                                <input type="radio" id="horizontal_menu" name="menu_layout" value="horizontal"
                                    class="themeCustomization-checkInput">
                                <label for="horizontal_menu"
                                    class="themeCustomization-checkInput-label ">Horizontal</label>
                            </div>
                        </div>
                        <div id="menuCollapse" class="flex items-center justify-between mt-5">
                            <h3 class="!mb-0">Menu Collapsed</h3>
                            <label
                                class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input type="checkbox" value="" class="sr-only peer">
                                <span
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                        <div id="menuHidden" class="!flex items-center justify-between mt-5">
                            <h3 class="!mb-0">Menu Hidden</h3>
                            <label id="menuHide"
                                class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input type="checkbox" value="" class="sr-only peer">
                                <span
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="p-6">
                        <h3>Navbar Type</h3>
                        <div class="input-area flex flex-wrap items-center space-x-4 rtl:space-x-reverse">
                            <div class="input-group flex items-center">
                                <input type="radio" id="nav_floating" name="navbarType" value="floating"
                                    class="themeCustomization-checkInput">
                                <label for="nav_floating"
                                    class="themeCustomization-checkInput-label ">Floating</label>
                            </div>
                            <div class="input-group flex items-center">
                                <input type="radio" id="nav_sticky" name="navbarType" value="sticky"
                                    class="themeCustomization-checkInput">
                                <label for="nav_sticky" class="themeCustomization-checkInput-label ">Sticky</label>
                            </div>
                            <div class="input-group flex items-center">
                                <input type="radio" id="nav_static" name="navbarType" value="static"
                                    class="themeCustomization-checkInput">
                                <label for="nav_static" class="themeCustomization-checkInput-label ">Static</label>
                            </div>
                            <div class="input-group flex items-center">
                                <input type="radio" id="nav_hidden" name="navbarType" value="hidden"
                                    class="themeCustomization-checkInput">
                                <label for="nav_hidden" class="themeCustomization-checkInput-label ">Hidden</label>
                            </div>
                        </div>
                        <h3 class="mt-4">Footer Type</h3>
                        <div class="input-area flex items-center space-x-4 rtl:space-x-reverse">
                            <div class="input-group flex items-center">
                                <input type="radio" id="footer_sticky" name="footerType" value="sticky"
                                    class="themeCustomization-checkInput">
                                <label for="footer_sticky" class="themeCustomization-checkInput-label ">Sticky</label>
                            </div>
                            <div class="input-group flex items-center">
                                <input type="radio" id="footer_static" name="footerType" value="static"
                                    class="themeCustomization-checkInput">
                                <label for="footer_static" class="themeCustomization-checkInput-label ">Static</label>
                            </div>
                            <div class="input-group flex items-center">
                                <input type="radio" id="footer_hidden" name="footerType" value="hidden"
                                    class="themeCustomization-checkInput">
                                <label for="footer_hidden" class="themeCustomization-checkInput-label ">Hidden</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- END: Settings Modal -->
        <!-- END: Settings -->

        <!-- End: Settings -->
        <div class="flex flex-col justify-between min-h-screen">
            <div>
                <!-- BEGIN: Header -->
                <!-- BEGIN: Header -->
                <div class="z-[9]" id="app_header">
                    <div
                        class="app-header z-[999] ltr:ml-[248px] rtl:mr-[248px] bg-white dark:bg-slate-800 shadow-sm dark:shadow-slate-700">
                        <div class="flex justify-between items-center h-full">
                            <div
                                class="flex items-center md:space-x-4 space-x-2 xl:space-x-0 rtl:space-x-reverse vertical-box">
                                <a href="{{ url('/') }}" class="mobile-logo xl:hidden inline-block">
                                    <img src="{{ asset('assets/images/logo/logo-c.svg') }}" class="black_logo"
                                        alt="logo">
                                    <img src="{{ asset('assets/images/logo/logo-c-white.svg') }}" class="white_logo"
                                        alt="logoo">
                                </a>
                                <button class="smallDeviceMenuController hidden md:inline-block xl:hidden">
                                    <iconify-icon
                                        class="leading-none bg-transparent relative text-xl top-[2px] text-slate-900 dark:text-white"
                                        icon="heroicons-outline:menu-alt-3"></iconify-icon>
                                </button>
                           

                            </div>

                            @if (Auth::user()->is_admin || Auth::user()->is_finance || Auth::user()->is_finance_assistant)
                                <div class="text-right">
                                    <a href="/accounts/entries">
                                        <button
                                            class="btn btn-sm inline-flex justify-center btn-outline-light rounded-[25px]">Accounting
                                            APP</button>
                                    </a>
                                </div>
                            @endif

                            <!-- end vertcial -->
                            <div class="items-center space-x-4 rtl:space-x-reverse horizental-box">
                                <a href="{{ url('/') }}">
                                    <span class="xl:inline-block hidden">
                                        <img src="{{ asset('assets/images/logo/logo.svg') }}" class="black_logo "
                                            alt="logo">
                                        <img src="{{ asset('assets/images/logo/logo.svg') }}assets/images/logo/logo-white.svg"
                                            class="white_logo" alt="logo">
                                    </span>
                                    <span class="xl:hidden inline-block">
                                        <img src="{{ asset('assets/images/logo/logo-c.svg') }}" class="black_logo "
                                            alt="logo">
                                        <img src="{{ asset('assets/images/logo/logo-c-white.svg') }}"
                                            class="white_logo " alt="logo">
                                    </span>
                                </a>
                                <button
                                    class="smallDeviceMenuController  open-sdiebar-controller xl:hidden inline-block">
                                    <iconify-icon
                                        class="leading-none bg-transparent relative text-xl top-[2px] text-slate-900 dark:text-white"
                                        icon="heroicons-outline:menu-alt-3"></iconify-icon>
                                </button>

                            </div>
                            <!-- end horizental -->



                            <div class="main-menu active">
                                <ul>
                                    <li>
                                        <a href="{{ url('/') }}" class="navItem @yield('home')">
                                            <div class="flex space-x-2 items-start rtl:space-x-reverse ">
                                                <iconify-icon icon=ic:home class="leading-[1] text-base">
                                                </iconify-icon>
                                                <span class="leading-[1]">
                                                    Home
                                                </span>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ url('/calendar') }}" class="navItem @yield('calendar')">
                                            <div class="flex space-x-2 items-start rtl:space-x-reverse ">
                                                <iconify-icon icon=ic:calendar class="leading-[1] text-base">
                                                </iconify-icon>
                                                <span class="leading-[1]">
                                                    Calendar
                                                </span>
                                            </div>
                                        </a>
                                    </li>


                                    <li>
                                        <a href="{{ url('/tasks') }}" class="navItem @yield('tasks')">
                                            <div class="flex space-x-2 items-start rtl:space-x-reverse">
                                                <iconify-icon class="leading-[1] text-base"
                                                    icon="ic:round-add-task"></iconify-icon>
                                                <span class="leading-[1]">
                                                    Tasks
                                                </span>
                                            </div>
                                        </a>
                                    </li>

                                    <li class="menu-item-has-children">
                                        <!--  Single menu -->

                                        <!-- has dropdown -->



                                        <a href="javascript:void()">
                                            <div class="flex flex-1 items-center space-x-[6px] rtl:space-x-reverse">
                                                <span class="icon-box">
                                                    <iconify-icon icon=heroicons-outline:home> </iconify-icon>
                                                </span>
                                                <div class="text-box">Settings</div>
                                            </div>
                                            <div
                                                class="flex-none text-sm ltr:ml-3 rtl:mr-3 leading-[1] relative top-1">
                                                <iconify-icon icon="heroicons-outline:chevron-down"> </iconify-icon>
                                            </div>
                                        </a>

                                        <ul class="sub-menu">

                                            <li>
                                                <a href="{{ url('/policies') }}" class="navItem @yield('policies')">
                                                    <div class="flex space-x-2 items-start rtl:space-x-reverse">
                                                        <iconify-icon icon=heroicons:presentation-chart-line
                                                            class="leading-[1] text-base"> </iconify-icon>
                                                        <span class="leading-[1]">
                                                            Policies
                                                        </span>
                                                    </div>
                                                </a>
                                            </li>



                                            <li>
                                                <a href="{{ url('/cars') }}" class="navItem @yield('cars')">
                                                    <div class="flex space-x-2 items-start rtl:space-x-reverse">
                                                        <iconify-icon icon=heroicons:shopping-cart
                                                            class="leading-[1] text-base"> </iconify-icon>
                                                        <span class="leading-[1]">
                                                            Cars
                                                        </span>
                                                    </div>
                                                </a>
                                            </li>



                                            <li>
                                                <a href="{{ url('/companies') }}" class="navItem @yield('companies')">
                                                    <div class="flex space-x-2 items-start rtl:space-x-reverse">
                                                        <iconify-icon icon=heroicons:building-storefront
                                                            class="leading-[1] text-base"> </iconify-icon>
                                                        <span class="leading-[1]">
                                                            Companies
                                                        </span>
                                                    </div>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ url('/customers') }}" class="navItem @yield('customers')">
                                                    <div class="flex space-x-2 items-start rtl:space-x-reverse">
                                                        <iconify-icon icon=raphael:customer
                                                            class="leading-[1] text-base"> </iconify-icon>
                                                        <span class="leading-[1]">
                                                            Customers
                                                        </span>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>


                                    {{-- <li>
                                        <a href="{{url('/')}}">
                                            <div class="flex space-x-2 items-start rtl:space-x-reverse">
                                                <iconify-icon icon=raphael:customer class="leading-[1] text-base">
                                                </iconify-icon>
                                                <span class="leading-[1]">
                                                    Customers
                                                </span>
                                            </div>
                                        </a>
                                    </li> --}}



                                </ul>
                            </div>
                            <!-- end top menu -->
                            <div
                                class="nav-tools flex items-center lg:space-x-5 space-x-3 rtl:space-x-reverse leading-0">

                                <!-- BEGIN: Toggle Theme -->
                                <div>
                                    <button id="themeMood"
                                        class="h-[28px] w-[28px] lg:h-[32px] lg:w-[32px] lg:bg-gray-500-f7 bg-slate-50 dark:bg-slate-900 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center">
                                        <iconify-icon class="text-slate-800 dark:text-white text-xl dark:block hidden"
                                            id="moonIcon"
                                            icon="line-md:sunny-outline-to-moon-alt-loop-transition"></iconify-icon>
                                        <iconify-icon class="text-slate-800 dark:text-white text-xl dark:hidden block"
                                            id="sunIcon"
                                            icon="line-md:moon-filled-to-sunny-filled-loop-transition"></iconify-icon>
                                    </button>
                                </div>
                                <!-- END: TOggle Theme -->

                                <!-- BEGIN: gray-scale Dropdown -->
                                <div>
                                    <button id="grayScale"
                                        class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer
            rounded-full text-[20px] flex flex-col items-center justify-center">
                                        <iconify-icon class="text-slate-800 dark:text-white text-xl"
                                            icon="mdi:paint-outline"></iconify-icon>
                                    </button>
                                </div>
                                <!-- END: gray-scale Dropdown -->


                                <!-- BEGIN: gray-scale Dropdown -->
                                <div>
                                    <livewire:new-lead />
                                </div>

                                <div>
                                    <livewire:new-meeting />
                                </div>
                                <!-- END: gray-scale Dropdown -->


                                <!-- BEGIN: Notification Dropdown -->
                                @php
                                    $notfCount = auth()->user()->getUnseenNotfCount();
                                @endphp
                                <!-- Notifications Dropdown area -->
                                <div class="relative md:block hidden">
                                    <button
                                        class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <iconify-icon class="animate-tada text-slate-800 dark:text-white text-xl"
                                            icon="heroicons-outline:bell"></iconify-icon>
                                        @if ($notfCount)
                                            <span
                                                class="absolute -right-1 lg:top-0 -top-[6px] h-4 w-4 bg-red-500 text-[8px] font-semibold flex flex-col items-center justify-center rounded-full text-white z-[99]">
                                                {{ $notfCount }}</span>
                                        @endif
                                    </button>
                                    <!-- Notifications Dropdown -->
                                    <div class="dropdown-menu z-10 hidden bg-white shadow w-[335px] dark:bg-slate-800 border dark:border-slate-700 !top-[23px] rounded-md overflow-hidden lrt:origin-top-right rtl:origin-top-left"
                                        style="max-height: 390px; overflow: overlay;">
                                        <div class="flex items-center justify-between py-4 px-4">
                                            <h3 class="text-sm font-Inter font-medium text-slate-700 dark:text-white">
                                                Notifications</h3>
                                            <a class="text-xs font-Inter font-normal underline text-slate-500 dark:text-white"
                                                href="{{ url('/notifications') }}">See All</a>
                                        </div>
                                        @if (!auth()->user()->notifications->isEmpty())
                                            @foreach (auth()->user()->latest_notifications as $notification)
                                                {{-- BEGIN: ONE Notification --}}
                                                {{-- classes for unread notf. dark:bg-slate-700 dark:bg-opacity-70 text-slate-800 --}}

                                                <div
                                                    class="text-slate-600 dark:text-slate-300 block w-full px-4 py-2 text-sm">
                                                    <div class="flex ltr:text-left rtl:text-right relative">
                                                        <div class="flex-none ltr:mr-3 rtl:ml-3">
                                                            <div class="h-8 w-8 bg-white rounded-full">
                                                                <img src="{{ asset('assets/images/all-img/user3.png') }}"
                                                                    alt="user"
                                                                    class="border-transparent block w-full h-full object-cover rounded-full border">
                                                            </div>
                                                        </div>
                                                        <div class="flex-1">
                                                            <a href="{{ $notification->route }}"
                                                                class="text-slate-600 dark:text-slate-300 text-sm font-medium mb-1 before:w-full before:h-full before:absolute before:top-0 before:left-0">
                                                                @if (!$notification->is_seen)
                                                                    *
                                                                @endif
                                                                {{ $notification->title }}
                                                            </a>
                                                            <div
                                                                class="text-slate-600 dark:text-slate-300 text-xs leading-4">
                                                                {{ $notification->message }}
                                                            </div>
                                                            {{ $notification->created_at->diffForHumans() }}

                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- End: ONE Notification --}}
                                            @endforeach
                                        @else
                                            <div
                                                class="text-slate-600 dark:text-slate-300 block w-full px-4 py-2 text-sm">
                                                <div class="flex ltr:text-left rtl:text-right relative">
                                                    <div class="flex-none ltr:mr-3 rtl:ml-3">
                                                        <p>You have no notifications at the moment.</p>
                                                    </div>
                                                </div>
                                            </div>

                                        @endif

                                    </div>
                                </div>
                                <!-- END: Notification Dropdown -->

                                <!-- BEGIN: Profile Dropdown -->
                                <!-- Profile DropDown Area -->
                                <div class="md:block hidden w-full">
                                    <button
                                        class="text-slate-800 dark:text-white focus:ring-0 focus:outline-none font-medium rounded-lg text-sm text-center
      inline-flex items-center"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div
                                            class="lg:h-8 lg:w-8 h-7 w-7 rounded-full flex-1 ltr:mr-[10px] rtl:ml-[10px]">
                                            <span
                                                class="block w-full h-full object-cover text-center text-lg leading-8 user-initial">
                                                {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                                            </span>
                                        </div>
                                        <span
                                            class="flex-none text-slate-600 dark:text-white text-sm font-normal items-center lg:flex hidden overflow-hidden text-ellipsis whitespace-nowrap">{{ ucwords(Auth::user()->username) }}</span>
                                        <svg class="w-[16px] h-[16px] dark:text-white hidden lg:inline-block text-base inline-block ml-[10px] rtl:mr-[10px]"
                                            aria-hidden="true" fill="none" stroke="currentColor"
                                            viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <!-- Dropdown menu -->
                                    <div
                                        class="dropdown-menu z-10 hidden bg-white divide-y divide-slate-100 shadow w-44 dark:bg-slate-800 border dark:border-slate-700 !top-[23px] rounded-md
      overflow-hidden">
                                        <ul class="py-1 text-sm text-slate-800 dark:text-slate-200">
                                            <li>
                                                <a href="{{ url('/') }}"
                                                    class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white font-inter text-sm text-slate-600
            dark:text-white font-normal">
                                                    <iconify-icon icon="heroicons-outline:user"
                                                        class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                                    <span class="font-Inter">Dashboard</span>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ url('tasks/my') }}"
                                                    class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white font-inter text-sm text-slate-600
            dark:text-white font-normal">
                                                    <iconify-icon icon="heroicons-outline:clipboard-check"
                                                        class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                                    <span class="font-Inter">Tasks</span>
                                                </a>
                                            </li>


                                            <li>
                                                <a href="{{ url('profile') }}"
                                                    class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white font-inter text-sm text-slate-600
            dark:text-white font-normal">
                                                    <iconify-icon icon="iconamoon:profile-bold"
                                                        class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                                    <span class="font-Inter">Profile</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ url('logout') }}"
                                                    class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white font-inter text-sm text-slate-600
            dark:text-white font-normal">
                                                    <iconify-icon icon="heroicons-outline:login"
                                                        class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                                    <span class="font-Inter">Logout</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- END: Header -->
                                <button class="smallDeviceMenuController md:hidden block leading-0">
                                    <iconify-icon class="cursor-pointer text-slate-900 dark:text-white text-2xl"
                                        icon="heroicons-outline:menu-alt-3"></iconify-icon>
                                </button>
                                <!-- end mobile menu -->
                            </div>
                            <!-- end nav tools -->
                        </div>
                    </div>
                </div>

                <!-- BEGIN: Search Modal -->
                <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
                    id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
                    <div class="modal-dialog relative w-auto pointer-events-none top-1/4">
                        <div
                            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-slate-900 bg-clip-padding rounded-md outline-none text-current">
                            <form>
                                <div class="relative">
                                    <input type="text" class="form-control !py-3 !pr-12" placeholder="Search">
                                    <button
                                        class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l text-xl border-l-slate-200 dark:border-l-slate-600 dark:text-slate-300 flex items-center justify-center">
                                        <iconify-icon icon="heroicons-solid:search"></iconify-icon>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- END: Search Modal -->
                <!-- END: Header -->
                <!-- END: Header -->
                <div class="content-wrapper transition-all duration-150 ltr:ml-[248px] rtl:mr-[248px]"
                    id="content_wrapper">
                    <div class="page-content">
                        <div class="transition-all duration-150 container-fluid" id="page_layout">
                            <div id="content_layout">



                                <!-- The actual SIMPLE-TOAST  -->
                                <div id="simpleToast">

                                </div>

                                @yield('Breadcrumb')
                                <livewire:confirmation-modal />
                                <div class=" space-y-5">
                                    @yield('content')
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-white bg-no-repeat custom-dropshadow footer-bg dark:bg-slate-700 flex justify-around items-center
    backdrop-filter backdrop-blur-[40px] fixed left-0 bottom-0 w-full z-[9999] bothrefm-0 py-[12px] px-4 md:hidden">
                <a href="chat.html">
                    <div>
                        <span
                            class="relative cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mb-1 dark:text-white
          text-slate-900 ">
                            <iconify-icon icon="heroicons-outline:mail"></iconify-icon>
                            <span
                                class="absolute right-[5px] lg:hrefp-0 -hrefp-2 h-4 w-4 bg-red-500 text-[8px] font-semibold flex flex-col items-center
            justify-center rounded-full text-white z-[99]">
                                10
                            </span>
                        </span>
                        <span class="block text-[11px] text-slate-600 dark:text-slate-300">
                            Messages
                        </span>
                    </div>
                </a>
                <a href="profile.html"
                    class="relative bg-white bg-no-repeat backdrop-filter backdrop-blur-[40px] rounded-full footer-bg dark:bg-slate-700
      h-[65px] w-[65px] z-[-1] -mt-[40px] flex justify-center items-center">
                    <div class="h-[50px] w-[50px] rounded-full relative left-[0px] hrefp-[0px] custom-dropshadow">
                        <img src="{{ asset('assets/images/users/user-1.jpg') }}" alt=""
                            class="w-full h-full rounded-full border-2 border-slate-100">
                    </div>
                </a>
                <a href="{{ url('notifications') }}">
                    <div>
                        <span
                            class=" relative cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mb-1 dark:text-white
          text-slate-900">
                            <iconify-icon icon="heroicons-outline:bell"></iconify-icon>
                            <span
                                class="absolute right-[17px] lg:hrefp-0 -hrefp-2 h-4 w-4 bg-red-500 text-[8px] font-semibold flex flex-col items-center
            justify-center rounded-full text-white z-[99]">
                                2
                            </span>
                        </span>
                        <span class=" block text-[11px] text-slate-600 dark:text-slate-300">
                            Notifications
                        </span>
                    </div>
                </a>
            </div>
        </div>
    </main>
    <!-- scripts -->
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/rt-plugins.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

    @yield('body')

    <script>
        @if (session('alert_msg'))
            console.log("{{ session('alert_msg') }}")
            Swal.fire("{{ session('alert_msg') }}")
        @endif

        const setAsSeen = (id) => {
            $.ajax({
                url: "{{ url('notifications/seen/') }}" + "/" + id,
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                }
            })
        }
    </script>

    @yield('child_scripts')

    @livewireScripts
</body>

</html>
