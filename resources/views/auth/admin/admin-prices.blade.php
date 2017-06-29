@extends('layouts.admin')

@section('title')
    Admin-prices
@endsection

@section('content')
    <div class="main-prices flex">
        <div class="add-price">
            <p>Добавить услугу</p>
            <div class="prices-validation-errors"></div>
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
            <p>Все услуги</p>
            @if(count($devices) > 0)
                <div class="panel-group scrollbar" id="added-products-panel" role="tablist" aria-multiselectable="true">
                    @foreach($devices as $product)
                        <div id="device_{{$product->id}}" class="panel">
                            <div class="panel-heading" role="tab" id="heading_{{$product->id}}">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#added-products-panel" href="#collapse_{{$product->id}}" aria-expanded="false" aria-controls="collapse_{{$product->id}}">
                                        {{$product->model}}
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse_{{$product->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_{{$product->id}}">
                                <div class="panel-body">
                                    @if(count($product->prices) > 0)
                                        @foreach($product->prices as $productPrice)
                                            <div class="product-service flex ps_{{$productPrice->id}}">
                                                <div class="product-service-data flex">
                                                    <p class="align-center">{{$productPrice->service['description']}}</p>
                                                    <b class="align-center">
                                                        <span class="priceSpan">{{$productPrice->price}}</span>
                                                        <i class="fa fa-rub" aria-hidden="true"></i>
                                                    </b>
                                                </div>
                                                <div class="product-price-buttons flex">
                                                    <button class="btn btn-info btn-sm change-price">Изменить цену</button>
                                                    <input type="hidden" value="{{$productPrice->id}}">
                                                    <button class="btn btn-danger btn-sm delete-product">Удалить</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <h4>Нет зарегистрированных услуг</h4>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <div class="modal fade" id="price-update-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Изменить цену</h4>
                </div>
                <div class="modal-body">
                    <div class="prices-upd-validation-errors"></div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="changePriceModal" placeholder="Введите цену услуги.">
                        <input type="hidden" class="form-control" id="priceIdModal">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="price-upd-modal-btn">Обновить</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            var priceModal = $('#price-update-modal');
            function resetForm() {
                $('#select-device').prop('selectedIndex',0);
                $('#select-service').prop('selectedIndex',0);
                $('#product-price').val('');
            }
            function showValidationErrors(message, block) {
                var errorBlock = $('.' + block + '-validation-errors');
                errorBlock.empty();
                $('<div class="alert alert-danger" >' + message + '</div>').prependTo(errorBlock).delay(3000).slideUp(1000, function () {
                    errorBlock.empty();
                });
            }
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
                            price: price
                        },
                        dataType: "json",
                        cache: false,
                        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                        success: function (answer) {
                            if(answer.validationError) {
                                showValidationErrors(answer.message, 'prices');
                            } else {
                                showResponse(answer);
                                if(answer.success) {
                                    var currentDeviceBox = $('#device_' + device + ' .panel-body');
                                    var EmptyBox = $('#device_' + device + ' .panel-body > h4');
                                    if(EmptyBox.length > 0) {
                                        EmptyBox.remove();
                                    }
                                    currentDeviceBox.append("<div class='product-service flex ps_" + answer.newServiceProduct.id + "'>" +
                                        "<div class='product-service-data flex'>" +
                                            "<p class='align-center'>" + answer.newServiceProduct.description + "</p>" +
                                            "<b class='align-center'>" +
                                                "<span class='priceSpan'>" + answer.newServiceProduct.price + "</span>" +
                                                "<i class='fa fa-rub' aria-hidden='true'></i>" +
                                            "</b>" +
                                        "</div>" +
                                        "<div class='product-price-buttons flex'>" +
                                            "<button class='btn btn-info btn-sm change-price'>Изменить цену</button>" +
                                            "<input type='hidden' value='" + answer.newServiceProduct.id + "'>" +
                                            "<button class='btn btn-danger btn-sm delete-product'>Удалить</button>" +
                                        "</div>" +
                                     "</div>");
                                    resetForm();
                                }
                            }
                        }
                    });
                }
            });
            $(document).on( "click", ".delete-product", function() {
                var currentProduct = $(this).parent().parent();
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
                        showResponse(answer);
                        if(answer.success) {
                            var currentDeviceId = currentProduct.parent().parent().attr('id');
                            currentProduct.remove();
                            if ($('#' + currentDeviceId + '>.panel-body .product-service').length == 0) {
                                $('#' + currentDeviceId + '>.panel-body').append("<h4>Нет зарегистрированных услуг</h4>");
                            }
                        }
                    }
                });
            });
            $(document).on( "click", ".change-price", function() {
                var id = $(this).next().val();
                var oldPrice = $(this).parent().prev().find('b').find('span').text();
                $('#changePriceModal').val(oldPrice);
                $('#priceIdModal').val(id);
                priceModal.modal('show');
                priceModal.parent().next().removeClass('modal-backdrop');
            });
            $(document).on( "click", "#price-upd-modal-btn", function() {
                var newPrice = $('#changePriceModal').val();
                var id = $('#priceIdModal').val();
                if(newPrice.length > 0) {
                    $.ajax({
                        type: 'post',
                        url: 'update_sp_price',
                        data: {
                            id: id,
                            newPrice: newPrice
                        },
                        dataType: "json",
                        cache: false,
                        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                        success: function (answer) {
                            if(answer.validationError) {
                                showValidationErrors(answer.message, 'prices-upd');
                            } else {
                                if (answer.success) {
                                    $('.ps_' + id + ' .priceSpan').text(newPrice);
                                }
                                priceModal.modal('hide');
                                showResponse(answer);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
