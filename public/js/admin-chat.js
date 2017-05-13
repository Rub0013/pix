$(document).ready(function(){
    $.ajax({
        type: 'post',
        url: 'open_admin_panel',
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
                        $('#heading_' + fromId).find('.conversation_notes').text(notes);
                        $('#heading_' + fromId).find('.conversation_notes').show('slow');
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
    $(document).on( "click", ".open-close", function() {
        var sId =  $(this).parent().parent().next().attr('id');
        var selector = "#" + sId + " .panel-body";
        var connectionId = sId.split("_")[1];
        var clickEvent = $("#collapse_" + connectionId).attr( "aria-expanded" );
        if(clickEvent == 'true'){
            var spanNotes = $('#heading_' + connectionId).find('.conversation_notes');
            if(spanNotes.text() > 0 ){
                spanNotes.text(0);
                $('#heading_' + connectionId).find('.conversation_notes').hide("slow");
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
        if(text.length > 0){
            var toId = $(this).prev().val();
            conn.send(JSON.stringify({
                message: text,
                toId: toId
            }));
            $(this).parent().prev().val('');
            var chat = $('#collapse_' + toId).find(".panel-body").find(".chat");
            chat.append("<li class='right clearfix'>" +
                    "<span class='chat-img pull-right'>" +
                        "<img src='http://placehold.it/50/FA6F57/fff&text=ADM' alt='Admin Avatar' class='img-circle' />" +
                    "</span>" +
                    "<div class='chat-body clearfix'>" +
                        "<div class='header right-side'>" +
                            "<small class='text-muted'>" +
                            "<span class='glyphicon glyphicon-time'></span>" + datetime + "</small>" +
                        "</div>" +
                        "<p class='right-side'>" + text + "</p>" +
                    "</div>" +
                "</li>");
        }
        else{
            alert("Add message!!");
        }
    });
    conn.onmessage = function (e) {
        $('#unseen-dot').show('slow');
        var datetime = dateTime();
        var data = JSON.parse(e.data);
        var message = data.msg;
        var fromId = data.from_id;
        var currentChat = $("#user_" + fromId);
        if(currentChat.length != 0){
            var chat = $('#collapse_' + fromId).find(".panel-body").find(".chat");
            chat.append("<li class='left clearfix'>" +
                    "<span class='chat-img pull-left'>" +
                        "<img src='http://placehold.it/50/55C1E7/fff&text=U' alt='User Avatar' class='img-circle' />" +
                    "</span>" +
                    "<div class='chat-body clearfix'>" +
                        "<div class='header'>" +
                            "<small class='text-muted'>" +
                                "<span class='glyphicon glyphicon-time'></span>" + datetime +
                            "</small>" +
                        "</div>" +
                        "<p>" + message + "</p>" +
                    "</div>" +
            "</li>");
            var currentNotesSpan = $('#heading_' + fromId).find('.conversation_notes');
            // console.log(currentNotesSpan.text());
            // console.log(parseInt(currentNotesSpan.text(),10));
            var spanNotes = parseInt(currentNotesSpan.text(),10) + 1;
            if(spanNotes == 1){
                var headerNotes = parseInt($('#unseen').text(),10) + 1;
                $('#unseen').text(headerNotes);
                $('#unseen').show('slow');
            }
            currentNotesSpan.text(spanNotes);
            currentNotesSpan.show('slow');
        }
        else{
            $("#all-chats").append("<div class='panel panel-primary' id='user_" + fromId + "'>" +
                    "<div class='panel-heading conversation' role='tab' id='heading_" + fromId + "'>" +
                        "<h4 class='panel-title'>" +
                            "<a class='collapsed open-close' role='button' data-toggle='collapse' data-parent='#all-chats' href='#collapse_" + fromId + "' aria-expanded='false' aria-controls='heading_" + fromId + "'>" +
                                'Диалог # ' + fromId +
                            "</a>" +
                        "</h4>" +
                        "<span class='conversation_notes'>1</span>" +
                    "</div>" +
                    "<div id='collapse_" + fromId + "' class='panel-collapse collapse' role='tabpanel' aria-labelledby='heading_" + fromId + "'>" +
                        "<div class='panel-body'>" +
                            "<ul class='chat'>" +
                                "<li class='left clearfix'>" +
                                    "<span class='chat-img pull-left'>" +
                                        "<img src='http://placehold.it/50/55C1E7/fff&text=U' alt='User Avatar' class='img-circle' />" +
                                    "</span>" +
                                    "<div class='chat-body clearfix'>" +
                                        "<div class='header'>" +
                                            "<small class='text-muted'>" +
                                                "<span class='glyphicon glyphicon-time'></span>" + datetime +
                                            "</small>" +
                                        "</div>" +
                                        "<p>" + message + "</p>" +
                                    "</div>" +
                                "</li>" +
                            "</ul>" +
                        "</div>" +
                        "<div class='panel-footer'>" +
                            "<div class='input-group'>" +
                                "<input class='btn-input form-control input-sm' type='text' placeholder='Ваше сообщение ...' />" +
                                "<span class='input-group-btn'>" +
                                    "<input type='hidden' value='" + fromId + "'>" +
                                    "<button class='btn btn-warning btn-sm btn-chat'>Отправить</button>" +
                                "</span>" +
                            "</div>" +
                        "</div>" +
                    "</div>" +
                "</div>");
            $('#heading_' + fromId).find('.conversation_notes').show('slow');
            var headerNotes = parseInt($('#unseen').text(),10) + 1;
            $('#unseen').text(headerNotes);
            $('#unseen').show('slow');
        }
    };
});
