@extends('layouts.admin')

@section('title')
    Admin-panel
@endsection

@section('content')
    <div class="main-panel">
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#add-device-tab">Утройства</a>
            </li>
            <li>
                <a data-toggle="tab" href="#add-service-tab">Сервисы</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="add-device-tab" class="tab-pane fade in active">
                <div class="flex">
                    <div id="add-device">
                        <div class="form-group">
                            <label for="deviceInput">Добавить устройство</label>
                            <input type="text" class="form-control" id="deviceInput" placeholder="Модель устройства">
                        </div>
                        <button type="button" id="add-device-btn" class="btn btn-success">Добавить</button>
                    </div>
                    <div id="show-devices">
                        <p>Все устройства</p>
                        <div class="all-devices scrollbar">
                            @if(count($devices) > 0)
                                @foreach($devices as $device)
                                    <div class="dev_{{$device->id}} one-device flex">
                                        <p>{{$device->model}}</p>
                                        <div class="service-device-buttons flex">
                                            <button class="btn btn-success btn-sm device-upd-btn">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </button>
                                            <input type="hidden" value="{{$device->id}}">
                                            <button class="btn btn-danger btn-sm device-del-btn">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                            <p>Нет добавленных устройств.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div id="add-service-tab" class="tab-pane fade">
                <div class="flex">
                    <div id="add-service">
                        <div class="form-group">
                            <label for="serviceInput">Добавить сервис</label>
                            <input type="text" class="form-control" id="serviceInput" placeholder="Описание сервиса">
                        </div>
                        <button type="button" id="add-service-btn" class="btn btn-success">Добавить</button>
                    </div>
                    <div id="show-services">
                        <p>Все сервисы</p>
                        <div class="all-services scrollbar">
                            @if(count($services) > 0)
                                @foreach($services as $service)
                                    <div class="srv_{{$service->id}} one-service flex">
                                        <p>{{$service->description}}</p>
                                        <div class="service-device-buttons flex">
                                            <button class="btn btn-success btn-sm service-upd-btn">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </button>
                                            <input type="hidden" value="{{$service->id}}">
                                            <button class="btn btn-danger btn-sm service-del-btn">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                            <p>Нет добавленных сервисов.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="device-update-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Изменить</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="deviceNameModal" placeholder="Модель устройства">
                        <input type="hidden" class="form-control" id="deviceIdModal">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="device-upd-modal-btn">Обновить</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="service-update-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Изменить</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="serviceDescriptionModal" placeholder="Описание сервиса">
                        <input type="hidden" class="form-control" id="serviceIdModal">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="service-upd-modal-btn">Обновить</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
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
                            if (answer.success) {
                                if (noServices.length > 0) {
                                    noServices.remove();
                                }
                                $('.all-services').append("<div class='dev_" + answer.newService.id + " one-service flex'>" +
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
                            if (answer.success) {
                                if (noDevices.length > 0) {
                                    noDevices.remove();
                                }
                                $('.all-devices').append("<div class='dev_" + answer.newDevice.id + " one-device flex'>" +
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
                            if (answer.success) {
                                $('.dev_' + id + ' > p').text(device);
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
                            if (answer.success) {
                                $('.srv_' + id + ' > p').text(service);
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
    </script>
@endsection
