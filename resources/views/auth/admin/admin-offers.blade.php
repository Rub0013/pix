@extends('layouts.admin')

@section('title')
    Admin-prices
@endsection

@section('content')
    <div class="main-offers flex">
        <div class="add-offers">
            <p>Добавить предложение</p>
            <div class="offers-validation-errors"></div>
            <div class="form-group offers-description-block">
                <label for="offers-description-input">Описание</label>
                <input class="form-control" id="offers-description-input" type="text" placeholder="Введите описание.">
            </div>
            <div class="form-groups-block flex">
                <div class="form-group offers-image-block">
                    <label for="offers-image_label">Загрузить изображение</label>
                    <label id="offers-image_label" class="btn btn-default btn-file">
                        Добавить изображение
                        <input type="file" name="image" id="offers-image-input" style="display: none">
                    </label>
                </div>
                <div class="form-group offers-image-block">
                    <label for="select-offer-status">Статус</label>
                    <select id="select-offer-status" class="form-control">
                        <option value="0">Выключен</option>
                        <option value="1">Включен</option>
                    </select>
                </div>
            </div>
            <div class="form-group add-offer-btn-block flex">
                <button id="add-offer-btn" class="btn btn-primary">Добавить</button>
            </div>
        </div>
        <div class="added-products">
            <p>Все услуги</p>
            {{--@if(count($devices) > 0)--}}
                {{--<div class="panel-group scrollbar" id="added-products-panel" role="tablist" aria-multiselectable="true">--}}
                    {{--@foreach($devices as $product)--}}
                        {{--<div id="device_{{$product->id}}" class="panel">--}}
                            {{--<div class="panel-heading" role="tab" id="heading_{{$product->id}}">--}}
                                {{--<h4 class="panel-title">--}}
                                    {{--<a role="button" data-toggle="collapse" data-parent="#added-products-panel" href="#collapse_{{$product->id}}" aria-expanded="false" aria-controls="collapse_{{$product->id}}">--}}
                                        {{--{{$product->model}}--}}
                                    {{--</a>--}}
                                {{--</h4>--}}
                            {{--</div>--}}
                            {{--<div id="collapse_{{$product->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_{{$product->id}}">--}}
                                {{--<div class="panel-body">--}}
                                    {{--@if(count($product->prices) > 0)--}}
                                        {{--@foreach($product->prices as $productPrice)--}}
                                            {{--<div class="product-service flex ps_{{$productPrice->id}}">--}}
                                                {{--<div class="product-service-data flex">--}}
                                                    {{--<p class="align-center">{{$productPrice->service['description']}}</p>--}}
                                                    {{--<b class="align-center">--}}
                                                        {{--<span class="priceSpan">{{$productPrice->price}}</span>--}}
                                                        {{--<i class="fa fa-rub" aria-hidden="true"></i>--}}
                                                    {{--</b>--}}
                                                {{--</div>--}}
                                                {{--<div class="product-price-buttons flex">--}}
                                                    {{--<button class="btn btn-info btn-sm change-price">Изменить цену</button>--}}
                                                    {{--<input type="hidden" value="{{$productPrice->id}}">--}}
                                                    {{--<button class="btn btn-danger btn-sm delete-product">Удалить</button>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--@endforeach--}}
                                    {{--@else--}}
                                        {{--<h4>Нет зарегистрированных услуг</h4>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--@endforeach--}}
                {{--</div>--}}
            {{--@endif--}}
        </div>
    </div>
@endsection
