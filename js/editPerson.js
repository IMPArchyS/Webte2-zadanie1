$(function () {
    $('#category').on('change', function () {
        if ($(this).val() === 'Literature') {
            $('#prize-details').removeClass('d-none');
        } else {
            $('#prize-details').addClass('d-none');
        }
    });
    let deleteButton = $('#user-delete-person');
    const personId = deleteButton.data('person-id');

    // person
    let nameValid = false;
    let surValid = false;
    let bdayValid = false;
    let countryValid = false;

    let orgValid = false;

    let sexValid = false;
    let deathValid = false;

    let bday = 0;
    let nobelYear = 0;

    // prize stuff
    let yearValid = false;
    let category = 'physics';
    let conEN = true;
    let conSK = true;

    // prize details
    let lanEN = false;
    let lanSK = false;
    let genEN = false;
    let genSK = false;

    let nameRegex = /^[^0-9]+$/;
    let numberRegex = /^[0-9]{1,4}$/;

    //  person info
    $('#name').on('blur', function () {
        let name = $(this).val();

        if (name === '') {
            $(this).removeClass('impError');
            nameValid = true;
            $('#nameError').text('');
            return;
        }

        if (!nameRegex.test(name)) {
            $(this).addClass('impError');
            nameValid = false;
            $('#nameError').text('Invalid name');
        } else {
            $(this).removeClass('impError');
            nameValid = true;
            $('#nameError').text('');
        }
    });

    $('#surname').on('blur', function () {
        let surname = $(this).val();
        if (surname === '') {
            $(this).removeClass('impError');
            surValid = true;
            $('#surnameError').text('');
            return;
        }
        if (!nameRegex.test(surname)) {
            $(this).addClass('impError');
            surValid = false;
            $('#surnameError').text('Invalid surname');
        } else {
            $(this).removeClass('impError');
            surValid = true;
            $('#surnameError').text('');
        }
    });

    $('#sex').on('blur', function () {
        let sex = $(this).val();
        if (sex === '') {
            $(this).removeClass('impError');
            sexValid = true;
            $('#sexError').text('');
            return;
        }

        if (!nameRegex.test(sex)) {
            $(this).addClass('impError');
            sexValid = false;
            $('#sexError').text('Invalid sex');
        } else {
            $(this).removeClass('impError');
            sexValid = true;
            $('#sexError').text('');
        }
    });

    $('#organisation').on('blur', function () {
        $(this).removeClass('impError');
        $('#organisationError').text('');
        orgValid = true;
    });

    $('#birth').on('blur', function () {
        let birth = $(this).val();
        bday = birth;
        if (birth === '') {
            $(this).removeClass('impError');
            bdayValid = true;
            $('#birthError').text('');
            $('#year').trigger('blur');
            return;
        }
        if (!numberRegex.test(birth)) {
            $(this).addClass('impError');
            bdayValid = false;
            $('#birthError').text('Invalid date');
        } else {
            $(this).removeClass('impError');
            bdayValid = true;
            $('#birthError').text('');
        }
        if (nobelYear != 0 && bday > nobelYear) {
            $('#year').addClass('impError');
            yearValid = false;
            $('#yearError').text('Invalid date');
        }
        $('#year').trigger('blur');
    });

    $('#death').on('blur', function () {
        let death = $(this).val();
        if (death === '') {
            deathValid = true;
            return;
        }

        if (!numberRegex.test(death)) {
            $(this).addClass('impError');
            deathValid = false;
            $('#deathError').text('Invalid date');
        } else if (bday > death) {
            $(this).addClass('impError');
            deathValid = false;
            $('#deathError').text('Invalid date');
        } else {
            $(this).removeClass('impError');
            deathValid = true;
            $('#deathError').text('');
        }
    });

    $('#country').on('blur', function () {
        let country = $(this).val();
        if (country === '') {
            $(this).removeClass('impError');
            countryValid = true;
            $('#countryError').text('');
            return;
        }

        if (!nameRegex.test(country)) {
            $(this).addClass('impError');
            countryValid = false;
            $('#countryError').text('Invalid country');
        } else {
            $(this).removeClass('impError');
            countryValid = true;
            $('#countryError').text('');
        }
    });

    // prize info
    $('#year').on('blur', function () {
        let year = $(this).val();
        nobelYear = year;
        if (year === '') {
            $(this).removeClass('impError');
            yearValid = true;
            $('#yearError').text('');
            return;
        }
        if (!numberRegex.test(year) || year < bday) {
            $(this).addClass('impError');
            yearValid = false;
            $('#yearError').text('Invalid date');
        } else {
            $(this).removeClass('impError');
            yearValid = true;
            $('#yearError').text('');
        }
    });

    $('#category').on('change', function () {
        category = $(this).val();
    });

    $('#contribution_en').on('blur', function () {});

    $('#contribution_sk').on('blur', function () {});

    // prize details
    $('#language_en').on('blur', function () {});

    $('#language_sk').on('blur', function () {});

    $('#genre_en').on('blur', function () {});

    $('#genre_sk').on('blur', function () {});

    $('#editFormData').on('submit', function (event) {
        event.preventDefault();
        $('#name').trigger('blur');
        $('#surname').trigger('blur');
        $('#sex').trigger('blur');
        $('#organisation').trigger('blur');
        $('#birth').trigger('blur');
        $('#death').trigger('blur');
        $('#country').trigger('blur');

        $('#year').trigger('blur');
        $('#contribution_en').trigger('blur');
        $('#contribution_sk').trigger('blur');

        $('#language_en').trigger('blur');
        $('#language_sk').trigger('blur');
        $('#genre_en').trigger('blur');
        $('#genre_sk').trigger('blur');

        console.log('nameValid: ' + nameValid);
        console.log('surValid: ' + surValid);
        console.log('orgValid: ' + orgValid);
        console.log('sexValid: ' + sexValid);
        console.log('bdayValid: ' + bdayValid);
        console.log('countryValid: ' + countryValid);

        console.log('yearValid: ' + yearValid);
        console.log('conEN: ' + conEN);
        console.log('conSK: ' + conSK);

        let name = $('#name').val();
        let surname = $('#surname').val();
        let sex = $('#sex').val();
        let org = $('#organisation').val();
        let birth = $('#birth').val();
        let death = $('#death').val();
        let country = $('#country').val();
        let year = $('#year').val();
        let category = $('#category').val();
        let contribution_en = $('#contribution_en').val();
        let contribution_sk = $('#contribution_sk').val();
        let language_en = $('#language_en').val();
        let language_sk = $('#language_sk').val();
        let genre_en = $('#genre_en').val();
        let genre_sk = $('#genre_sk').val();

        $.ajax({
            type: 'POST',
            url: 'editPerson.php',
            data: {
                _method: 'PATCH',
                _personId: personId,
                name: name,
                surname: surname,
                sex: sex,
                org: org,
                birth: birth,
                death: death,
                country: country,
                year: year,
                category: category,
                contribution_en: contribution_en,
                contribution_sk: contribution_sk,
                language_en: language_en,
                language_sk: language_sk,
                genre_en: genre_en,
                genre_sk: genre_sk,
            },
            success: function (response) {
                console.log('SCS');
                console.log('PATCH');
                console.log(response);
                if (response === 'success') {
                    $('#feedbackToastText').text('SUCCESSFULY INSERTED');
                    $('#feedbackToast').addClass('impToastGood');
                    $('#feedbackToast').removeClass('impToastBad');
                    $('#toastInfo').text('SUCCESS');
                } else if (response === 'fail') {
                    $('#toastInfo').text('FAILURE');
                    $('#feedbackToastText').text('INVALID PERSON INFORMATION');
                    $('#feedbackToast').removeClass('impToastGood');
                    $('#feedbackToast').addClass('impToastBad');
                } else {
                    $('#toastInfo').text('FAILURE');
                    $('#feedbackToastText').text('COULDNT INSERT');
                    $('#feedbackToast').removeClass('impToastGood');
                    $('#feedbackToast').addClass('impToastBad');
                }
                $('#editPersonModal').modal('hide');
                $('#feedbackToast').toast('show');
                setTimeout(function () {
                    $('#feedbackToast').toast('hide');
                    //location.reload();
                }, 2000);
            },
            error: function () {
                console.log('FFF');
                $('#toastInfo').text('FATAL FAILURE');
                $('#feedbackToastText').text('INTERNAL DB ERROR');
                $('#feedbackToast').removeClass('impToastGood');
                $('#feedbackToast').addClass('impToastBad');
                $('#feedbackToast').toast('show');
                setTimeout(function () {
                    $('#feedbackToast').toast('hide');
                    //location.reload();
                }, 2000);
            },
        });
    });
});
