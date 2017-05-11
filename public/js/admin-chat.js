$(document).ready(function(){
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
        var datetime = dateTime();
        var data = JSON.parse(e.data);
        var message = data.msg;
        var fromId = data.from_id;
        var currentChat = $("#all-chats").find("#user_" + fromId);
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
        }
        else{
            $("#all-chats").append("<div class='panel panel-primary' id='user_" + fromId + "'>" +
                    "<div class='panel-heading' role='tab' id='heading_" + fromId + "'>" +
                        "<h4 class='panel-title'>" +
                            "<a class='collapsed' role='button' data-toggle='collapse' data-parent='#all-chats' href='#collapse_" + fromId + "' aria-expanded='false' aria-controls='heading_" + fromId + "'>" +
                                'Диалог #' + fromId +
                            "</a>" +
                        "</h4>" +
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
                                            "<span class='glyphicon glyphicon-time'></span>" + datetime + "</small>" +
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
        }
    };
});
