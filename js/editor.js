$(function () {
    // Fetch the button with id #login
    var addButton = $('#user-add-person');
    var editButton = $('#user-edit-person');
    var deleteButton = $('#user-delete-person');

    // Add a click event listener to the button
    addButton.on('click', function () {
        $('#editPersonModalLabel').text('Add person');
        $('#editPersonModal').modal('show');
    });

    editButton.on('click', function () {
        $('#editPersonModalLabel').text('Edit person');
        $('#editPersonModal').modal('show');
    });

    deleteButton.on('click', function () {
        $('#editPersonModalLabel').text('Delete person');
        $('#editPersonModal').modal('show');
    });

    // Fetch the button with id #modal-close
    var modalCloseButton = $('#modal-close');

    modalCloseButton.on('click', function () {
        $('#editPersonModal').modal('hide');
    });

    // Fetch the button with id #logout
    var logoutButton = $('#user-logout');

    // Add a click event listener to the button
    logoutButton.on('click', function () {
        $.ajax({
            type: 'POST',
            url: 'logout.php',
            success: function () {
                window.location.href = '/';
            },
        });
    });

    setInterval(function () {
        $.get('checkLogin.php', function (data) {
            if (data == 'not_logged_in') {
                location.reload();
            }
        });
    }, 1000);
});
