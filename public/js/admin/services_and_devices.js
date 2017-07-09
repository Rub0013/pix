$(document).ready(function(){
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href");
        if (target == '#add-device-tab') {
            $('#deviceInput').val('');
        } else {
            $('#serviceInput').val('');
        }
    });
    function showValidationErrors(message, block) {
        var errorBlock = $('.' + block + '-validation-errors');
        errorBlock.empty();
        $('<div class="alert alert-danger" >' + message + '</div>').prependTo(errorBlock).delay(2500).slideUp(1000, function () {
            errorBlock.empty();
        });
    }
    var deviceModal = $('#device-update-modal');
    var serviceModal = $('#service-update-modal');
    $(document).on( "click", "#add-service-btn", function() {
        var serviceInput = $('#serviceInput');
        var serviceDescription = serviceInput.val();
        var noServices = $('.all-services > p');
        if(serviceDescription.length > 0) {
            $.ajax({
                type: 'post',
                url: 'add_service',
                data: { newService: serviceDescription },
                dataType: "json",
                cache: false,
                headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                success: function (answer) {
                    if (answer.validationError) {
                        showValidationErrors(answer.message,'service')
                    } else {
                        if (answer.success) {
                            if (noServices.length > 0) {
                                noServices.remove();
                            }
                            var serviceBox = $('.all-services');
                            serviceBox.append("<div class='dev_" + answer.newService.id + " one-service flex'>" +
                                "<p>" + answer.newService.description + "</p>" +
                                "<div class='service-device-buttons flex'>" +
                                "<button class='btn btn-success btn-sm service-upd-btn'>" +
                                "<i class='fa fa-pencil' aria-hidden='true'></i>" +
                                "</button>" +
                                "<input type='hidden' value='" + answer.newService.id + "'>" +
                                "<button class='btn btn-danger btn-sm service-del-btn'>" +
                                "<i class='fa fa-trash' aria-hidden='true'></i>" +
                                "</button>" +
                                "</div>" +
                                "</div>");
                            serviceInput.val('');
                            serviceBox.scrollTop(serviceBox.prop('scrollHeight'));
                        }
                        showResponse(answer);
                    }
                }
            });
        }
    });
    $(document).on( "click", "#add-device-btn", function() {
        var deviceInput = $('#deviceInput');
        var deviceModel = deviceInput.val();
        var noDevices = $('.all-devices > p');
        if(deviceModel.length > 0) {
            $.ajax({
                type: 'post',
                url: 'add_device',
                data: { deviceModel: deviceModel },
                dataType: "json",
                cache: false,
                headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                success: function (answer) {
                    if (answer.validationError) {
                        showValidationErrors(answer.message,'device')
                    } else {
                        if (answer.success) {
                            if (noDevices.length > 0) {
                                noDevices.remove();
                            }
                            var deviceBlock = $('.all-devices');
                            deviceBlock.append("<div class='dev_" + answer.newDevice.id + " one-device flex'>" +
                                "<p>" + answer.newDevice.model + "</p>" +
                                "<div class='service-device-buttons flex'>" +
                                "<button class='btn btn-success btn-sm device-upd-btn'>" +
                                "<i class='fa fa-pencil' aria-hidden='true'></i>" +
                                "</button>" +
                                "<input type='hidden' value='" + answer.newDevice.id + "'>" +
                                "<button class='btn btn-danger btn-sm device-del-btn'>" +
                                "<i class='fa fa-trash' aria-hidden='true'></i>" +
                                "</button>" +
                                "</div>" +
                                "</div>");
                            deviceInput.val('');
                            deviceBlock.scrollTop(deviceBlock.prop('scrollHeight'));
                        }
                        showResponse(answer);
                    }
                }
            });
        }
    });
    $(document).on( "click", ".device-upd-btn", function() {
        var id = $(this).next().val();
        var device = $(this).parent().prev().text();
        $('#deviceNameModal').val(device);
        $('#deviceIdModal').val(id);
        deviceModal.modal('show');
        deviceModal.parent().next().removeClass('modal-backdrop');
    });
    $(document).on( "click", "#device-upd-modal-btn", function() {
        var device = $('#deviceNameModal').val();
        var id = $('#deviceIdModal').val();
        if(device.length > 0) {
            $.ajax({
                type: 'post',
                url: 'update_device',
                data: {
                    id: id,
                    model: device
                },
                dataType: "json",
                cache: false,
                headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                success: function (answer) {
                    if (answer.validationError) {
                        showValidationErrors(answer.message,'device-upd')
                    } else {
                        if (answer.success) {
                            $('.dev_' + id + ' > p').text(device);
                        }
                        deviceModal.modal('hide');
                        showResponse(answer);
                    }
                }
            });
        }
    });
    $(document).on( "click", ".device-del-btn", function() {
        var btn = $(this);
        var id = btn.prev().val();
        $.ajax({
            type: 'post',
            url: 'delete_device',
            data: {
                id: id
            },
            dataType: "json",
            cache: false,
            headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
            success: function (answer) {
                if (answer.success) {
                    btn.parent().parent().remove();
                    if ($('.one-device').length == 0) {
                        $('.all-devices').append("<p>Нет добавленных устройств.</p>");
                    }
                    showResponse(answer);
                }
            }
        });
    });
    $(document).on( "click", ".service-upd-btn", function() {
        var id = $(this).next().val();
        var serviceDesc = $(this).parent().prev().text();
        $('#serviceDescriptionModal').val(serviceDesc);
        $('#serviceIdModal').val(id);
        serviceModal.modal('show');
        serviceModal.parent().next().removeClass('modal-backdrop');
    });
    $(document).on( "click", "#service-upd-modal-btn", function() {
        var service = $('#serviceDescriptionModal').val();
        var id = $('#serviceIdModal').val();
        if(service.length > 0) {
            $.ajax({
                type: 'post',
                url: 'update_service',
                data: {
                    id: id,
                    service: service
                },
                dataType: "json",
                cache: false,
                headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                success: function (answer) {
                    if (answer.validationError) {
                        showValidationErrors(answer.message,'service-upd')
                    } else {
                        if (answer.success) {
                            $('.srv_' + id + ' > p').text(service);
                        }
                        serviceModal.modal('hide');
                        showResponse(answer);
                    }
                }
            });
        }
    });
    $(document).on( "click", ".service-del-btn", function() {
        var btn = $(this);
        var id = btn.prev().val();
        $.ajax({
            type: 'post',
            url: 'delete_service',
            data: {
                id: id
            },
            dataType: "json",
            cache: false,
            headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
            success: function (answer) {
                if (answer.success) {
                    btn.parent().parent().remove();
                    if ($('.one-service').length == 0) {
                        $('.all-services').append("<p>Нет добавленных сервисов.</p>");
                    }
                    showResponse(answer);
                }
            }
        });
    });
});