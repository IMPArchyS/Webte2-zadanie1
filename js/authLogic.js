$(function () {
    let codeValid = false;

    $('#2fa').on('blur', function () {
        const value = $(this).val();
        if (value === '') {
            $(this).addClass('impError');
            $('#2faError').removeClass('d-none');
            codeValid = false;
        } else {
            $(this).removeClass('impError');
            $('#2faError').addClass('d-none');
            codeValid = true;
        }
    });

    $('form').on('submit', function (event) {
        if (!codeValid) {
            event.preventDefault();
            if (!codeValid) {
                $('#2fa').addClass('impError');
                $('#2faError').removeClass('d-none');
            }
        }
    });
});
