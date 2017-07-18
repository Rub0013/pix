@extends('layouts.admin')

@section('adm-page-style')
    <link rel="stylesheet" href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css">
@endsection

@section('adm-page-script')
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
@endsection

@section('title')
    Admin-prices
@endsection

@section('content')
    <div class="main-offers flex">
        <div class="add-block-parent">
            <div class="add-offers">
                <p>Добавить предложение</p>
                <div class="offers-validation-errors"></div>
                <div class="form-group offers-description-block">
                    <label for="offers-description-input">Описание</label>
                    <input class="form-control" id="offers-description-input" type="text" placeholder="Введите описание.">
                </div>
                <div class="form-groups-block flex">
                    <div class="form-group offers-status-block">
                        <select id="select-offer-status" class="form-control">
                            <option value="">Выберите статус</option>
                            <option value="0">Выключен</option>
                            <option value="1">Включен</option>
                        </select>
                    </div>
                    <div class="form-group offers-image-block">
                        <label id="offers-image_label" class="btn btn-default btn-file">
                            Загрузить изображение
                            <input type="file" name="image" id="offers-image-input" style="display: none">
                        </label>
                    </div>
                </div>
                <div class="form-group add-offer-btn-block flex">
                    <button id="add-offer-btn" class="btn btn-primary">Добавить</button>
                </div>
            </div>
        </div>
        <div class="added-offers">
            <p>Все предложения</p>
            @if(count($offers) > 0)
                <div class="panel-group scrollbar" id="added-offers-panel" role="tablist" aria-multiselectable="true">
                    @foreach($offers as $offer)
                        <div id="offer_{{$offer->id}}" class="offer-container flex">
                            <div class="offer-data">
                                <p>
                                    <span>Статус - </span>
                                    @if($offer->active)
                                        <span class="current-offer-status status-active">Включен</span>
                                    @else
                                        <span class="current-offer-status">Выключен</span>
                                    @endif
                                </p>
                                <div class="offer-data-buttons flex">
                                    <div class="change-offer-status">
                                        <input @if($offer->active) checked @endif class="offer-status-change" type="checkbox" data-toggle="toggle" data-off='Включить' data-on='Выключить' data-onstyle="danger" data-offstyle="primary">
                                    </div>
                                    <input type="hidden" value="{{$offer->id}}">
                                    <button class="btn btn-danger delete-offer">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="offer-image">
                                <img src='/images/offers/{{$offer->image}}'>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <script src="{{ asset('/js/admin/offers.js') }}"></script>
@endsection
