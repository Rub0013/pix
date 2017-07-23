$(document).ready(function(){
    $('.carousel').carousel({
        interval: 3000
    });
    $('#playButton').click(function () {
        $('#homeCarousel').carousel('cycle');
    });
    $('#pauseButton').click(function () {
        $('#homeCarousel').carousel('pause');
    });
    // var blockHeight = $(window).height();
    // $("#prices").height(blockHeight);
    // $("#contacts").height(blockHeight);
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
    $(document).on('click', '.devices-list > li', function () {
        var allClasses = $(this).attr('class');
        if(!allClasses.split(" ")[2]) {
            var selector = '#' + allClasses.split(" ")[1];
            var activeDevice = $('.active-device');
            var activeList = $('.active-price-list');
            activeDevice.removeClass('active-device');
            activeList.removeClass('active-price-list');
            $(this).addClass('active-device');
            $(selector).addClass('active-price-list');
        }
    });
    $('#go-top').click(function() {
        $("html, body").animate({scrollTop: 0}, 500);
    });
});
