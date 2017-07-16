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
        <div class="add-block-parent">
            <div class="add-offers">
                <p>Добавить предложение</p>
                <div class="offers-validation-errors"></div>
                <div class="form-group offers-description-block">
                    <label for="offers-description-input">Описание</label>
                    <input class="form-control" id="offers-description-input" type="text" placeholder="Введите описание.">
                </div>
                <div class="form-groups-block flex">
                    <div class="form-group offers-status-block">
                        <select id="select-offer-status" class="form-control">
                            <option value="">Выберите статус</option>
                            <option value="0">Выключен</option>
                            <option value="1">Включен</option>
                        </select>
                    </div>
                    <div class="form-group offers-image-block">
                        <label id="offers-image_label" class="btn btn-default btn-file">
                            Загрузить изображение
                            <input type="file" name="image" id="offers-image-input" style="display: none">
                        </label>
                    </div>
                </div>
                <div class="form-group add-offer-btn-block flex">
                    <button id="add-offer-btn" class="btn btn-primary">Добавить</button>
                </div>
            </div>
        </div>
        <div class="added-offers">
            <p>Все предложения</p>
            @if(count($offers) > 0)
                <div class="panel-group scrollbar" id="added-offers-panel" role="tablist" aria-multiselectable="true">
                    @foreach($offers as $offer)
                        <div id="offer_{{$offer->id}}" class="offer-container flex">
                            <div class="offer-data">
                                <p>
                                    <span>Статус - </span>
                                    @if($offer->active)
                                        <span class="current-offer-status status-active">Включен</span>
                                    @else
                                        <span class="current-offer-status">Выключен</span>
                                    @endif
                                </p>
                                <div class="offer-data-buttons flex">
                                    <div class="change-offer-status">
                                        <input @if($offer->active) checked @endif class="offer-status-change" type="checkbox" data-toggle="toggle" data-off='Включить' data-on='Выключить' data-onstyle="danger" data-offstyle="primary">
                                    </div>
                                    <input type="hidden" value="{{$offer->id}}">
                                    <button class="btn btn-danger delete-offer">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="offer-image">
                                <img src='/images/offers/{{$offer->image}}'>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $(document).on( "change", ".offer-status-change", function() {
                var checkbox = $(this);
                checkbox.bootstrapToggle('disable');
                var offerId = checkbox.parent().parent().next().val();
                var sendingData = {
                    offerId: offerId
                };
                if(checkbox.is(':checked')) {
                    sendingData.status = 1;
                } else {
                    sendingData.status = 0;
                }
                $.ajax({
                    type: 'post',
                    url: 'change_offer_status',
                    data: sendingData,
                    headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                    success: function (answer) {
                        if(answer.success) {
                            var spanStatus = $('#offer_' + offerId + ' .current-offer-status');
                            if(sendingData.status) {
                                spanStatus.text('Включен');
                                spanStatus.addClass("status-active");
                            } else {
                                spanStatus.text('Выключен');
                                spanStatus.removeClass("status-active");
                            }
                        }
                        showResponse(answer);
                        checkbox.bootstrapToggle('enable');
                    }
                });
            });
            $(document).on( "click", ".delete-offer", function() {
                var delBtn = $(this);
                delBtn.attr("disabled", true);
                var offerId = delBtn.prev().val();
                $.ajax({
                    type: 'post',
                    url: 'delete_offer',
                    data: {
                        id: offerId
                    },
                    headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                    success: function (answer) {
                        if(answer.success) {
                            delBtn.parent().parent().parent().remove();
                        }
                        delBtn.attr("disabled", false);
                        showResponse(answer);
                    }
                });
            });
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
                        if (answer.validationError) {
                            showValidationErrors(answer.message, 'offers');
                        } else {
                            if(answer.success) {
                                var offerContainer = $('#added-offers-panel');
                                var currentStatus = "<span class='current-offer-status'>Выключен</span>";
                                var checked = '';
                                if(answer.newOffer.active == 1) {
                                    currentStatus = "<span class='current-offer-status status-active'>Включен</span>";
                                    checked = 'checked';
                                }
                                var checkbox =  "<input "+ checked +" class='offer-status-change' type='checkbox' data-toggle='toggle' data-off='Включить' data-on='Выключить' data-onstyle='danger' data-offstyle='primary'>";
                                offerContainer.append("<div id='offer_" + answer.newOffer.id + "' class='offer-container flex'>" +
                                    "<div class='offer-data'>" +
                                        "<p>" +
                                            "<span>Статус - </span>" + currentStatus +
                                        "</p>" +
                                    "<div class='offer-data-buttons flex'>" +
                                    "<div class='change-offer-status'>" +
                                    checkbox +
                                    "</div>" +
                                    "<input type='hidden' value='" + answer.newOffer.id + "'>" +
                                    "<button class='btn btn-danger delete-offer'>" +
                                    "<i class='fa fa-trash-o' aria-hidden='true'></i>" +
                                    "</button>" +
                                    "</div>" +
                                    "</div>" +
                                    "<div class='offer-image'>" +
                                    "<img src='/images/offers/" + answer.newOffer.image + "'>" +
                                    "</div>" +
                                    "</div>");
                                $('.offer-status-change').bootstrapToggle();
                                offerContainer.animate({scrollTop: offerContainer.prop("scrollHeight")}, 400);
                            }
                            showResponse(answer);
                        }
                    }
                });
            });
        });


    </script>
@endsection
