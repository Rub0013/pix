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
        var descInput = $('#offers-description-input');
        var desc = descInput.val();
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
                    descInput.val('');
                }
            }
        });
    });
});