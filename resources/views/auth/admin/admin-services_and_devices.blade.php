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
                    <div class="parent-add-device">
                        <div id="add-device">
                            <div class="form-group">
                                <label for="deviceInput">Добавить устройство</label>
                                <div class="device-validation-errors"></div>
                                <input type="text" class="form-control" id="deviceInput" placeholder="Модель устройства">
                            </div>
                            <button type="button" id="add-device-btn" class="btn btn-success">Добавить</button>
                        </div>
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
                    <div class="parent-add-service">
                        <div id="add-service">
                            <div class="form-group">
                                <label for="serviceInput">Добавить сервис</label>
                                <div class="service-validation-errors"></div>
                                <input type="text" class="form-control" id="serviceInput" placeholder="Описание сервиса">
                            </div>
                            <button type="button" id="add-service-btn" class="btn btn-success">Добавить</button>
                        </div>
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
                    <div class="device-upd-validation-errors"></div>
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
                    <div class="service-upd-validation-errors"></div>
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
    <script src="{{ asset('/js/admin/services_and_devices.js') }}"></script>
@endsection
