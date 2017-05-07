@extends('layouts.parent')

@section('title')
    Home
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/styles/home.css') }}">
@endsection

@section('script')
    <script src="{{ asset('/js/home.js') }}"></script>
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
@endsection