$(function () {
    let method = 'POST';

    $('#feedbackToast').removeClass('impToastGood impToastBad');
    $('#toastInfo').text('FEEDBACK');

    $('#user-add-person').on('click', function () {
        method = 'POST';
    });

    $('#user-logout').on('click', function () {
        window.location.href = '../index.php';
    });

    $('#category').on('change', function () {
        if ($(this).val() === 'Literature') {
            $('#prize-details').removeClass('d-none');
        } else {
            $('#prize-details').addClass('d-none');
        }
    });

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
    let conEN = false;
    let conSK = false;

    // prize details
    let lanEN = false;
    let lanSK = false;
    let genEN = false;
    let genSK = false;

    let nameRegex = /^[^0-9]+$/;
    let numberRegex = /^[0-9]{1,4}$/;

    //  person info
    $('#name').on('blur', function () {
        if (orgValid) return;

        let name = $(this).val();
        if (name === '') {
            $(this).addClass('impError');
            nameValid = false;
            $('#nameError').removeClass('d-none').text('Name is required');
        } else if (!nameRegex.test(name)) {
            $(this).addClass('impError');
            nameValid = false;
            $('#nameError').removeClass('d-none').text('Invalid name');
        } else {
            $(this).removeClass('impError');
            nameValid = true;
            $('#nameError').addClass('d-none').text('');

            $('#organisation').removeClass('impError');
            $('#organisationError').addClass('d-none').text('');
        }
    });

    $('#surname').on('blur', function () {
        if (orgValid) return;

        let surname = $(this).val();
        if (surname === '') {
            $(this).addClass('impError');
            surValid = false;
            $('#surnameError').removeClass('d-none').text('Surname is required');
        } else if (!nameRegex.test(surname)) {
            $(this).addClass('impError');
            surValid = false;
            $('#surnameError').removeClass('d-none').text('Invalid surname');
        } else {
            $(this).removeClass('impError');
            surValid = true;
            $('#surnameError').addClass('d-none').text('');

            $('#organisation').removeClass('impError');
            $('#organisationError').addClass('d-none').text('');
        }
    });

    $('#sex').on('blur', function () {
        let sex = $(this).val();
        if (sex === '') {
            $(this).addClass('impError');
            sexValid = false;
            $('#sexError').removeClass('d-none').text('Sex is required');
        } else if (!nameRegex.test(sex)) {
            $(this).addClass('impError');
            sexValid = false;
            $('#sexError').removeClass('d-none').text('Invalid sex');
        } else {
            $(this).removeClass('impError');
            sexValid = true;
            $('#sexError').addClass('d-none').text('');
        }
    });

    $('#organisation').on('blur', function () {
        if (surValid && nameValid) return;

        let org = $(this).val();
        if (org === '') {
            orgValid = false;
            $('#organisationError').removeClass('d-none').text('No name & surname Organisation is required');
            $(this).addClass('impError');
        } else {
            $(this).removeClass('impError');
            $('#organisationError').addClass('d-none').text('');
            orgValid = true;
            $('#name').removeClass('impError');
            $('#nameError').addClass('d-none').text('');
            $('#surname').removeClass('impError');
            $('#surnameError').addClass('d-none').text('');
        }
    });

    $('#birth').on('blur', function () {
        let birth = $(this).val();
        bday = birth;
        if (birth === '') {
            $(this).addClass('impError');
            bdayValid = false;
            $('#birthError').removeClass('d-none').text('Birth is required');
        } else if (!numberRegex.test(birth)) {
            $(this).addClass('impError');
            bdayValid = false;
            $('#birthError').removeClass('d-none').text('Invalid date');
        } else {
            $(this).removeClass('impError');
            bdayValid = true;
            $('#birthError').addClass('d-none').text('');
        }
        if (nobelYear != 0 && bday > nobelYear) {
            $('#year').addClass('impError');
            yearValid = false;
            $('#yearError').removeClass('d-none').text('Invalid date');
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
            $('#deathError').removeClass('d-none').text('Invalid date');
        } else if (bday > death) {
            $(this).addClass('impError');
            deathValid = false;
            $('#deathError').removeClass('d-none').text('Invalid date');
        } else {
            $(this).removeClass('impError');
            deathValid = true;
            $('#deathError').addClass('d-none').text('');
        }
    });

    $('#country').on('blur', function () {
        let country = $(this).val();
        if (country === '') {
            $(this).addClass('impError');
            countryValid = false;
            $('#countryError').removeClass('d-none').text('Country is required');
        } else if (!nameRegex.test(country)) {
            $(this).addClass('impError');
            countryValid = false;
            $('#countryError').removeClass('d-none').text('Invalid country');
        } else {
            $(this).removeClass('impError');
            countryValid = true;
            $('#countryError').addClass('d-none').text('');
        }
    });

    // prize info
    $('#year').on('blur', function () {
        let year = $(this).val();
        nobelYear = year;
        if (year === '') {
            $(this).addClass('impError');
            yearValid = false;
            $('#yearError').removeClass('d-none').text('Year is required');
        } else if (!numberRegex.test(year) || year < bday) {
            $(this).addClass('impError');
            yearValid = false;
            $('#yearError').removeClass('d-none').text('Invalid date');
        } else {
            $(this).removeClass('impError');
            yearValid = true;
            $('#yearError').addClass('d-none').text('');
        }
    });

    $('#category').on('change', function () {
        category = $(this).val();
    });

    $('#contribution_en').on('blur', function () {
        let contributionText = $(this).val();
        if (contributionText === '') {
            $(this).addClass('impError');
            conEN = false;
            $('#contribution_enError').removeClass('d-none').text('Contribution is required');
        } else {
            $(this).removeClass('impError');
            conEN = true;
            $('#contribution_enError').addClass('d-none').text('');
        }
    });

    $('#contribution_sk').on('blur', function () {
        let contributionText = $(this).val();
        if (contributionText === '') {
            $(this).addClass('impError');
            conSK = false;
            $('#contribution_skError').removeClass('d-none').text('Contribution is required');
        } else {
            $(this).removeClass('impError');
            conSK = true;
            $('#contribution_skError').addClass('d-none').text('');
        }
    });

    // prize details
    $('#language_en').on('blur', function () {
        if (category !== 'Literature') {
            $(this).removeClass('impError');
            $('#language_enError').addClass('d-none').text('');
            return;
        }

        let contributionText = $(this).val();
        if (contributionText === '') {
            $(this).addClass('impError');
            lanEN = false;
            $('#language_enError').removeClass('d-none').text('Language is required');
        } else {
            $(this).removeClass('impError');
            lanEN = true;
            $('#language_enError').addClass('d-none').text('');
        }
    });

    $('#language_sk').on('blur', function () {
        if (category !== 'Literature') {
            $(this).removeClass('impError');
            $('#language_skError').addClass('d-none').text('');
            return;
        }

        let contributionText = $(this).val();
        if (contributionText === '') {
            $(this).addClass('impError');
            lanSK = false;
            $('#language_skError').removeClass('d-none').text('Language is required');
        } else {
            $(this).removeClass('impError');
            lanSK = true;
            $('#language_skError').addClass('d-none').text('');
        }
    });

    $('#genre_en').on('blur', function () {
        if (category !== 'Literature') {
            $(this).removeClass('impError');
            $('#genre_enError').addClass('d-none').text('');
            return;
        }

        let contributionText = $(this).val();
        if (contributionText === '') {
            $(this).addClass('impError');
            genEN = false;
            $('#genre_enError').removeClass('d-none').text('Genre is required');
        } else {
            $(this).removeClass('impError');
            genEN = true;
            $('#genre_enError').addClass('d-none').text('');
        }
    });

    $('#genre_sk').on('blur', function () {
        if (category !== 'Literature') {
            $('#genre_skError').addClass('d-none').text('');
            $(this).removeClass('impError');
            return;
        }

        let contributionText = $(this).val();
        if (contributionText === '') {
            $(this).addClass('impError');
            genSK = false;
            $('#genre_skError').removeClass('d-none').text('Genre is required');
        } else {
            $(this).removeClass('impError');
            genSK = true;
            $('#genre_skError').addClass('d-none').text('');
        }
    });

    /// backend stuff
    setInterval(function () {
        $.get('checkLogin.php', function (data) {
            if (data == 'not_logged_in') {
                location.reload();
            }
        });
    }, 1000);

    $('#sendFormData').on('submit', function (event) {
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

        let validOther = false;
        if (category === 'Literature') {
            if (genSK && genEN && lanEN && lanSK) validOther = true;
            else validOther = false;
        } else validOther = true;

        if (
            ((nameValid && surValid) || orgValid) &&
            sexValid &&
            bdayValid &&
            deathValid &&
            countryValid &&
            yearValid &&
            conEN &&
            conSK &&
            validOther
        ) {
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
                url: 'addToDB.php',
                data: {
                    _method: method,
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
                    console.log(method);
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
        }
    });
});
