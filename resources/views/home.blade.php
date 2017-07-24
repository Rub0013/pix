@extends('layouts.parent')

@section('title')
    Home
@endsection

@section('meta-tags')
    <meta name="description" content="Метатеги в Википедии" />
    <meta name="keywords" content="Википедия, Метатег, статья" />
@show

@section('style')
    <link rel="stylesheet" href="{{ asset('css/styles/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hover-master/hover-min.css') }}">
@endsection

@section('script-app')
@endsection

@section('script')
    <script src="{{ asset('/js/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('/js/datetimepicker/bootstrap.min.js') }}"></script>
    @if(Auth::guest())
        <script src="{{ asset('/js/datetimepicker/moment-with-locales.min.js') }}"></script>
        <script src="{{ asset('/js/datetimepicker/bootstrap-datetimepicker-4.17.47.min.js') }}"></script>
        <script src="{{ asset('/js/home-chat.js') }}"></script>
    @endif
    <script src="{{ asset('/js/home.js') }}"></script>
@endsection

@section('content')
    @if(Auth::guest())
        <div id="chat" class="flex">
            <p class="toggle-chat hide-block">
                <img src="images/icons/chat-1.png">
                Онлайн консультация
            </p>
            <div class="chat-container">
                <div id="view-messages" class="scrollbar">
                    <div id="helloFromAdmin">
                        <div class="flex">
                            <div>
                                <img src='images/icons/technical-support (1).png'>
                            </div>
                            <div>
                                <p>Онлайн</p>
                                <p>Оператор</p>
                            </div>
                        </div>
                        <p>Здравствуйте! Чем могу помочь?</p>
                    </div>
                </div>
                <div id="send-messages" class="flex">
                    <textarea class="form-control" placeholder="Задать вопрос..." id="message"></textarea>
                    <div class="chat-buttons flex">
                        <label id="image_file_label" class="btn btn-default btn-file">
                            Добавить изображение
                            <input type="file" name="image" id="image_file" style="display: none" accept=".jpg,.png">
                        </label>
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
                        <div id="error-box" class="alert alert-danger"></div>
                        <div class="form-group">
                            <label for="user-name">Ваше имя</label>
                            <input type="text" class="form-control" id="user-name" placeholder="Введите Ваше имя...">
                        </div>
                        <div class="form-group">
                            <label for="user-email">Ваш email</label>
                            <input type="email" class="form-control" id="user-email" placeholder="Введите Ваш адрес электронной почты...">
                        </div>
                        <div class="form-group">
                            <label for="email-text">Ваш вопрос</label>
                            <textarea class="form-control" id="email-text" placeholder="Введите свой вопрос..."></textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseCallBack" aria-expanded="false" aria-controls="collapseExample">
                                Хотите, мы перезвоним Вам?
                            </button>
                            <div class="collapse" id="collapseCallBack">
                                <div class="well checkbox-input-container-parent">
                                    <div class="checkbox-input-labels-container">
                                        <div class="checkbox flex checkbox-container">
                                            <label class="checkbox-input-label">
                                                <input id="mobile-number-checkbox" type="checkbox" checked="checked">
                                            </label>
                                            <div class="contact-box flex">
                                                <input type="text" class="form-control" id="mobile-number" placeholder="Ваш номер мобильного телефона...">
                                                <img src="{{ asset('images/icons/phone.png') }}">
                                            </div>
                                        </div>
                                        <div class="checkbox flex checkbox-container">
                                            <label class="checkbox-input-label">
                                                <input id="viber-checkbox" type="checkbox">
                                            </label>
                                            <div class="contact-box flex">
                                                <input type="text" class="form-control" id="viber-number" placeholder="Ваш номер Viber..." disabled >
                                                <img src="{{ asset('images/icons/viber-logo-round.png') }}">
                                            </div>
                                        </div>
                                        <div class="checkbox flex checkbox-container">
                                            <label class="checkbox-input-label">
                                                <input id="whatsapp-checkbox" type="checkbox">
                                            </label>
                                            <div class="contact-box flex">
                                                <input type="text" class="form-control" id="whatsapp-number" placeholder="Ваш номер WhatsApp..." disabled>
                                                <img src="{{ asset('images/icons/whatsapp-logo-round.png') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="choose-time">
                                        <p>Выберите время</p>
                                        <div class="flex justify-center">
                                            <div class="container">
                                                <div class="row flex justify-center">
                                                    <div class='col-sm-6'>
                                                        <div class="form-group">
                                                            <div class='input-group date' id='datetimepicker1'>
                                                                <input id="callTime" type='text' class="form-control" />
                                                                <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer flex footer-buttons">
                        <div>
                            <label data-toggle="tooltip" data-placement="left" title="Прикрепить файл" id="email-attach-file" class="btn btn-file btn-default">
                                <img src="{{ asset('images/icons/attach.png') }}">
                                <input type="file" id="email-attach-file_input" style="display: none">
                            </label>
                        </div>
                        <button type="button" id="send-mail" data-loading-text="Отправка..." class="btn btn-success" autocomplete="off">
                            Отправить
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    </div>
                </div>

            </div>
        </div>
    @endif
    <div id="home-content">
        @if(count($offers) > 0)
            <div id="best-offers" class="">
                <div class="container">
                    <div id="OffersCarousel" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            @foreach($offers as $key => $offer)
                                <li data-target="#OffersCarousel" data-slide-to="{{$key}}" @if($key == 0) class="active" @endif></li>
                            @endforeach
                        </ol>
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">
                            @foreach($offers as $key => $offer)
                                <div class="item @if($key == 0) active @endif offer-item">
                                    <img src="/images/offers/{{$offer->image}}" alt="{{$offer->description}}" style="height:50%; margin: 0 auto">
                                </div>
                            @endforeach
                        </div>
                        <a class="left carousel-control" href="#OffersCarousel" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#OffersCarousel" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                            <span class="sr-only">Next</span>
                        </a>
                        <div id="carouselButtons">
                            <button id="playButton" type="button" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-play"></span>
                            </button>
                            <button id="pauseButton" type="button" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-pause"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div id="prices">
            <p class="text-center price-header">Выберите модель своего телефона и ознакомьтесь с ценамию</p>
            <div class="flex">
            @if(count($devices) > 0)
                <ul class="devices-list flex">
                    @foreach($devices as $key => $device)
                        @if($key == 0)
                        <li class="hvr-forward device_{{$device->id}} active-device">
                        @else
                        <li class="hvr-forward device_{{$device->id}}">
                        @endif
                            <span>{{$device->model}}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="price-list-container">
                    @foreach($devices as $key => $device)
                        @if($key == 0)
                        <div id="device_{{$device->id}}" class="price-list active-price-list">
                        @else
                        <div id="device_{{$device->id}}" class="price-list">
                        @endif
                            <p class="text-center device-model">{{$device->model}}</p>
                            <div class="scroll-removable">
                                @if(count($device->prices) > 0)
                                    @foreach($device->prices as $priceList)
                                        <div class="service-price flex">
                                            <p>{{$priceList->service['description']}}</p>
                                            <p>
                                                <span>{{$priceList->price}}</span>
                                                <i class="fa fa-rub" aria-hidden="true"></i>
                                            </p>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="no-priceList text-center">
                                        Нет добавленного прайс-листа.
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            </div>
            <p class="text-center price-footer">Не нашли проблему с вашим устройством в списке? <span>Свяжитесь с нами.</span></p>
        </div>
        <div id="map-parent" class="flex">
            <div id="map">
                {!! Mapper::render() !!}
            </div>
        </div>
        <div id="contacts" class="flex">
            <h2 class="text-center">Контакты</h2>
            <h3 class="text-center">Если у вас есть какие-либо вопросы, звоните или пишите нам.</h3>
            <div class="contacts-block flex">
                <div class="contact-phone-numbers">
                    <div class="contacts-icon">
                        <img src="{{ asset('images/icons/contacts-phone.png') }}">
                    </div>
                    @if(count($contacts) > 0)
                        @foreach($contacts as $contact)
                            @if($contact->phone)
                                <div class="one-contact">
                                    <p class="text-center">{{$contact->phone}}</p>
                                </div>
                            @endif()
                        @endforeach
                    @endif
                </div>
                <div class="contact-emails">
                    <div class="contacts-icon">
                        <img src="{{ asset('images/icons/contacts-email.png') }}">
                    </div>
                    @if(count($contacts) > 0)
                        @foreach($contacts as $contact)
                            @if($contact->email)
                                <p class="one-contact text-center">{{$contact->email}}</p>
                            @endif()
                        @endforeach
                    @endif
                </div>
            </div>
            <p class="text-center copyright">Copyright &#169 2017 Бородатый Мастер. Все права защищены.</p>
        </div>
    </div>
    <button id="go-top" type="button" class="btn btn-info bounce">
        <i class="fa fa-arrow-circle-up fa-2x" aria-hidden="true"></i>
    </button>
@endsection