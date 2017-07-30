<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta-tags')
    <link rel="shortcut icon" href="{{ asset('images/logos/FAVICON.ico') }}" />
    <title>@yield('title')</title>

    {{--Styles--}}
    <link rel="stylesheet" href="{{ asset('css/styles/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles/media.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles/classes.css') }}">
    <link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    @section('style')
    @show
    @yield('adm-page-style')

    {{--Scripts--}}

    @section('script-app')
        <script src="{{ asset('/js/app.js') }}"></script>
    @show

    <script src="{{ asset('/js/alert-block.js') }}"></script>
    @yield('script')
    @yield('adm-page-script')
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
<div class="body">

    <div class="notify-top-popup"></div>

    @section('navbar')
        <div class="nav-above">
        </div>
        <nav class="nav-home flex">
            <div class="nav-logo">
                <img src="/images/logos/logo-small.png">
            </div>
            <ul id="nav_menu" class="flex">
                <li class="hvr-float-shadow li_home">
                    <a href="#prices">
                        Цены
                    </a>
                </li>
                <li class="hvr-float-shadow li_home">
                    <a href="#map">
                        Местоположения
                    </a>
                </li>
                <li class="hvr-float-shadow li_home">
                    <a href="#contacts">
                        Контакты
                    </a>
                </li>
            </ul>
            @if(Auth::check())
                <ul id="adm-log" class="flex">
                    <li class="hvr-underline-from-center">
                        <a href="{{ route('servicesAndDevices') }}">
                            <i class="fa fa-cogs" aria-hidden="true"></i>
                            На админ панель
                        </a>
                    </li>
                    <li></li>
                    <li class="hvr-underline-from-center">
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out" aria-hidden="true"></i>
                            Выйти
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            @endif
        </nav>
    @show

    @yield('content')

</div>
</body>
</html>
