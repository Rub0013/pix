function showResponse(res) {
    if (!$('.alert-box').length && !$('#responseMessage').length) {
        if (res.success) {
            $('<div id="responseMessage" class="alert alert-success" >' + res.message + '</div>').prependTo($('.notify-top-popup')).delay(3000).fadeOut(1000, function () {
                $('#responseMessage').remove();
            });
        } else {
            $('<div id="responseMessage" class="alert alert-danger" >' + res.message + '</div>').prependTo($('.notify-top-popup')).delay(3000).fadeOut(1000, function () {
                $('#responseMessage').remove();
            });
        }
    }
};
