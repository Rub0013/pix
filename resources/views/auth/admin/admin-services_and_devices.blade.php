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
                <div id="add-device">
                    <div class="form-group">
                        <label for="deviceInput">Добавить устройство</label>
                        <input type="text" class="form-control" id="deviceInput" placeholder="Модель устройства">
                    </div>
                    <button type="button" id="add-device-btn" class="btn btn-success">Добавить</button>
                </div>
            </div>
            <div id="add-service-tab" class="tab-pane fade">
                <div id="add-service">
                    <div class="form-group">
                        <label for="serviceInput">Добавить сервис</label>
                        <input type="text" class="form-control" id="serviceInput" placeholder="Описание сервиса">
                    </div>
                    <button type="button" id="add-service-btn" class="btn btn-success">Добавить</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $(document).on( "click", "#add-service-btn", function() {
                var serviceInput = $('#serviceInput');
                var serviceDescription = serviceInput.val();
                if(serviceDescription.length > 0) {
                    $.ajax({
                        type: 'post',
                        url: 'add_service',
                        data: { newService: serviceDescription },
                        dataType: "json",
                        cache: false,
                        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                        success: function (answer) {
                            console.log(answer);
                        }
                    });
                }
            });
            $(document).on( "click", "#add-device-btn", function() {
                var deviceInput = $('#deviceInput');
                var deviceModel = deviceInput.val();
                if(deviceModel.length > 0) {
                    $.ajax({
                        type: 'post',
                        url: 'add_device',
                        data: { deviceModel: deviceModel },
                        dataType: "json",
                        cache: false,
                        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                        success: function (answer) {
                            console.log(answer);
                        }
                    });
                }
            });
        });
    </script>
@endsection
