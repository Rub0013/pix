$(document).ready(function(){
    var branchModal = $('#branch-update-modal');
    function resetForm() {
        $('#branch-latitude').val('');
        $('#branch-longitude').val('');
        $('#branch-title').val('');
        $('#branch-address').val('');
    }
    $(document).on( "click", "#add-branch-btn", function() {
        var latitudeInput = $('#branch-latitude');
        var longitudeInput = $('#branch-longitude');
        var titleInput = $('#branch-title');
        var addressInput = $('#branch-address');
        var latitude = latitudeInput.val();
        var longitude = longitudeInput.val();
        var title = titleInput.val();
        var address = addressInput.val();
        if(latitude.length > 0 && longitude.length > 0 && title.length > 0 && address.length > 0) {
            $.ajax({
                type: 'post',
                url: 'add_branch',
                data: {
                    latitude: latitude,
                    longitude: longitude,
                    title: title,
                    address: address
                },
                dataType: "json",
                cache: false,
                headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                success: function (answer) {
                    console.log(answer);
                    if(answer.validationError) {
                        showValidationErrors(answer.message, 'branches');
                    } else {
                        showResponse(answer);
                        if(answer.success) {
                            var branchBox = $('#added-branches-panel');
                            branchBox.append("<div id='branch_" + answer.newBranch.id + "' class='panel one-branch'>" +
                                "<div class='branch-info'>" +
                                "<div class='branch-title-block'>" +
                                "<p>Название</p>" +
                                "<p class='branch-title'>" + answer.newBranch.title + "</p>" +
                                "</div>" +
                                "<div class='branch-address-block'>" +
                                "<p>Адрес филиала</p>" +
                                "<p class='branch-address'>" + answer.newBranch.address + "</p>" +
                                "</div>" +
                                "</div>" +
                                "<div class='branch-buttons flex'>" +
                                "<button class='btn btn-info btn-sm upt-branch'>" +
                                "<i class='fa fa-pencil' aria-hidden='true'></i>" +
                                "</button>" +
                                "<input type='hidden' value='" + answer.newBranch.id + "'>" +
                                "<button class='btn btn-danger btn-sm delete-branch'>" +
                                "<i class='fa fa-trash-o' aria-hidden='true'></i>" +
                                "</button>" +
                                "</div>" +
                                "</div>");
                            branchBox.scrollTop(branchBox.prop('scrollHeight'));
                            resetForm();
                        }
                    }
                }
            });
        }
    });
    $(document).on( "click", ".delete-branch", function() {
        var delButton = $(this);
        var branchId = delButton.prev().val();
        $.ajax({
            type: 'post',
            url: 'delete_branch',
            data: {
                branchId: branchId
            },
            dataType: "json",
            cache: false,
            headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
            success: function (answer) {
                showResponse(answer);
                if(answer.success) {
                    delButton.parent().parent().remove();
                }
            }
        });
    });
    $(document).on( "click", ".upt-branch", function() {
        var id = $(this).next().val();
        var branchTitle = $(this).parent().prev().find('.branch-title-block').find('.branch-title').text();
        var branchAddress = $(this).parent().prev().find('.branch-address-block').find('.branch-address').text();
        $('#upt-branch-title').val(branchTitle);
        $('#upt-branch-address').val(branchAddress);
        $('#branchIdModal').val(id);
        branchModal.modal('show');
        branchModal.parent().next().removeClass('modal-backdrop');
    });
    $(document).on( "click", "#branch-upd-modal-btn", function() {
        var newTitle = $('#upt-branch-title').val();
        var newAddress = $('#upt-branch-address').val();
        var id = $('#branchIdModal').val();
        $.ajax({
            type: 'post',
            url: 'update_branch',
            data: {
                id: id,
                newTitle: newTitle,
                newAddress: newAddress
            },
            dataType: "json",
            cache: false,
            headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
            success: function (answer) {
                if(answer.validationError) {
                    showValidationErrors(answer.message, 'branches-upd');
                } else {
                    if (answer.success) {
                        $('#branch_' + id + ' .branch-title').text(newTitle);
                        $('#branch_' + id + ' .branch-address').text(newAddress);
                    }
                    branchModal.modal('hide');
                    showResponse(answer);
                }
            }
        });
    });
});
