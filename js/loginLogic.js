$(function () {
    let emailValid = false;
    let passValid = false;

    $('#email').on('blur', function () {
        const value = $(this).val();
        const emailRegex = /^[^\.\s][\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
        if (!emailRegex.test(value)) {
            $(this).addClass('impError');
            $('#emailError').removeClass('d-none');
            emailValid = false;
        } else if (value === '') {
            $(this).addClass('impError');
            $('#emailError').removeClass('d-none');
            emailValid = false;
        } else {
            $(this).removeClass('impError');
            $('#emailError').addClass('d-none');
            emailValid = true;
        }
    });

    $('#password').on('blur', function () {
        const value = $(this).val();
        if (value === '') {
            $(this).addClass('impError');
            $('#passwordError').removeClass('d-none');
            passValid = false;
        } else {
            $(this).removeClass('impError');
            $('#passwordError').addClass('d-none');
            passValid = true;
        }
    });
    $('form').on('submit', function (event) {
        if (!emailValid || !passValid) {
            event.preventDefault();
            if (!passValid) {
                $('#password').addClass('impError');
                $('#passwordError').removeClass('d-none');
            }
            if (!emailValid) {
                $('#email').addClass('impError');
                $('#emailError').removeClass('d-none');
            }
        }
    });
});
