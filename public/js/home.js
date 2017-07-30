$(document).ready(function(){
    $('.carousel').carousel({
        interval: 3000
    });
    $(document).on( "click", "#playButton", function() {
        var buttonContainer = $('#carouselButtons');
        $('.carousel').carousel('cycle');
        buttonContainer.empty();
        buttonContainer.append("<span id='pauseButton' class='glyphicon glyphicon-pause'></span>");
    });
    $(document).on( "click", "#pauseButton", function() {
        var buttonContainer = $('#carouselButtons');
        $('.carousel').carousel('pause');
        buttonContainer.empty();
        buttonContainer.append("<span id='playButton' class='glyphicon glyphicon-play'></span>");
    });
    $(document).scroll(function() {
        var changeNavStart = $('.nav-above').height();
        var offset = $(document).scrollTop();
        var navMenu = $('.nav-home');
        var bestOffers = $('#best-offers');
        var pricesBlock = $('#prices');
        if(offset > changeNavStart) {
            if(bestOffers.length > 0) {
                bestOffers.addClass('offset-scrolled');
            } else {
                pricesBlock.addClass('offset-scrolled');
            }
            navMenu.addClass('nav-home-fixed');
        } else {
            navMenu.removeClass('nav-home-fixed');
            if(bestOffers.length > 0) {
                bestOffers.removeClass('offset-scrolled');
            } else {
                pricesBlock.removeClass('offset-scrolled');
            }
        }
        if(offset + $(window).height() >= $(document).height() - 30) {
            $('#go-top').fadeIn();
        } else {
            $('#go-top').fadeOut();
        }
    });
    $(document).on( "click", ".li_home", function(event) {
        event.preventDefault();
        var id = $(this).find("a").attr('href');
        $('html,body').animate({
            scrollTop: $(id).offset().top - $('.nav-home-fixed').height()
        },500);
    });
    $(document).on( "click", ".price-footer > span", function() {
        $('html,body').animate({scrollTop: $('#contacts').offset().top},'slow');
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
