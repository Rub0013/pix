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
        @foreach($chats as $connection => $chat)
            <div class='panel panel-primary' id='user_{{$connection}}'>
                <div class='panel-heading' role='tab' id='heading_{{$connection}}'>
                    <h4 class='panel-title'>
                        <a class='collapsed open-close' role='button' data-toggle='collapse' data-parent='#all-chats' href='#collapse_{{$connection}}' aria-expanded='false' aria-controls='heading_{{$connection}}'>
                            Диалог # {{$connection}}
                        </a>
                    </h4>
                </div>
                <div id='collapse_{{$connection}}' class='panel-collapse collapse' role='tabpanel' aria-labelledby='heading_{{$connection}}'>
                    <div class='panel-body'>
                        <ul class='chat'>
                            @foreach($chat as $message)
                                    @if($message->byClient == 1)
                                    <li class='left clearfix'>
                                        <span class='chat-img pull-left'>
                                            <img src='http://placehold.it/50/55C1E7/fff&text=U' alt='User Avatar' class='img-circle' />
                                        </span>
                                        <div class='chat-body clearfix'>
                                            <div class='header'>
                                                <small class='text-muted'>
                                                    <span class='glyphicon glyphicon-time'></span>{{Carbon\Carbon::parse($message->created_at)->format('d/m/Y - H:i')}}
                                                </small>
                                            </div>
                                            <p>{{$message->message}}</p>
                                        </div>
                                    </li>
                                    @else
                                    <li class='right clearfix'>
                                        <span class='chat-img pull-right'>
                                            <img src='http://placehold.it/50/FA6F57/fff&text=ADM' alt='Admin Avatar' class='img-circle' />
                                        </span>
                                        <div class='chat-body clearfix'>
                                            <div class='header right-side'>
                                                <small class='text-muted'>
                                                    <span class='glyphicon glyphicon-time'></span>{{Carbon\Carbon::parse($message->created_at)->format('d/m/Y - H:i')}}
                                                </small>
                                            </div>
                                            <p class='right-side'>{{$message->message}}</p>
                                        </div>
                                    </li>
                                    @endif
                            @endforeach
                        </ul>
                    </div>
                    <div class='panel-footer'>
                        <div class='input-group'>
                            <input class='btn-input form-control input-sm' type='text' placeholder='Ваше сообщение ...' />
                            <span class='input-group-btn'>
                                    <input type='hidden' value='{{$connection}}'>
                                    <button class='btn btn-warning btn-sm btn-chat'>Отправить</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
