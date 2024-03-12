$(function () {
    $('#login').on('click', function () {});

    $('#modal-close').on('click', function () {});

    $('#mainPageButton').attr('href', 'index.php');

    $('#user-logout').on('click', function () {
        window.location.href = 'php/logout.php';
    });

    $('#cookieButton').on('click', function () {
        $.ajax({
            url: 'php/acceptCookie.php',
            method: 'POST',
            success: function (response) {
                $('#cookieModal').addClass('d-none');
                $('#cookieModalBackdrop').addClass('d-none');
                console.log('uspech');
            },
            error: function (xhr, status, error) {
                console.log('error');
            },
        });
    });
});
