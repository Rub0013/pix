@extends('layouts.parent')

@section('title')
    Home
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/styles/home.css') }}">
@endsection

@section('content')
    <div id="home-content">
        <div id="prices" class="">
            <h1>Prices</h1>
        </div>
        <div id="contacts" class="">
            <h1>Contacts</h1>
        </div>
        <div id="reviews" class="">
            <h1>Reviews</h1>
        </div>
        <div id="map_parent" class="">
            <div id="map">
                {!! Mapper::render() !!}
            </div>
        </div>
    </div>
    <button id="go_top" type="button" class="btn btn-info bounce">
        <i class="fa fa-arrow-circle-up fa-2x" aria-hidden="true"></i>
    </button>
    <script>
        $(document).ready(function(){
            var blockHeight = $( window ).height();
            var width = screen.availWidth;
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
    </script>
@endsection