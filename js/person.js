$(function () {
    let editButton = $('#user-edit-person');
    let deleteButton = $('#user-delete-person');
    let method = 'POST';
    const personId = deleteButton.data('person-id');

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

    let modalCloseButton = $('#modal-close');

    modalCloseButton.on('click', function () {
        $('#editPersonModal').modal('hide');
    });

    $('#sendFormData').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'editPerson.php',
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
                $('#feedbackToast').addClass('impToastBad');
            },
        });
    });
});
