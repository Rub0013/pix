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