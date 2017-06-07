$(document).ready(function(){
    var conn = new WebSocket("ws://localhost:8080");
    var block = $("#view-messages");
    var adminOnline = false;
    conn.onopen = function (e) {
        console.log("Connected");
    };
    $(document).on( "change", "#image_file", function() {
        var image = $(this)[0].files[0];
        if(image == undefined) {
            $(this).parent().removeClass("btn-primary");
            $(this).parent().addClass("btn-default");
        }else{
            $(this).parent().removeClass("btn-default");
            $(this).parent().addClass("btn-primary");
        }
    });
    $(document).on( "change", "#email-attach-file_input", function() {
        var image = $(this)[0].files[0];
        if(image == undefined) {
            $(this).parent().removeClass("btn-info");
            $(this).parent().addClass("btn-default");
        }else{
            $(this).parent().removeClass("btn-default");
            $(this).parent().addClass("btn-info");
        }
    });
    $(document).on( "click", "#submit-send-message", function() {
        var text = $("#message").val();
        var input = $('#image_file');
        var image = input[0].files[0];
        if(image != undefined || text.length > 0) {
            if(image == undefined){
                conn.send(JSON.stringify({
                    message: text
                }));
                block.append("<div class='from-user flex'>" +
                    "<div class='user-icon'>" +
                    "<img src='images/icons/user-icon.png'>" +
                    "</div>" +
                    "<div class='message-main'>" +
                    "<div class='text-message'>" +
                    "<pre class='pre-message'>" + text + "</pre>" +
                    "</div>" +
                    "</div>" +
                    "</div>");
            } else {
                var time = Math.round(new Date().getTime() / 1000);
                var imageName = image.name;
                var imageExtension = imageName.split('.')[imageName.split('.').length - 1].toLowerCase();
                var random = Math.floor(100000 + Math.random() * 900000);
                var newName = time + '_' + random + '.' + imageExtension;
                var formData = new FormData();
                formData.append('image',image);
                formData.append('name',newName);
                $.ajax({
                    type: 'post',
                    url: '/send_image',
                    cache: false,
                    ectype: 'multipart/form-data',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                    success: function (answer) {
                        if(answer.success){
                            block.append("<div class='from-user flex msg_" + time + "'>" +
                                "<div class='user-icon'>" +
                                "<img src='images/icons/user-icon.png'>" +
                                "</div>" +
                                "<div class='message-main'>" +
                                "<div class='image-message'>" +
                                "<img src='images/uploaded/" + newName + "'>" +
                                "</div>" +
                                "</div>" +
                                "</div>");
                            var sendingData = {
                                image: newName
                            };
                            if(text.length > 0){
                                sendingData.message = text;
                                $('.msg_' + time + ' .message-main').append("<div class='text-message'>" +
                                    "<pre class='pre-message'>" + text + "</pre>" +
                                    "</div>");
                            }
                            conn.send(JSON.stringify(sendingData));
                            input.parent().removeClass("btn-primary");
                            input.parent().addClass("btn-default");
                            block.animate({scrollTop: block.prop("scrollHeight")}, 400);
                        } else{
                            alert('Something gone wrong!')
                        }
                    }
                });
            }
        }
        else{
            alert('Add a message');
        }
        $("#message").val('');
        input.val('');
        block.animate({scrollTop: block.prop("scrollHeight")}, 400);
    });
    conn.onmessage = function (e) {
        var data = JSON.parse(e.data);
        console.log(data.adminOnline);
        adminOnline = data.adminOnline;
        if(data.msg){
            block.append("<div class='from-admin flex'>" +
                "<div class='message-main'>" +
                "<div class='text-message'>" +
                "<pre class='pre-message'>" + data.msg + "</pre>" +
                "</div>" +
                "</div>" +
                "<div class='admin-icon'>" +
                "<img src='images/icons/admin-icon.jpg'>" +
                "</div>" +
                "</div>");
        }
        if(data.img){
            block.append("<div class='from-admin flex'>" +
                "<div class='message-main'>" +
                "<div class='image-message'>" +
                "<img src='images/uploaded/" + data.img + "'>" +
                "</div>" +
                "</div>" +
                "<div class='admin-icon'>" +
                "<img src='images/icons/admin-icon.jpg'>" +
                "</div>" +
                "</div>");
        }
        block.animate({scrollTop: block.prop("scrollHeight")}, 400);
    };
    $(document).on( "click", "#question", function() {
        if(adminOnline) {
            $('.chat-container').toggleClass('chat-opened');
            block.scrollTop(block.prop('scrollHeight'));
        } else {
            $('#sendMailModal').modal('show');
        }
    });
    $(document).on( "click", ".toggle-chat", function() {
        $(this).next().toggleClass('chat-opened');
        block.scrollTop(block.prop('scrollHeight'));
    });
    $(document).on( "click", "#send-mail", function() {
        var userName = $('#user-name').val();
        var userEmail = $('#user-email').val();
        var emailText = $('#email-text').val();
        if(userName.length > 0 && userEmail.length > 0 && emailText.length > 0) {
            var submit = $(this);
            submit.prop('disabled', true);
            var $btn = $(this).button('loading');
            var input = $('#email-attach-file_input');
            var file = input[0].files[0];
            var formData = new FormData();
            formData.append('name',userName);
            formData.append('email',userEmail);
            formData.append('text',emailText);
            if(file != undefined) {
                formData.append('file',file);
            }
            if ($('#viber-checkbox').is(':checked')) {
                var viber = $('#viber-number').val().replace(/\s/g, '');
                if(viber.length > 0) {
                    formData.append('Viber',viber);
                }
            }
            if ($('#whatsapp-checkbox').is(':checked')) {
                var whatsApp = $('#whatsapp-number').val().replace(/\s/g, '');
                if(whatsApp.length > 0) {
                    formData.append('WhatsApp',whatsApp);
                }
            }
            $.ajax({
                type: 'post',
                url: '/send_mail_user',
                cache: false,
                ectype: 'multipart/form-data',
                data: formData,
                processData: false,
                contentType: false,
                headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                success: function (answer) {
                    $btn.button('reset');
                    submit.prop('disabled', false);
                    if(answer.success) {
                        $('#sendMailModal').modal('hide');
                    } else {
                        var error = answer.error[Object.keys(answer.error)[0]];
                        var errorBlock = $('#login-error');
                        errorBlock.text('');
                        errorBlock.append(error[0]);
                        errorBlock.show();
                        console.log(answer.error);
                    }
                }
            });
        }
    });
    $(':checkbox').change(function () {
        var selector = $('#' + $(this).parent().next().find('input').attr('id'));
        if(this.checked) {
            selector.prop( "disabled", false );
        } else {
            selector.prop( "disabled", true );
        }
    });
    $('#sendMailModal').on('hidden.bs.modal', function () {
        $("#collapseCallBack").collapse('hide');
        var viber = $('#viber-number');
        var whatsApp = $('#whatsapp-number');
        var input = $('#email-attach-file_input');
        viber.val('');
        whatsApp.val('');
        input.val('');
        input.parent().removeClass("btn-info");
        input.parent().addClass("btn-default");
        $('#user-name').val('');
        $('#user-email').val('');
        $('#email-text').val('');
        viber.prop( "disabled", true );
        whatsApp.prop( "disabled", true );
        $('#viber-checkbox').prop('checked', false);
        $('#whatsapp-checkbox').prop('checked', false);
    })
    $(function() {
        $('#datetimepicker1').datetimepicker({
            locale: 'ru'
        });
    });
});

