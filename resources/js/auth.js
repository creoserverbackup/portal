$('.auth-form__btn').on('click', function (event) {
    event.preventDefault();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    console.log(" accounts/auth/login/connect accounts/auth/login/connect")
    console.log(process.env.MIX_APP_URL)

    $.ajax({
        type: 'get',
        url: '/accounts/auth/login/connect',
        data: {
            '_token': $('input[name=u-name]').val(),
        },
        success: function (data) {
            if (data.connect == undefined || data.connect == false) {
                addMessage('Paniek! Het ziet er naar uit dat u geen connectie meer heeft  internet!')
            } else {
                $('[name=auth]').trigger('submit');
            }
        },
        error: function (jqXHR, exception) {
            if (jqXHR.status === 0 || jqXHR.status == 500) {
                // alert('Not connect. Verify Network.');
                // } else if (jqXHR.status == 500) {
                if (navigator.onLine) {
                    addMessage('Sorry, onze server light er even uit. Misschien zijn we de omgeving aan het updaten. komt u alstublieft later terug of neemt u contact met ons op')
                } else {
                    addMessage('Paniek! Het ziet er naar uit dat u geen connectie meer heeft')
                }
            } else if (exception === 'parsererror') {
                // alert('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                // alert('Time out error.');
            } else if (exception === 'abort') {
                // alert('Ajax request aborted.');
            } else {
                // alert('Uncaught Error. ' + jqXHR.responseText);
            }
        }
    });
})

function addMessage(message) {
    $('.auth-form__info-error').show()
    $('.auth-form__info-error-message').html(message);

    setTimeout(function () {
        $('.auth-form__info-error').hide()
        $('.auth-form__info-error-message').html('')
    }, 10000);

}
