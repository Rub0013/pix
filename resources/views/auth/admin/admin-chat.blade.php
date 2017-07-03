@extends('layouts.admin')

@section('title')
    Admin-chat
@endsection

@section('content')
    <div class="panel-group" id="all-chats" role="tablist" aria-multiselectable="true">
        @foreach($chats as $connection => $chat)
            <div class='panel panel-primary' id='user_{{$connection}}'>
                <div class='panel-heading conversation flex' role='tab' id='heading_{{$connection}}'>
                    <h4 class='panel-title align-center'>
                        <a class='collapsed open-close' role='button' data-toggle='collapse' data-parent='#all-chats' href='#collapse_{{$connection}}' aria-expanded='false' aria-controls='heading_{{$connection}}'>
                            Диалог # {{$connection}}
                        </a>
                        <span class="conversation_notes">0</span>
                    </h4>
                    <button class="btn btn-danger btn-sm delChat">Удалить</button>
                </div>
                <div id='collapse_{{$connection}}' class='panel-collapse collapse' role='tabpanel' aria-labelledby='heading_{{$connection}}'>
                    <div class='panel-body admin-panel scrollbar'>
                        <ul class='chat'>
                            @foreach($chat as $message)
                                @if($message->byClient == 1)
                                    @if($message->message)
                                        <li class='left clearfix'>
                                            <span class='chat-img pull-left'>
                                                <span class='img-circle circle-user flex'>
                                                    <span class="align-center">U</span>
                                                </span>
                                            </span>
                                            <div class='chat-body clearfix'>
                                                <div class='header'>
                                                    <small class='text-muted'>
                                                        <span class='glyphicon glyphicon-time'></span>
                                                        {{Carbon\Carbon::parse($message->created_at)->format('d/m/Y - H:i')}}
                                                    </small>
                                                </div>
                                                <pre class="pre-message">{{$message->message}}</pre>
                                            </div>
                                        </li>
                                    @endif
                                    @if($message->image)
                                        <li class='left clearfix'>
                                            <span class='chat-img pull-left'>
                                                <span class='img-circle circle-user flex'>
                                                    <span>U</span>
                                                </span>
                                            </span>
                                            <div class='chat-body clearfix'>
                                                <div class='header'>
                                                    <small class='text-muted'>
                                                        <span class='glyphicon glyphicon-time'></span>
                                                        {{Carbon\Carbon::parse($message->created_at)->format('d/m/Y - H:i')}}
                                                    </small>
                                                </div>
                                                <div class='uploaded-image'>
                                                    <img src='/images/uploaded/{{$message->image}}'>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @else
                                    @if($message->message)
                                        <li class='right clearfix'>
                                            <span class='chat-img pull-right'>
                                                <span class='img-circle circle-admin flex'>
                                                    <span class="align-center">A</span>
                                                </span>
                                            </span>
                                            <div class='chat-body clearfix'>
                                                <div class='header text-right'>
                                                    <small class='text-muted'>
                                                        <span class='glyphicon glyphicon-time'></span>
                                                        {{Carbon\Carbon::parse($message->created_at)->format('d/m/Y - H:i')}}
                                                    </small>
                                                </div>
                                                <pre class='pull-right pre-message'>{{$message->message}}</pre>
                                            </div>
                                        </li>
                                    @endif
                                    @if($message->image)
                                        <li class='right clearfix'>
                                            <span class='chat-img pull-right'>
                                                <span class='img-circle circle-admin flex'>
                                                    <span>A</span>
                                                </span>
                                            </span>
                                            <div class='chat-body clearfix'>
                                                <div class='header text-right'>
                                                     <small class='text-muted'>
                                                         <span class='glyphicon glyphicon-time'></span>
                                                         {{Carbon\Carbon::parse($message->created_at)->format('d/m/Y - H:i')}}
                                                     </small>
                                                </div>
                                                <div class='uploaded-image pull-right'>
                                                    <img src='/images/uploaded/{{$message->image}}'>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <div class='panel-footer'>
                        <div class='flex'>
                            <textarea class='btn-input form-control input-sm' placeholder='Ваше сообщение ...'></textarea>
                            <div class='controls-button flex'>
                                <label class="btn btn-info btn-file">
                                    Добавить изображение <input type="file" name="image" id="image_file" style="display: none" accept=".jpg,.png">
                                </label>
                                <input type='hidden' value='{{$connection}}'>
                                <button class='btn btn-warning btn-sm btn-chat'>Отправить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01">
    </div>
@endsection
