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
        <div class="add-offers">
            <p>Добавить предложение</p>
            <div class="offers-validation-errors"></div>
            <div class="form-group offers-description-block">
                <label for="offers-description-input">Описание</label>
                <input class="form-control" id="offers-description-input" type="text" placeholder="Введите описание.">
            </div>
            <div class="form-groups-block flex">
                <div class="form-group offers-image-block">
                    <label id="offers-image_label" class="btn btn-default btn-file">
                        Загрузить изображение
                        <input type="file" name="image" id="offers-image-input" style="display: none">
                    </label>
                </div>
                <div class="form-group offers-status-block">
                    <select id="select-offer-status" class="form-control">
                        <option value="">Выберите статус</option>
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
            @if(count($offers) > 0)
                <div class="panel-group scrollbar" id="added-offers-panel" role="tablist" aria-multiselectable="true">
                    @foreach($offers as $offer)
                        <div id="offer_{{$offer->id}}" class="panel offer-container">
                            <img src='/images/offers/{{$offer->image}}'>
                            <div class="change-offer-status">
                            @if($offer->active)
                                <input type="checkbox" checked data-toggle="toggle" data-size="small">
                            @else
                                <input type="checkbox"  data-toggle="toggle" data-size="small">
                            @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $(document).on( "change", "#offers-image-input", function() {
                var image = $(this)[0].files[0];
                if(image == undefined) {
                    $(this).parent().removeClass("btn-info");
                    $(this).parent().addClass("btn-default");
                }else{
                    $(this).parent().removeClass("btn-default");
                    $(this).parent().addClass("btn-info");
                }
            });
            $(document).on( "click", "#add-offer-btn", function() {
                var submit = $(this);
                submit.prop('disabled', true);
                var desc = $('#offers-description-input').val();
                var status = $('#select-offer-status').val();
                var offerImg = $('#offers-image-input');
                var image = offerImg[0].files[0];
                var formData = new FormData();
                formData.append('desc',desc);
                formData.append('image',image);
                formData.append('status',status);
                $.ajax({
                    type: 'post',
                    url: 'add_offer',
                    cache: false,
                    ectype: 'multipart/form-data',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                    success: function (answer) {
                        submit.prop('disabled', false);
                        console.log(answer);
                        if (answer.validationError) {
                            showValidationErrors(answer.message, 'offers');
                        }
                    }
                });

            });
        });


    </script>
@endsection
