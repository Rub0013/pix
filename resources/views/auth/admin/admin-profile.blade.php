@extends('layouts.admin')

@section('title')
    Admin-profile
@endsection

@section('content')
    <div class="main-profile">
        <div id="contacts-block">
            <p class="block-header">Контакты</p>
            <div class="contacts-block-body flex">
                <div class="add-contact">
                    <p class="contact-block-label">Добавить контакт</p>
                    <div class="contacts-validation-errors"></div>
                    <div class="form-group">
                        <select id="contact-type" class="form-control">
                            <option value="phone" selected>Номер телефона</option>
                            <option value="email">E-mail</option>
                        </select>
                        <div class="contact-input-div">
                            <input class="form-control" id="contact-phone" type="text" placeholder="Введите номер телефона..">
                        </div>
                        <div class="flex tab-sd-btn-div">
                            <button id="add-contact-btn" class="btn btn-primary">Добавить</button>
                        </div>
                    </div>
                </div>
                <div class="show-contact">
                    <p class="contact-block-label">Все контакты</p>
                    <div id="contacts-container" class="scrollbar">
                        @if(count($contacts) > 0)
                            @foreach($contacts as $contact)
                                <div class="one-contact contact_{{$contact->id}} flex">
                                    <div class="flex">
                                    @if($contact->email)
                                         <i class="fa fa-envelope" aria-hidden="true"></i>
                                         <p>{{$contact->email}}</p>
                                    @else
                                         <i class="fa fa-phone-square" aria-hidden="true"></i>
                                         <p>{{$contact->phone}}</p>
                                    @endif
                                    </div>
                                    <input type="hidden" value="{{$contact->id}}">
                                    <button class="btn btn-danger btn-sm delete-contact-btn">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            @endforeach()
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('/js/admin/profile.js') }}"></script>
@endsection
