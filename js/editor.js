$(function () {
    // Fetch the button with id #login
    let addButton = $('#user-add-person');
    let editButton = $('#user-edit-person');
    let deleteButton = $('#user-delete-person');

    let method = 'POST';

    $('#feedbackToast').removeClass('impToastGood impToastBad');
    $('#toastInfo').text('FEEDBACK');
    // Add a click event listener to the button
    addButton.on('click', function () {
        $('#editPersonModalLabel').text('Add person');
        $('#editPersonModal').modal('show');
        method = 'POST';
    });

    editButton.on('click', function () {
        $('#editPersonModalLabel').text('Edit person');
        $('#editPersonModal').modal('show');
        method = 'PATCH';
    });

    deleteButton.on('click', function () {
        $('#editPersonModalLabel').text('Delete person');
        $('#editPersonModal').modal('show');
        method = 'DELETE';
    });

    // Fetch the button with id #modal-close
    let modalCloseButton = $('#modal-close');

    modalCloseButton.on('click', function () {
        $('#editPersonModal').modal('hide');
    });

    // Fetch the button with id #logout
    let logoutButton = $('#user-logout');

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

    $('#sendFormData').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'addToDB.php',
            data: {
                _method: method, // Send the method as a string
                // other data to send
            },
            success: function (response) {
                console.log('SCS');
                console.log(method);
                $('#feedbackToastText').text(response);
                $('#editPersonModal').modal('hide');
                $('#feedbackToast').addClass('impToastGood');
                $('#toastInfo').text('SUCCESS');
                $('#feedbackToast').toast('show');
                setTimeout(function () {
                    $('#feedbackToast').toast('hide');
                }, 1500);
                // Handle success
            },
            error: function () {
                console.log('FFF');
                $('#toastInfo').text('FAILURE');
                $('#feedbackToast').addClass('impToastBad');
                // Handle error
            },
        });
    });
});
