@extends('layouts.admin')

@section('title')
    Admin-map
@endsection

@section('content')
    <div class="main-map">
        <div id="admin-map-parent" class="flex">
            <div id="admin-map">
                {!! Mapper::render() !!}
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
        });
    </script>
@endsection
