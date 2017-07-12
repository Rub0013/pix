$(document).ready(function(){
    $('.carousel').carousel({
        interval: 3000
    });
    $('#playButton').click(function () {
        console.log(1111);
        $('#homeCarousel').carousel('cycle');
    });
    $('#pauseButton').click(function () {
        console.log(2222);
        $('#homeCarousel').carousel('pause');
    });
    // var blockHeight = $(window).height();
    // $("#prices").height(blockHeight);
    // $("#contacts").height(blockHeight);
    // $("#reviews").height(blockHeight);
    // $("#map-parent").height(blockHeight);
    $(document).scroll(function() {
        if($(window).scrollTop() + $(window).height() >= $(document).height() - 30) {
            $('#go-top').fadeIn();
        } else {
            $('#go-top').fadeOut();
        }
    });
    $(document).on( "click", ".li_home", function(event) {
        event.preventDefault();
        var id = $(this).find("a").attr('href');
        $('html,body').animate({scrollTop: $(id).offset().top},'slow');
    });
    $('#go-top').click(function() {
        $("html, body").animate({scrollTop: 0}, 500);
    });
});
