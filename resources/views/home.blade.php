@extends('layouts.parent')

@section('title')
    Home
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/styles/home.css') }}">
@endsection

@section('script')
    <script src="{{ asset('/js/home.js') }}"></script>
    @if(Auth::guest())
        <script src="{{ asset('/js/home-chat.js') }}"></script>
    @endif
@endsection

@section('content')
    @if(Auth::guest())
        <div id="chat" class="flex">
            <p class="toggle-chat">Чат</p>
            <div class="chat-container">
                <div id="view-messages" class="scrollbar"></div>
                <div id="send-messages" class="flex">
                    <textarea class="form-control" placeholder="Задать вопрос..." id="message"></textarea>
                    <div class="chat-buttons flex">
                        <label id="image_file_label" class="btn btn-default btn-file">
                            Добавить изображение
                            {{--<i class="fa fa-camera" aria-hidden="true"></i>--}}
                            <input type="file" name="image" id="image_file" style="display: none" accept=".jpg,.png">
                        </label>
                        {{--<button id="reset-img" class="btn btn-warning">--}}
                            {{--<i class="fa fa-ban" aria-hidden="true"></i>--}}
                        {{--</button>--}}
                        <button class="btn btn-info" id="submit-send-message">Отправить</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="question">
            <p>Есть вопрос?</p>
        </div>

        <div class="modal fade" id="sendMailModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="email">Ваш email</label>
                            <input type="email" class="form-control" id="email" placeholder="Введите адрес электронной почты.">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Ваш вопрос</label>
                            <textarea class="form-control" id="email-text" placeholder="Введите свой вопрос."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <label id="email-attach-file" class="btn btn-info btn-file">
                            <i class="fa fa-camera" aria-hidden="true"></i>
                            <input type="file" id="email-attach-file_input" style="display: none" accept=".jpg,.png">
                        </label>
                        <button type="button" id="send-mail" class="btn btn-success">Отправить</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    </div>
                </div>

            </div>
        </div>
    @endif
    <div id="home-content">
        <div id="prices" class="">
            <h1>Prices</h1>
        </div>
        <div id="contacts" class="">
            <h1>Contacts</h1>
        </div>
        <div id="reviews" class="">
            <h1>Reviews</h1>
        </div>
        <div id="map_parent" class="flex">
            <div id="map">
                {!! Mapper::render() !!}
            </div>
        </div>
    </div>
    <button id="go_top" type="button" class="btn btn-info bounce">
        <i class="fa fa-arrow-circle-up fa-2x" aria-hidden="true"></i>
    </button>
@endsection