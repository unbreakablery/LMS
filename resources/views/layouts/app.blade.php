<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Lab Management System</title>
    <link rel="shortcut icon" type="image/png/ico" href="{{ asset('/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('/favicon192.ico') }}" />
    <!-- Fonts -->
    <!-- <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href='https://fonts.googleapis.com/css?family=Titillium Web' rel='stylesheet'> -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@900&display=swap" rel="stylesheet"> -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,900;1,700&display=swap" rel="stylesheet"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/fixedheader/3.1.8/css/fixedHeader.dataTables.min.css" rel="stylesheet"> -->
    <link href="{{ asset('css/bootstrap-datepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fixedHeader.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.css') }}" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css"> -->
    <!-- <link href="{{ asset('css/bootstrap-select.min.css') }}" rel="stylesheet"> -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    @if (Route::currentRouteName() == 'settings')
    <link href="{{ asset('css/setting.css') }}" rel="stylesheet">
    @endif
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
</head>
<body class="bg-ec">
    <div id="page-loader"></div>
    <div id="app">
        @if (Route::currentRouteName() != 'login')
        <nav class="navbar navbar-expand-md navbar-light shadow-sm top-layer border-bottom">
            <div class="container no-max-width">
                <a class="navbar-brand text-white logo-img d-flex" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="" class="login-logo" height='40'>
                    <h3 class="d-flex align-items-center mb-0 ml-4">Lab Management System</h3>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <img src="{{ asset('images/avatar.png') }}" alt="Settings" style="height: 25px; width: 25px;">
                                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} ({{ getUserRoleName(Auth::user()->role) }})
                                </a>

                                <div class="dropdown-menu dropdown-menu-right bg-dropdown-menu border-white n-b-r" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item text-white" href="{{ route('settings') }}">
                                        {{ __('Settings') }}
                                    </a>
                                    @can('manage-user')
                                    <a class="dropdown-item text-white" href="{{ route('users') }}">
                                        {{ __('User Management') }}
                                    </a>
                                    @endcan
                                    <a class="dropdown-item text-white" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @endif

        <main class="py-4">
            @yield('content')
        </main>
        
        <footer class="footer">
            <p class="my-0">LMS 1.0 &copy; {{ date("Y") }} Benaris Hajduk</p>
        </footer>
        <!-- Message box -->
        <div class="position-fixed bottom-0 right-0 p-3" style="z-index: 99999; left: 50%; top: 0; transform: translateX(-50%);">
            <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
                <div class="toast-header bg-success text-white">
                    <strong class="mr-auto">Message</strong>
                    <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body bg-white text-secondary">
                    Hello, world! This is a toast message.
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script> -->
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.js"></script> -->
    
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script> -->
    <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <!-- <script src="https://cdn.datatables.net/fixedheader/3.1.8/js/dataTables.fixedHeader.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.24/sorting/date-eu.js"></script> -->
    <script src="{{ asset('js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('js/date-eu.js') }}"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script> -->
    <!-- <script src="{{ asset('js/bootstrap-select.min.js') }}"></script> -->

    <script>
        var host = "http://local.lab-management.com/lab-management/public";
        function loader($mode) {
            var $lpageLoader = $('#page-loader');
            var $lBody = $('body');

            if ($mode === 'show') {
                if ($lpageLoader.length) {
                    $lpageLoader.fadeIn(250);
                } else {
                    $lBody.prepend('<div id="page-loader"></div>');
                }
            } else if ($mode === 'hide') {
                if ($lpageLoader.length) {
                    $lpageLoader.fadeOut(250);
                }
            }

            return false;
        }
        function showMessage(type, message) {
            var rClass = '';
            var aClass = '';
            if (type == 'success') {
                rClass = 'bg-warning bg-danger';
                aClass = 'bg-success';
            } else if (type == 'warning') {
                rClass = 'bg-success bg-danger';
                aClass = 'bg-warning';
            } else if (type == 'danger') {
                rClass = 'bg-success bg-warning';
                aClass = 'bg-danger';
            }

            $('.toast .toast-header').removeClass(rClass);
            $('.toast .toast-header').addClass(aClass);
            $('.toast .toast-body').html(message);
            $('.toast').toast('show');
        }
        function empty(val) {
            if (val == undefined || val == null || val == 0 || val == '' || val == false) {
                return true;
            }
            return false;
        }
        $(document).ready(function() {
            $.noConflict();
            loader('hide');
        });
    </script>

    @if (Route::currentRouteName() == 'settings')
        <script src="{{ asset('js/setting.js') }}"></script>
    @endif

    @if (Route::currentRouteName() == 'users')
        <script src="{{ asset('js/users.js') }}"></script>
    @endif

    @if (Route::currentRouteName() == 'category.list')
        <script src="{{ asset('js/category.js') }}"></script>
    @endif

    @if (Route::currentRouteName() == 'equipment.manage.list' || Route::currentRouteName() == 'equipment.list')
        <script src="{{ asset('js/equipment.js') }}"></script>
    @endif

    @if (Route::currentRouteName() == 'booking.manage.list' || Route::currentRouteName() == 'booking.list')
        <script src="{{ asset('js/booking.js') }}"></script>
    @endif

    @if (Route::currentRouteName() == 'tracking.list')
        <script src="{{ asset('js/tracking.js') }}"></script>
    @endif
</body>
</html>
