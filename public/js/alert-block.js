function showResponse(res) {
    var alertBox = $('.notify-top-popup');
    alertBox.empty();
    if (res.success) {
        $('<div class="alert alert-success" >' + res.message + '</div>').prependTo(alertBox).delay(3000).fadeOut(1000, function () {
            alertBox.empty();
        });
    } else {
        $('<div class="alert alert-danger" >' + res.message + '</div>').prependTo(alertBox).delay(3000).fadeOut(1000, function () {
            alertBox.empty();
        });
    }
}

