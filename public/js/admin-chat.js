$(document).ready(function(){
    $.ajax({
        type: 'post',
        url: 'notifications',
        dataType: "json",
        cache: false,
        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
        success: function (answer) {
            if(answer.success){
                var totalNotes= answer.data.unseen;
                if(totalNotes.length > 0){
                    $('#unseen').text(totalNotes.length);
                    $('#unseen').show('slow');
                    $('#unseen-dot').show('slow');
                    for(var i = 0; i < totalNotes.length; i++){
                        var fromId = totalNotes[i].connectionId;
                        var notes = totalNotes[i].total;
                        $('#heading_' + fromId).find('h4').find('.conversation_notes').text(notes);
                        $('#heading_' + fromId).find('h4').find('.conversation_notes').fadeIn('slow');
                    }
                }
            }
            else{
                $('#unseen').text(0);
                $('#unseen').hide('slow');
            }
        }
    });
    Date.prototype.today = function () {
        return ((this.getDate() < 10)?"0":"") + this.getDate() +"/"+(((this.getMonth()+1) < 10)?"0":"") + (this.getMonth()+1) +"/"+ this.getFullYear();
    };
    Date.prototype.timeNow = function () {
        return ((this.getHours() < 10)?"0":"") + this.getHours() +":"+ ((this.getMinutes() < 10)?"0":"") + this.getMinutes();
    };
    function dateTime() {
        return new Date().today() + " - " + new Date().timeNow();
    }
    var conn = new WebSocket("ws://localhost:8080?admin=true");
    conn.onopen = function (e) {
        console.log('Connected!');
    };
    conn.onmessage = function (e) {
        $('#unseen-dot').show('slow');
        if($('#all-chats').length != 0){
            var datetime = dateTime();
            var data = JSON.parse(e.data);
            var image = data.img;
            var message = data.msg;
            var fromId = data.from_id;
            var currentChat = $("#user_" + fromId);
            if(currentChat.length == 0){
                $("#all-chats").append("<div class='panel panel-primary' id='user_" + fromId + "'>" +
                    "<div class='panel-heading conversation' role='tab' id='heading_" + fromId + "'>" +
                    "<h4 class='panel-title'>" +
                    "<a class='collapsed open-close' role='button' data-toggle='collapse' data-parent='#all-chats' href='#collapse_" + fromId + "' aria-expanded='false' aria-controls='heading_" + fromId + "'>" +
                    'Диалог # ' + fromId +
                    "</a>" +
                    "<span class='conversation_notes'>0</span>" +
                    "</h4>" +
                    "<button class='btn btn-danger btn-sm delChat'>Удалить</button>" +
                    "</div>" +
                    "<div id='collapse_" + fromId + "' class='panel-collapse collapse' role='tabpanel' aria-labelledby='heading_" + fromId + "'>" +
                    "<div class='panel-body scrollbar'>" +
                    "<ul class='chat'>" +
                    "</ul>" +
                    "</div>" +
                    "<div class='panel-footer'>" +
                        "<div class='flex'>" +
                            "<textarea class='btn-input form-control input-sm' placeholder='Ваше сообщение ...'></textarea>" +
                            "<div class='controls-button flex'>" +
                                "<label class='btn btn-default btn-file'>" +
                                    "Добавить изображение <input type='file' name='image' id='image_file' style='display: none' accept='.jpg,.png'>" +
                                "</label>" +
                                "<input type='hidden' value='" + fromId + "'>" +
                                "<button class='btn btn-warning btn-sm btn-chat'>Отправить</button>" +
                            "</div>" +
                        "</div>" +
                    "</div>" +
                    "</div>" +
                    "</div>");
            }
            var chat = $('#collapse_' + fromId + " .chat");
            if(message){
                chat.append("<li class='left clearfix'>" +
                    "<span class='chat-img pull-left'>" +
                    "<span class='img-circle circle-user flex'>" +
                    "<span>U</span>" +
                    "</span>" +
                    "</span>" +
                    "<div class='chat-body clearfix'>" +
                    "<div class='header'>" +
                    "<small class='text-muted'>" +
                    "<span class='glyphicon glyphicon-time'></span>" + datetime +
                    "</small>" +
                    "</div>" +
                    "<pre class='pre-message'>" + message + "</pre>" +
                    "</div>" +
                    "</li>");
            }
            if(image){
                chat.append("<li class='left clearfix'>" +
                    "<span class='chat-img pull-left'>" +
                    "<span class='img-circle circle-user flex'>" +
                    "<span>U</span>" +
                    "</span>" +
                    "</span>" +
                    "<div class='chat-body clearfix'>" +
                    "<div class='header'>" +
                    "<small class='text-muted'>" +
                    "<span class='glyphicon glyphicon-time'></span>" + datetime +
                    "</small>" +
                    "</div>" +
                    "<div class='uploaded-image'>" +
                    "<img src='/images/uploaded/" + image + "'>" +
                    "</div>" +
                    "</div>" +
                    "</li>");
            }
            var block = $("#collapse_" + fromId + " .panel-body");
            block.animate({scrollTop: block.prop("scrollHeight")}, 400);
            var currentNotesSpan = $('#heading_' + fromId).find('h4').find('.conversation_notes');
            var spanNotes = parseInt(currentNotesSpan.text(),10) + 1;
            if(spanNotes == 1){
                var headerNotes = parseInt($('#unseen').text(),10) + 1;
                $('#unseen').text(headerNotes);
                $('#unseen').show('slow');
            }
            currentNotesSpan.text(spanNotes);
            currentNotesSpan.fadeIn('slow');
        }
        else{
            $.ajax({
                type: 'post',
                url: 'notifications',
                dataType: "json",
                cache: false,
                headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                success: function (answer) {
                    if(answer.success){
                        var totalNotes= answer.data.unseen;
                        if(totalNotes.length > 0){
                            $('#unseen').text(totalNotes.length);
                            $('#unseen').show('slow');
                            $('#unseen-dot').show('slow');
                        }
                    }
                    else{
                        $('#unseen').text(0);
                        $('#unseen').hide('slow');
                    }
                }
            });
        }
    };
    $(document).on( "change", "#image_file", function() {
        var image = $(this)[0].files[0];
        if(image == undefined) {
            $(this).parent().removeClass("btn-primary");
            $(this).parent().addClass("btn-info");
        }else{
            $(this).parent().removeClass("btn-info");
            $(this).parent().addClass("btn-primary");
        }
    });
    $(document).on( "click", ".open-close", function() {
        var sId =  $(this).parent().parent().next().attr('id');
        var selector = "#" + sId + " .panel-body";
        var connectionId = sId.split("_")[1];
        var clickEvent = $("#collapse_" + connectionId).attr( "aria-expanded" );
        if(clickEvent == 'true'){
            var spanNotes = $('#heading_' + connectionId).find('h4').find('.conversation_notes');
            if(spanNotes.text() > 0 ){
                spanNotes.fadeOut("slow");
                spanNotes.text(0);
                $.ajax({
                    type: 'post',
                    url: 'open_conversation',
                    data: { id: connectionId },
                    dataType: "json",
                    cache: false,
                    headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                    success: function (answer) {
                        if(answer.success){
                            var headerNotes = parseInt($('#unseen').text(),10) - 1;
                            if(headerNotes < 1){
                                $('#unseen').text(0);
                                $('#unseen').hide('slow');
                                $('#unseen-dot').hide('slow');
                            }
                            else{
                                $('#unseen').text(headerNotes);
                            }
                        }
                    }
                });
            }
            $(selector).scrollTop($(selector).prop('scrollHeight'));
        }
    });
    $(document).on( "click", ".btn-chat", function() {
        var datetime = dateTime();
        var text = $(this).parent().prev().val();
        var input = $('#image_file');
        var image = input[0].files[0];
        if(image != undefined || text.length > 0) {
            var submit = $(this);
            submit.prop('disabled', true);
            var toId = $(this).prev().val();
            var chat = $('#collapse_' + toId + " .chat");
            if(text.length > 0){
                conn.send(JSON.stringify({
                    message: text,
                    toId: toId
                }));
                $(this).parent().prev().val('');
                chat.append("<li class='right clearfix'>" +
                    "<span class='chat-img pull-right'>" +
                    "<span class='img-circle circle-admin flex'>" +
                    "<span>A</span>" +
                    "</span>" +
                    "</span>" +
                    "<div class='chat-body clearfix'>" +
                    "<div class='header text-right'>" +
                    "<small class='text-muted'>" +
                    "<span class='glyphicon glyphicon-time'></span>" + datetime + "</small>" +
                    "</div>" +
                    "<pre class='pull-right pre-message'>" + text + "</pre>" +
                    "</div>" +
                    "</li>");
                submit.prop('disabled', false);
            }
            if(image != undefined) {
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
                            chat.append("<li class='right clearfix'>" +
                                "<span class='chat-img pull-right'>" +
                                    "<span class='img-circle circle-admin flex'>" +
                                        "<span>A</span>" +
                                    "</span>" +
                                "</span>" +
                                "<div class='chat-body clearfix'>" +
                                    "<div class='header text-right'>" +
                                        "<small class='text-muted'>" +
                                        "<span class='glyphicon glyphicon-time'></span>" + datetime + "</small>" +
                                    "</div>" +
                                    "<div class='uploaded-image pull-right'>" +
                                        "<img src='/images/uploaded/" + newName + "'>" +
                                    "</div>" +
                                "</div>" +
                                "</li>");
                            conn.send(JSON.stringify({
                                image: newName,
                                toId: toId
                            }));
                            input.val('');
                            input.parent().removeClass("btn-primary");
                            input.parent().addClass("btn-default");
                            var block = $("#collapse_" + toId + " .panel-body");
                            block.animate({scrollTop: block.prop("scrollHeight")}, 400);
                            submit.prop('disabled', false);
                        } else{
                            alert('Something gone wrong!')
                        }
                    }
                });
            }
            var block = $("#collapse_" + toId + " .panel-body");
            block.animate({scrollTop: block.prop("scrollHeight")}, 400);
        } else{
            alert("Add message!!");
        }
    });
    $(document).on( "click", ".delChat", function(){
        var clicked = $(this);
        var chatId = clicked.parent().attr('id').split("_")[1];
        $.ajax({
            type: 'post',
            url: 'delete_conversation',
            dataType: "json",
            data: { id: chatId },
            cache: false,
            headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
            success: function (answer) {
                if(answer.success){
                    $('#user_' + chatId).remove();
                    var spanNotes = clicked.prev().find('.conversation_notes').css('display');
                    if(spanNotes == "inline"){
                        var headerNotes = parseInt($('#unseen').text(),10);
                        if(headerNotes == 1){
                            $('#unseen').hide('slow');
                            $('#unseen-dot').hide('slow');
                            $('#unseen').text(0);
                        }
                        else{
                            $('#unseen').text(headerNotes - 1);
                        }
                    }
                }
            }
        });
    });
});
