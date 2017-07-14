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
function showValidationErrors(message, block) {
    var errorBlock = $('.' + block + '-validation-errors');
    errorBlock.empty();
    $('<div class="alert alert-danger" >' + message + '</div>').prependTo(errorBlock).delay(2500).slideUp(1000, function () {
        errorBlock.empty();
    });
}

