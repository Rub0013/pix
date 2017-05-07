@extends('layouts.admin')

@section('title')
    Admin-chat
@endsection

@section('script')
    <script src="{{ asset('/js/admin-chat.js') }}"></script>
@endsection

@section('content')
    <h1>Chat</h1>
    <div class="panel-group" id="all-chats" role="tablist" aria-multiselectable="true">

    </div>
@endsection
