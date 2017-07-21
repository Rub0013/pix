$(document).ready(function(){
    $(document).on('change', '#contact-type', function () {
        var contactType = $(this).val();
        changeContactType(contactType);
    });
    function changeContactType(contact) {
        var contactInputBlock = $('.contact-input-div').empty();
        if(contact == 'phone') {
            contactInputBlock.append("<input class='form-control' id='contact-phone' type='text' placeholder='Введите номер телефона.'>");
        }
        if(contact == 'email') {
            contactInputBlock.append("<input class='form-control' id='contact-email' type='text' placeholder='Введите email.'>");
        }
    }
    $(document).on('click', '#add-contact-btn', function () {
        var emailInput = $('#contact-email');
        var phoneInput = $('#contact-phone');
        var email = emailInput.val();
        var phone = phoneInput.val();
        var sendingData = {};
        if(phone) {
            sendingData.type = 'phone';
            sendingData.value = phone;
        }
        if(email) {
            sendingData.type = 'email';
            sendingData.value = email;
        }
        if(!jQuery.isEmptyObject(sendingData)) {
            $.ajax({
                type: 'post',
                url: 'add_contact',
                data: sendingData,
                headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                success: function (answer) {
                    if(answer.validationError) {
                        showValidationErrors(answer.message, 'contacts');
                    } else {
                        if(answer.success) {
                            var contactsBlock = $('#contacts-container');
                            var contactIconClass = "fa";
                            var contactVal = null;
                            if(answer.newContact.email) {
                                contactIconClass = contactIconClass + " fa-envelope";
                                contactVal = answer.newContact.email;
                            } else {
                                contactIconClass = contactIconClass + " fa-phone-square";
                                contactVal = answer.newContact.phone;
                            }
                            contactsBlock.append("<div class='one-contact contact_" + answer.newContact.id + " flex'>" +
                                "<div class='flex'>" +
                                "<i class='" + contactIconClass + "' aria-hidden='true'></i>" +
                                "<p>" + contactVal + "</p>" +
                                "</div>" +
                                "<input type='hidden' value='" + answer.newContact.id + "'>" +
                                "<button class='btn btn-danger btn-sm delete-contact-btn'>" +
                                "<i class='fa fa-trash' aria-hidden='true'></i>" +
                                "</button>" +
                                "</div>");
                            contactsBlock.animate({scrollTop: contactsBlock.prop("scrollHeight")}, 400);
                        }
                        showResponse(answer);
                        emailInput.val('');
                        phoneInput.val('');
                    }
                }
            });
        }
    });
    $(document).on('click', '.delete-contact-btn', function () {
        var btn = $(this);
        var contactId = btn.prev().val();
        $.ajax({
            type: 'post',
            url: 'delete_contact',
            data: {id: contactId},
            headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
            success: function (answer) {
                if(answer.success) {
                    btn.parent().remove();
                }
                showResponse(answer);
            }
        });
    })
});