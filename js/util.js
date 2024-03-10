$(function () {
    $('#login').on('click', function () {});

    $('#modal-close').on('click', function () {});

    $('#mainPageButton').attr('href', 'index.php');

    $('#user-logout').on('click', function () {
        window.location.href = 'php/logout.php';
    });
});
