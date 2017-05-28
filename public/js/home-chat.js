$(document).ready(function(){
    var conn = new WebSocket("ws://localhost:8080");
    var block = $("#view-messages");
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
    $(document).on( "click", ".toggle-chat", function() {
        $(this).next().toggleClass('chat-opened');
        block.scrollTop(block.prop('scrollHeight'));
    });
});

