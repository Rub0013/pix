@extends('layouts.parent')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/styles/admin-panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles/nav-bar.css') }}">
@endsection

@section('script')
    <script src="{{ asset('/js/admin/chat.js') }}"></script>
@endsection

@section('navbar')
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span id="unseen-dot"></span>
                </button>
                <div class="navbar-brand admin-logo-parent flex">
                    <div class="admin-logo-container align-center">
                        <img class="admin-logo" src="{{ asset('images/logos/logo_admin_panel.png') }}">
                    </div>
                </div>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li  {!! (Route::is('servicesAndDevices') ? 'class="active"' : '') !!}>
                        <a href="{{ route('servicesAndDevices') }}">Устройства и сервисы</a>
                    </li>
                    <li  {!! (Route::is('prices') ? 'class="active"' : '') !!}>
                        <a href="{{ route('prices') }}">Услуги</a>
                    </li>
                    <li  {!! (Route::is('map') ? 'class="active"' : '') !!}>
                        <a href="{{ route('map') }}">Карта</a>
                    </li>
                    <li  {!! (Route::is('chat') ? 'class="active"' : '') !!} id="messages-all">
                        <a href="{{ route('chat') }}">Сообщения</a>
                        <p id="unseen">0</p>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user-circle" aria-hidden="true"></i>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                    <a href="{{ route('home') }}">
                                        <i class="fa fa-home" aria-hidden="true"></i>
                                        На главную
                                    </a>
                                </li>
                            <li role="separator" class="divider"></li>
                            <li>
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
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endsection

