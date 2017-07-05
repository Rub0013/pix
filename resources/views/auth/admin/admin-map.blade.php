@extends('layouts.admin')

@section('title')
    Admin-map
@endsection

@section('content')
    <div class="main-map  flex">
        <div class="add-branch">
            <p>Добавить филиал</p>
            <div class="branches-validation-errors"></div>
            <div class="form-group">
                <label for="branch-latitude">Широта</label>
                <input class="form-control" id="branch-latitude" type="text" placeholder="Введите широту.">
            </div>
            <div class="form-group">
                <label for="branch-longitude">Долгота</label>
                <input class="form-control" id="branch-longitude" type="text" placeholder="Введите долготу.">
            </div>
            <div class="form-group">
                <label for="branch-title">Название</label>
                <input class="form-control" id="branch-title" type="text" placeholder="Введите название филиала.">
            </div>
            <div class="form-group">
                <label for="branch-address">Адрес</label>
                <input class="form-control" id="branch-address" type="text" placeholder="Введите адрес филиала.">
            </div>
            <div class="form-group add-product-btn-block flex">
                <button id="add-branch-btn" class="btn btn-primary">Добавить</button>
            </div>
        </div>
        <div class="added-branches">
            <p>Все филиалы</p>
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
    <script>
        $(document).ready(function(){
            function resetForm() {
                $('#branch-latitude').val('');
                $('#branch-longitude').val('');
                $('#branch-title').val('');
                $('#branch-address').val('');
            }
            function showValidationErrors(message, block) {
                var errorBlock = $('.' + block + '-validation-errors');
                errorBlock.empty();
                $('<div class="alert alert-danger" >' + message + '</div>').prependTo(errorBlock).delay(3000).slideUp(1000, function () {
                    errorBlock.empty();
                });
            }
            $(document).on( "click", "#add-branch-btn", function() {
                var latitudeInput = $('#branch-latitude');
                var longitudeInput = $('#branch-longitude');
                var titleInput = $('#branch-title');
                var addressInput = $('#branch-address');
                var latitude = latitudeInput.val();
                var longitude = longitudeInput.val();
                var title = titleInput.val();
                var address = addressInput.val();
                if(latitude.length > 0 && longitude.length > 0 && title.length > 0 && address.length > 0) {
                    $.ajax({
                        type: 'post',
                        url: 'add_branch',
                        data: {
                            latitude: latitude,
                            longitude: longitude,
                            title: title,
                            address: address
                        },
                        dataType: "json",
                        cache: false,
                        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                        success: function (answer) {
                            console.log(answer);
                            if(answer.validationError) {
                                showValidationErrors(answer.message, 'branches');
                            } else {
                                showResponse(answer);
                                if(answer.success) {
//                                    var currentDeviceBox = $('#device_' + device + ' .panel-body');
//                                    var EmptyBox = $('#device_' + device + ' .panel-body > h4');
//                                    if(EmptyBox.length > 0) {
//                                        EmptyBox.remove();
//                                    }
//                                    currentDeviceBox.append("<div class='product-service flex ps_" + answer.newServiceProduct.id + "'>" +
//                                        "<div class='product-service-data flex'>" +
//                                        "<p class='align-center'>" + answer.newServiceProduct.description + "</p>" +
//                                        "<b class='align-center'>" +
//                                        "<span class='priceSpan'>" + answer.newServiceProduct.price + "</span>" +
//                                        "<i class='fa fa-rub' aria-hidden='true'></i>" +
//                                        "</b>" +
//                                        "</div>" +
//                                        "<div class='product-price-buttons flex'>" +
//                                        "<button class='btn btn-info btn-sm change-price'>Изменить цену</button>" +
//                                        "<input type='hidden' value='" + answer.newServiceProduct.id + "'>" +
//                                        "<button class='btn btn-danger btn-sm delete-product'>Удалить</button>" +
//                                        "</div>" +
//                                        "</div>");
                                    resetForm();
                                }
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
