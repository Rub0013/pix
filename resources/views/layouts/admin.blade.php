@extends('layouts.parent')

@section('main-style')
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/styles/admin-panel.css') }}">
@endsection

@section('script')
    <script src="{{ asset('/js/admin-chat.js') }}"></script>
@endsection

@section('navbar')
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span id="unseen-dot"></span>
                </button>
                <a class="navbar-brand" href="#">Brand</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li  {!! (Route::is('panel') ? 'class="active"' : '') !!}>
                        <a href="{{ route('panel') }}">Панель</a>
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

@section('chat')
@endsection

@section('content')
@endsection
