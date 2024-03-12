$(function () {
    let method = 'POST';

    $('#feedbackToast').removeClass('impToastGood impToastBad');
    $('#toastInfo').text('FEEDBACK');

    $('#user-add-person').on('click', function () {
        method = 'POST';
    });

    $('#user-logout').on('click', function () {
        window.location.href = 'logout.php';
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
            $('#nameError').text('Meno je povinné');
        } else if (!nameRegex.test(name)) {
            $(this).addClass('impError');
            nameValid = false;
            $('#nameError').text('Neplatné meno');
        } else {
            $(this).removeClass('impError');
            nameValid = true;
            $('#nameError').text('');

            $('#organisation').removeClass('impError');
            $('#organisationError').text('');
        }
    });

    $('#surname').on('blur', function () {
        if (orgValid) return;

        let surname = $(this).val();
        if (surname === '') {
            $(this).addClass('impError');
            surValid = false;
            $('#surnameError').text('Priezvisko je povinné');
        } else if (!nameRegex.test(surname)) {
            $(this).addClass('impError');
            surValid = false;
            $('#surnameError').text('Neplatné priezvisko');
        } else {
            $(this).removeClass('impError');
            surValid = true;
            $('#surnameError').text('');

            $('#organisation').removeClass('impError');
            $('#organisationError').text('');
        }
    });

    $('#sex').on('blur', function () {
        let sex = $(this).val();
        if (sex === '') {
            $(this).addClass('impError');
            sexValid = false;
            $('#sexError').text('Pohlavie je povinné');
        } else if (!nameRegex.test(sex)) {
            $(this).addClass('impError');
            sexValid = false;
            $('#sexError').text('Neplatné pohlavie');
        } else {
            $(this).removeClass('impError');
            sexValid = true;
            $('#sexError').text('');
        }
    });

    $('#organisation').on('blur', function () {
        if (surValid && nameValid) return;

        let org = $(this).val();
        if (org === '') {
            orgValid = false;
            $('#organisationError').text('povinné ak nie je zadané meno+priezvisko');
            $(this).addClass('impError');
        } else {
            $(this).removeClass('impError');
            $('#organisationError').text('');
            orgValid = true;
            $('#name').removeClass('impError');
            $('#nameError').text('');
            $('#surname').removeClass('impError');
            $('#surnameError').text('');
        }
    });

    $('#birth').on('blur', function () {
        let birth = $(this).val();
        bday = birth;
        if (birth === '') {
            $(this).addClass('impError');
            bdayValid = false;
            $('#birthError').text('Narodenie je povinné');
        } else if (!numberRegex.test(birth)) {
            $(this).addClass('impError');
            bdayValid = false;
            $('#birthError').text('Neplatný rok');
        } else {
            $(this).removeClass('impError');
            bdayValid = true;
            $('#birthError').text('');
        }
        if (nobelYear != 0 && bday > nobelYear) {
            $('#year').addClass('impError');
            yearValid = false;
            $('#yearError').text('Neplatný rok');
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
            $('#deathError').text('Neplatný rok');
        } else if (bday > death) {
            $(this).addClass('impError');
            deathValid = false;
            $('#deathError').text('Neplatný rok');
        } else {
            $(this).removeClass('impError');
            deathValid = true;
            $('#deathError').text('');
        }
    });

    $('#country').on('blur', function () {
        let country = $(this).val();
        if (country === '') {
            $(this).addClass('impError');
            countryValid = false;
            $('#countryError').text('Krajina je povinná');
        } else if (!nameRegex.test(country)) {
            $(this).addClass('impError');
            countryValid = false;
            $('#countryError').text('Neplatná krajina');
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
            $(this).addClass('impError');
            yearValid = false;
            $('#yearError').text('Rok je povinný');
        } else if (!numberRegex.test(year) || year < bday) {
            $(this).addClass('impError');
            yearValid = false;
            $('#yearError').text('Neplatný rok');
        } else {
            $(this).removeClass('impError');
            yearValid = true;
            $('#yearError').text('');
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
            $('#contribution_enError').text('Príspevok je povinný');
        } else {
            $(this).removeClass('impError');
            conEN = true;
            $('#contribution_enError').text('');
        }
    });

    $('#contribution_sk').on('blur', function () {
        let contributionText = $(this).val();
        if (contributionText === '') {
            $(this).addClass('impError');
            conSK = false;
            $('#contribution_skError').text('Príspevok je povinný');
        } else {
            $(this).removeClass('impError');
            conSK = true;
            $('#contribution_skError').text('');
        }
    });

    // prize details
    $('#language_en').on('blur', function () {
        if (category !== 'Literature') {
            $(this).removeClass('impError');
            $('#language_enError').text('');
            return;
        }

        let contributionText = $(this).val();
        if (contributionText === '') {
            $(this).addClass('impError');
            lanEN = false;
            $('#language_enError').text('Jazyk je povinný');
        } else {
            $(this).removeClass('impError');
            lanEN = true;
            $('#language_enError').text('');
        }
    });

    $('#language_sk').on('blur', function () {
        if (category !== 'Literature') {
            $(this).removeClass('impError');
            $('#language_skError').text('');
            return;
        }

        let contributionText = $(this).val();
        if (contributionText === '') {
            $(this).addClass('impError');
            lanSK = false;
            $('#language_skError').text('Jazyk je povinný');
        } else {
            $(this).removeClass('impError');
            lanSK = true;
            $('#language_skError').text('');
        }
    });

    $('#genre_en').on('blur', function () {
        if (category !== 'Literature') {
            $(this).removeClass('impError');
            $('#genre_enError').text('');
            return;
        }

        let contributionText = $(this).val();
        if (contributionText === '') {
            $(this).addClass('impError');
            genEN = false;
            $('#genre_enError').text('Žáner je povinný');
        } else {
            $(this).removeClass('impError');
            genEN = true;
            $('#genre_enError').text('');
        }
    });

    $('#genre_sk').on('blur', function () {
        if (category !== 'Literature') {
            $('#genre_skError').text('');
            $(this).removeClass('impError');
            return;
        }

        let contributionText = $(this).val();
        if (contributionText === '') {
            $(this).addClass('impError');
            genSK = false;
            $('#genre_skError').text('Žáner je povinný');
        } else {
            $(this).removeClass('impError');
            genSK = true;
            $('#genre_skError').text('');
        }
    });

    /// backend stuff
    setInterval(function () {
        $.get('checkLogin.php', function (data) {
            if (data == 'not_logged_in') {
                location.reload();
            }
        });
    }, 7000);

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
                        $('#feedbackToastText').text('Úspešne pridané');
                        $('#feedbackToast').addClass('impToastGood');
                        $('#feedbackToast').removeClass('impToastBad');
                        $('#toastInfo').text('Úspech');
                    } else if (response === 'fail') {
                        $('#toastInfo').text('Neúspech');
                        $('#feedbackToastText').text('Nesprávne informácie');
                        $('#feedbackToast').removeClass('impToastGood');
                        $('#feedbackToast').addClass('impToastBad');
                    } else {
                        $('#toastInfo').text('Neúspech');
                        $('#feedbackToastText').text('Nedalo sa pridať');
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
                    $('#toastInfo').text('Fatálna chyba');
                    $('#feedbackToastText').text('Databázová chyba');
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
