$(function () {
    // Fetch the button with id #login
    var loginButton = $('#login');

    // Add a click event listener to the button
    loginButton.on('click', function () {});

    // Fetch the button with id #modal-close
    var modalCloseButton = $('#modal-close');

    modalCloseButton.on('click', function () {});

    // Fetch the button with id #logout
    var logoutButton = $('#user-logout');

    // Add a click event listener to the button
    logoutButton.on('click', function () {
        $.ajax({
            type: 'POST',
            url: 'php/logout.php',
            success: function () {
                // Refresh the page after logging out
                location.reload();
            },
        });
    });
});
