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
        <p>{{ $bodyMessage }}</p>
    </div>
    @if($viber)
        <div>
            <p>{{ $viber }}</p>
        </div>
    @endif
    @if($whatsapp)
        <div>
            <p>{{ $whatsapp }}</p>
        </div>
    @endif
</body>
</html>