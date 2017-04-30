$(document).ready(function(){
    var conn = new WebSocket("ws://localhost:8080");
    conn.onopen = function (e) {
        console.log("Connected");
    };
    $(document).on( "click", "#submit-send-message", function() {
        var text = $("#message").val();
        conn.send(JSON.stringify({
            message: text
        }));
        $("#message").val('');
        $("#view-messages").append("<p class='one-message'>" + text + "</p>");
    });
    conn.onmessage = function (e) {
        var data = JSON.parse(e.data);
        $("#view-messages").append("<p class='one-message'>" + data.message + "</p>");
    };
});
