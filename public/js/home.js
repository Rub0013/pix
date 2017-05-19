$(document).ready(function(){
    var blockHeight = $( window ).height();
    $("#prices").height(blockHeight);
    $("#contacts").height(blockHeight);
    $("#reviews").height(blockHeight);
    $("#map_parent").height(blockHeight);
    $(document).scroll(function() {
        if($(window).scrollTop() + $(window).height() >= $(document).height() - 30) {
            $('#go_top').fadeIn();
        } else {
            $('#go_top').fadeOut();
        }
    });
    $(document).on( "click", ".li_home", function(event) {
        event.preventDefault();
        var id = $(this).find("a").attr('href');
        $('html,body').animate({scrollTop: $(id).offset().top},'slow');
    });
    $('#go_top').click(function() {
        $("html, body").animate({scrollTop: 0}, 500);
    });
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
        var block = $("#view-messages");
        block.append("<p class='one-message'><span style='font-weight: bold'>Me - </span>" + text + "</p>");
        block.scrollTop(block.prop('scrollHeight'));
    });
    conn.onmessage = function (e) {
        var data = JSON.parse(e.data);
        console.log(data);
        $("#view-messages").append("<p class='one-message'><span style='font-weight: bold'>Admin - </span>" + data.msg + "</p>");
    };
});
