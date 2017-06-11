<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email from user</title>
</head>
<body>
    <h1>Пользователь - {{ $userName }}</h1>
    <div>
        <h3>Сообщение</h3>
        <p>{{ $bodyMessage }}</p>
    </div>
    <hr>
    @if($phoneNumber)
        <div>
            <span style="font-weight: bold">
                Номер телефона -
            </span>
            <span>{{ $phoneNumber }}</span>
        </div>
    @endif
    @if($viber)
        <div>
            <span style="font-weight: bold">
                Номер Viber -
            </span>
            <span>{{ $viber }}</span>
        </div>
    @endif
    @if($whatsapp)
        <div>
            <span style="font-weight: bold">
                Номер WhatApp -
            </span>
            <span>{{ $whatsapp }}</span>
        </div>
    @endif
    @if($callTime)
        <div>
            <span style="font-weight: bold">
                Время звонка -
            </span>
            <span>{{ $callTime }}</span>
        </div>
    @endif
</body>
</html>