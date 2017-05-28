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
});
