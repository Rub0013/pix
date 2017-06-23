@extends('layouts.admin')

@section('title')
    Admin-prices
@endsection

@section('content')
    <div class="main-prices flex">
        <div class="add-price">
            <h2>Добавить услугу</h2>
            <div class="form-group choose-device-block">
                <label for="select-device">Устройства</label>
                <select id="select-device" class="form-control">
                    <option value="" selected disabled>Выберите устройство</option>
                    @foreach($devices as $device)
                        <option value="{{$device['id']}}">{{$device['model']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group choose-service-block">
                <label for="select-service">Сервисы</label>
                <select id="select-service" class="form-control">
                    <option value="" selected disabled>Выберите сервис</option>
                    @foreach($services as $service)
                        <option value="{{$service['id']}}">{{$service['description']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group choose-price-block">
                <label for="product-price">Цена услуги</label>
                <input class="form-control" id="product-price" type="text" placeholder="Введите цену услуги.">
            </div>
            <div class="form-group add-product-btn-block flex">
                <button id="add-product-btn" class="btn btn-primary">Добавить</button>
            </div>
        </div>
        <div class="added-products">
            @if(count($devices) > 0)
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    @foreach($devices as $product)
                        <div id="device_{{$product->id}}" class="panel panel-primary">
                            <div class="panel-heading" role="tab" id="heading_{{$product->id}}">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{$product->id}}" aria-expanded="false" aria-controls="collapse_{{$product->id}}">
                                        {{$product->model}}
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse_{{$product->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_{{$product->id}}">
                                <div class="panel-body">
                                    @if(count($product->prices) > 0)
                                        @foreach($product->prices as $productPrice)
                                            <div class="product-service flex">
                                                <div class="product-service-data flex">
                                                    <p class="align-center">{{$productPrice->service['description']}}</p>
                                                    <b class="align-center">{{$productPrice->price}} <i class="fa fa-rub" aria-hidden="true"></i></b>
                                                </div>
                                                <div class="product-price-buttons">
                                                    <button class="btn btn-info btn-sm change-price">Изменить цену</button>
                                                    <input type="hidden" value="{{$productPrice->id}}">
                                                    <button class="btn btn-danger btn-sm delete-product">Удалить</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <h3>Нет зарегистрированных услуг</h3>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <script>
        $(document).ready(function(){

            $(document).on( "click", "#add-product-btn", function() {
                var deviceSelect = $('#select-device');
                var serviceSelect = $('#select-service');
                var priceInput = $('#product-price');
                var device = deviceSelect.val();
                var service = serviceSelect.val();
                var price = priceInput.val();
                if(price.length > 0 && service && device) {
                    $.ajax({
                        type: 'post',
                        url: 'add_service_product',
                        data: {
                            device: device,
                            service: service,
                            price: price,
                        },
                        dataType: "json",
                        cache: false,
                        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                        success: function (answer) {
                            console.log(answer);
                            showResponse(answer);
                        }
                    });
                }
            });
            $(document).on( "click", ".delete-product", function() {
                var productId = $(this).prev().val();
                $.ajax({
                    type: 'post',
                    url: 'delete_service_product',
                    data: {
                        productId: productId
                    },
                    dataType: "json",
                    cache: false,
                    headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                    success: function (answer) {
                        console.log(answer);
                    }
                });
            });
        });
    </script>
@endsection
