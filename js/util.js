$(function () {
    $('#login').on('click', function () {});

    $('#modal-close').on('click', function () {});

    $('#mainPageButton').attr('href', 'index.php');

    $('#user-logout').on('click', function () {
        window.location.href = 'php/logout.php';
    });

    $('#acceptCookies').on('click', function () {
        $('#cookieToast').addClass('d-none');
        document.cookie = 'cookiePopupShown=true; expires=' + new Date(new Date().getTime() + 86400 * 30).toUTCString() + '; path=/';
    });
});
