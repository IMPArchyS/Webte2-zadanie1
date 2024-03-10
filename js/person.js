$(function () {
    let editButton = $('#user-edit-person');
    let deleteButton = $('#user-delete-person');
    let method = 'POST';
    const personId = deleteButton.data('person-id');

    editButton.on('click', function () {
        $('#editPersonModalLabel').text('Edit person');
        $('#editPersonContainer').removeClass('d-none');
        $('#editPersonModal').modal('show');
        $(this).hide();
        method = 'PATCH';
    });

    deleteButton.on('click', function () {
        $('#editPersonModalLabel').text('Delete person');
        $('#editPersonModal').modal('show');
        method = 'DELETE';
    });

    let modalCloseButton = $('#modal-close');

    modalCloseButton.on('click', function () {
        $('#editPersonModal').modal('hide');
    });

    /// backend stuff
    setInterval(function () {
        $.get('checkLogin.php', function (data) {
            if (data == 'not_logged_in' && $('#editPersonContainer').length) {
                location.reload();
            }
        });
    }, 1000);

    $('#sendFormData').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'removePerson.php',
            data: {
                _method: method,
                _personId: personId,
            },
            success: function (response) {
                console.log('SCS');
                console.log(method);
                //$('#feedbackToastText').text(response);
                console.log(response);
                if (response === 'success') {
                    $('#editPersonModal').modal('hide');
                    $('#feedbackToast').addClass('impToastGood');
                    $('#feedbackToast').removeClass('impToastBad');
                    $('#toastInfo').text('SUCCESS');
                    $('#feedbackToast').toast('show');
                    setTimeout(function () {
                        $('#feedbackToast').toast('hide');
                        location.href = '../index.php';
                    }, 2500);
                } else {
                    $('#editPersonModal').modal('hide');
                    $('#feedbackToast').removeClass('impToastGood');
                    $('#toastInfo').text('FAILURE');
                    $('#feedbackToast').toast('show');
                    $('#feedbackToast').addClass('impToastBad');
                    setTimeout(function () {
                        $('#feedbackToast').toast('hide');
                    }, 2500);
                }
            },
            error: function () {
                console.log('FFF');
                $('#toastInfo').text('FAILURE');
                $('#feedbackToast').removeClass('impToastGood');
                $('#feedbackToast').addClass('impToastBad');
            },
        });
    });
});
