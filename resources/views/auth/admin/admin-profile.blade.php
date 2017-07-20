@extends('layouts.admin')

@section('title')
    Admin-profile
@endsection

@section('content')
    <div class="main-profile">
        <div id="contacts-block">
            <p class="block-header">Контакты</p>
            <div class="flex">
                <div class="add-contact">
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
                <div class="show-class">
                    ljmolni
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $(document).on('change','#contact-type',function () {
                var contactType = $(this).val();
                changeContactType(contactType);
            });
            function changeContactType(contact) {
                var contactInputBlock = $('.contact-input-div').empty();
                if(contact == 'phone') {
                    contactInputBlock.append("<input class='form-control' id='contact-phone' type='text' placeholder='Введите номер телефона.'>");
                }
                if(contact == 'email') {
                    contactInputBlock.append("<input class='form-control' id='contact-email' type='text' placeholder='Введите email.'>");
                }
            }
            $(document).on('click','#add-contact-btn',function () {
                var emailInput = $('#contact-email');
                var phoneInput = $('#contact-phone');
                var email = emailInput.val();
                var phone = phoneInput.val();
                var sendingData = {};
                if(phone) {
                    sendingData.type = 'phone';
                    sendingData.value = phone;
                }
                if(email) {
                    sendingData.type = 'email';
                    sendingData.value = email;
                }
                if(!jQuery.isEmptyObject(sendingData)) {
                    $.ajax({
                        type: 'post',
                        url: 'add_contact',
                        data: sendingData,
                        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                        success: function (answer) {
                            if(answer.validationError) {
                                showValidationErrors(answer.message, 'contacts');
                            } else {
                                if(answer.success) {

                                }
                                showResponse(answer);
                                emailInput.val('');
                                phoneInput.val('');
                            }
                            console.log(answer);
                        }
                    });
                }
            });
        });
    </script>
@endsection
