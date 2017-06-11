<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Метатеги в Википедии" />
    <meta name="keywords" content="Википедия, Метатег, статья" />
    <link rel="shortcut icon" href="{{ asset('images/logos/FAVICON.ico') }}" />
    <title>@yield('title')</title>

    {{--Styles--}}
    @section('main-style')
        <link rel="stylesheet" href="{{ asset('css/styles/main.css') }}">
        <link rel="stylesheet" href="{{ asset('css/styles/media.css') }}">
    @show
    <link rel="stylesheet" href="{{ asset('css/styles/classes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hover-master/hover-min.css') }}">
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    @yield('style')

    {{--Scripts--}}

    @section('script-app')
        <script src="{{ asset('/js/app.js') }}"></script>
    @show

    @yield('script')
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
        <nav>
            <ul id="nav_menu" class="flex">
                <li class="hvr-float-shadow li_home">
                    <a href="#prices">
                        <i class="fa fa-money fa-lg" aria-hidden="true"></i>
                        Цены
                    </a>
                </li>
                <li class="hvr-float-shadow li_home">
                    <a href="#contacts">
                        <i class="fa fa-phone fa-lg" aria-hidden="true"></i>
                        Контакты
                    </a>
                </li>
                <li class="hvr-float-shadow li_home">
                    <a href="#reviews">
                        <i class="fa fa-comments-o fa-lg" aria-hidden="true"></i>
                        Отзывы
                    </a>
                </li>
                <li class="hvr-float-shadow li_home">
                    <a href="#map">
                        <i class="fa fa-map-marker fa-lg" aria-hidden="true"></i>
                        Местоположения
                    </a>
                </li>
            </ul>
            @if(Auth::check())
                <ul id="adm-log" class="flex">
                    <li class="hvr-underline-from-center">
                        <a href="{{ route('panel') }}">
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
