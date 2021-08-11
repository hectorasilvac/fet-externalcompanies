$(function()
{
    $('.overflow-y-auto').mCustomScrollbar({
        theme:"minimal-blue"
    });

    $('.btn_terms_conditions').on('click', function()
    {
        $('#modal_terms_conditions').iziModal({
            title: 'Términos y condiciones',
            icon: 'fas fa-gavel',
            headerColor: '#e0922f',
            zindex: 9999,
            top: 150,
            bottom: 20,
            onClosed: function()
            {
                $('#modal_terms_conditions').iziModal('destroy');
            },
            onOpening: function()
            {
            }
        });

        $('#modal_terms_conditions').iziModal('open');
    });

    var validate_login_aspirants = $('#form_login_aspirants').validate(
    {
        rules: 
        {
            user: 'required',
            password: 'required'
        },
        messages: 
        {
            user: 'Por favor ingresa tu nombre de usuario.',
            password: 'Por favor ingresa tu contraseña.'
        },
        errorPlacement: function(error, element) 
        {
            error.insertAfter(element);
        }
    });

    var validate_register_aspirants = $('#form_register_aspirants').validate(
    {
        lang: 'es',
        rules: 
        {
            name_aspirant: 
            {
                required: true,
                lettersonly_es: true,
                maxlength: 50,
            },
            first_last_name_aspirant: 
            {
                required: true,
                lettersonly_es: true,
                maxlength: 30,
            },
            second_last_name_aspirant: 
            {
                required: true,
                lettersonly_es: true,
                maxlength: 30,
            },
            email_aspirant: 
            {
                required: true,
                email: true,
                maxlength: 50,
            },
            phone_aspirant: 
            {
                required: true,
                digits: true,
                maxlength: 12,
            },
            user_aspirant: 
            {
                required: true,
                letters_user: true,
                maxlength: 30,
            },
            password_aspirant: 
            {
                required: true,
                minlength: 8,
                maxlength: 15,
            },
            terms_conditions: 
            {
                required: true
            }
        },
        messages: 
        {
            name_aspirant: 
            {
                required: 'Este campo es obligatorio.',
                lettersonly_es: 'Solo se permiten letras.',
                maxlength: 'Solo se permiten 50 caracteres.',
            },
            first_last_name_aspirant: 
            {
                required: 'Este campo es obligatorio.',
                lettersonly_es: 'Solo se permiten letras.',
                maxlength: 'Solo se permiten 30 caracteres.',
            },
            second_last_name_aspirant: 
            {
                required: 'Este campo es obligatorio.',
                lettersonly_es: 'Solo se permiten letras.',
                maxlength: 'Solo se permiten 30 caracteres.',
            },
            email_aspirant: 
            {
                required: 'Este campo es obligatorio.',
                email: 'Por favor ingresa un correo electrónico válido.',
                maxlength: 'Solo se permiten 50 caracteres.',
            },
            phone_aspirant: 
            {
                required: 'Este campo es obligatorio.',
                digits: 'Solo se permiten números.',
                maxlength: 'Solo se permiten 12 caracteres.',
            },
            user_aspirant: 
            {
                required: 'Este campo es obligatorio.',
                letters_user: 'No se permiten caracateres.',
                maxlength: 'Solo se permiten 30 caracteres.',
            },
            password_aspirant: 
            {
                required: 'Este campo es obligatorio.',
                minlength: 'La contraseña debe tener 8 caracteres como minímo.',
                maxlength: 'La contraseña debe tener 15 caracteres como máximo.',
            },
            terms_conditions: 
            {
                required: 'Este campo es obligatorio.'
            }
        },
        errorPlacement: function(error, element) 
        {
            error.addClass('invalid-feedback');

            switch (element.prop('name'))
            {
                case 'terms_conditions':
                    error.insertAfter(element.parent());
                    break;
                case 'name_security_question[]':
                    error.insertAfter(element.next('.select2-container'));
                    break;
                default:
                    error.insertAfter(element);
            }
        }
    });

    $('#btn_modal_admin').on('click', function()
    {
        $('#modal_admin').iziModal({
            title: 'Iniciar sesión - Administrador',
            icon: 'fas fa-user-circle',
            headerColor: '#224978',
            zindex: 9999,
            onClosed: function()
            {
                $('#modal_admin').iziModal('destroy');
            },
            onOpening: function()
            {
                var validate_login_admin = $('#form_login_admin').validate(
                {
                    rules: 
                    {
                        user: 'required',
                        password: 'required'
                    },
                    messages: 
                    {
                        user: 'Por favor ingresa tu usuario',
                        password: 'Por favor ingresa la contraseña'
                    }
                });

                validate_login_admin.resetForm();
                $('#form_login_admin')[0].reset();

                $('#btn_login_admin').on('click', function()
                {
                    $('#form_login_admin').submit();
                });

                $('#modal_admin').keydown(function(e)
                {
                    if (e.keyCode == 13)
                    {
                        $('#form_login_admin').submit();
                    }
                });

                $('#form_login_admin').ajaxForm(
                {
                    dataType:  'json',
                    success:   success_admin,
                    beforeSubmit: function() 
                    { 
                        $('#btn_login_admin').attr('style', 'cursor: wait;');
                        $('#btn_login_admin').html('<i class="fas fa-spinner fa-spin"></i>  Procesando');  
                    }
                });

                $('#btn_close_admin').on('click', function()
                {
                    $('#modal_admin').iziModal('close');
                });
            }
        });

        $('#modal_admin').iziModal('open');
    });


    $('#btn_start').on('click', function(){
        $('.btn-start').addClass('d-none');
        $('#btn_modal_admin').addClass('d-none');

        $('div[data-id="welcome_container"]').removeClass('d-flex'); 
        $('div[data-id="welcome_container"]').addClass('d-none');  

        $('#login_aspirants').removeClass('d-none');
    });

    $('#btn_back_login').on('click', function(){
        $('.btn-start').removeClass('d-none');
        $('#btn_modal_admin').removeClass('d-none');

        $('div[data-id="welcome_container"]').removeClass('d-none'); 
        $('div[data-id="welcome_container"]').addClass('d-flex');  

        $('#login_aspirants').addClass('d-none');
    });

    $('#btn_back_register').on('click', function(){
        $('.form-aspirant-1').removeClass('d-none');
        $('.form-aspirant-2').addClass('d-none');

        $('#login_aspirants').removeClass('d-none');
        $('#register_aspirants').addClass('d-none');
    });

    $('#btn_register_aspirants').on('click', function() 
    {
        $('#form_aspirant_2').html('');

        validate_login_aspirants.resetForm();
        $('#form_login_aspirants')[0].reset();

        validate_register_aspirants.resetForm();
        $('#form_register_aspirants')[0].reset();

        $('#loading').removeClass('d-none');

        $.ajax({
            type: 'POST',
            url: $path_draw_security_questions,
            data: {
                name_parameter: 'LIMIT_QUESTIONS_ASPIRANT'
            },
            dataType: 'json',
            success: function (response)
            {
                $('#form_aspirant_2').append(response);

                setTimeout(function(){
                    var ids = [];

                    $('.select_security_question').select2({
                        theme: 'bootstrap4',
                        width: '100%',
                        language: 'es',
                        placeholder: 'Pregunta de seguridad',
                        allowClear: true,
                        ajax: {
                            url: $path_security_questions,
                            dataType: 'json',
                            delay: 250,
                            data: function(params)
                            {
                                var id = $(this).val();

                                ids = $.grep(ids, function(value) {
                                  return value != id;
                                });

                                return {
                                    q: params.term,
                                    page: params.page || 1,
                                    id: ids
                                };
                            },
                            processResults: function(data, params)
                            {
                                var page = params.page || 1;
                                return {
                                    results: $.map(data.items, function (item, index)
                                    {
                                        return {
                                            id: item.id,
                                            text: item.text
                                        }
                                    }),
                                    pagination: {
                                        more: (page * 10) <= data.total_count
                                    }
                                };
                            }
                        },
                        escapeMarkup: function (markup) { return markup; },
                    }).on('change', function (e)
                    {
                        $(this).valid();

                        var id = $(this).val();

                        if (id) 
                        {
                            ids.push(id);
                        }
                    });
                }, 2000);

                $('.form-aspirant-1').removeClass('d-none');
                $('.form-aspirant-2').addClass('d-none');

                $('#login_aspirants').addClass('d-none');
                $('#register_aspirants').removeClass('d-none');

                $('#loading').addClass('d-none');
            },
            error: function () {
                modal_alert(false, 'Error de conexión.');
            }
        });
    });

    $('#btn_next_aspirants').on('click', function() 
    {
        if ($('#form_register_aspirants').valid()) 
        {
            $('.form-aspirant-1').addClass('d-none');
            $('.form-aspirant-2').removeClass('d-none');
        }
    });

    $('#form_login_aspirants').ajaxForm(
    {
        dataType:  'json',
        success:   login_aspirants,
        beforeSubmit: function() 
        {
            $('#btn_login_aspirant').attr('style', 'cursor: wait;');
            $('#btn_login_aspirant').html('<i class="fas fa-spinner fa-spin"></i>  Procesando');
        }
    });

    $('#form_register_aspirants').ajaxForm(
    {
        dataType:  'json',
        success:   register_aspirants,
        beforeSubmit: function() 
        { 
            $('.btn-register').attr('style', 'cursor: wait;');
            $('.btn-register').html('<i class="fas fa-spinner fa-spin"></i>  Procesando');  
        }
    });

    $("#btn_forget_aspirants").click(function()
    {
        $('#modal_forgot_aspirant').iziModal('open');
    });

    $("#modal_forgot_aspirant").iziModal(
    {
        title: 'Olvidaste tu contraseña',
        subtitle: '<label class="fonts-terms wd-100p-force">No te preocupes, ingresa los datos a continuación.<br/>Y recibiras en tu correo una contraseña temporal.</label>',
        icon: 'fas fa-unlock',
        headerColor: '#e0922f',
        width: 400,
        setZindex: 99999,
        overlayColor: 'rgba(0, 0, 0, 0.8)',
        onClosing: function()
        {
            var validate_forgot_admin = $('#form_forgot_aspirant').validate();

            validate_forgot_admin.resetForm();
            $('#form_forgot_aspirant')[0].reset();
        },
        onOpening: function(modal)
        {
            $('#modal_admin').iziModal('close');

            var validate_forgot_admin = $('#form_forgot_aspirant').validate(
            {
                rules:
                {
                    user_forgot:
                    {
                        required: true
                    },
                    email_forgot: 
                    {
                        required: true,
                        email: true
                    }
                },
                messages:
                {
                    user_forgot:
                    {
                        required: 'Por favor ingresa tu usuario.'
                    },
                    email_forgot: 
                    {
                        required: 'Por favor ingresa tu correo electrónico.',
                        email: 'El correo que ingresaste no es valido.'
                    }
                }
            });

            $('#btn_forgot_confirm_admin').on('click', function() 
            {
                $('#form_forgot_aspirant').submit();
            });

            $('#form_forgot_aspirant').ajaxForm(
            {
                dataType:  'json',
                success:   forgot_password,
                beforeSubmit: function() 
                {
                    $('#btn_forgot_confirm_admin').attr('style', 'cursor: wait;');
                    $('#btn_forgot_confirm_admin').html('<i class="fas fa-spinner fa-spin"></i>  Procesando');
                }
            });
        }
    });


});

function success_admin(response)
{
    $('#btn_login_admin').attr('style', 'cursor: auto;');
    $('#btn_login_admin').html('Ingresar');

    if (response.data)
    {
        window.location.href = $path_dashboard;
    }
    else
    {
        modal_alert(response.data, response.message);
    }
}

function register_aspirants(response)
{
    $('.btn-register').attr('style', 'cursor: auto;');
    $('.btn-register').html('Enviar');

    if (response.data)
    {
        modal_alert(response.data, response.message);

        $('#login_aspirants').removeClass('d-none');
        $('#register_aspirants').addClass('d-none');
    }
    else
    {
        modal_alert(response.data, response.message);

        $('.form-aspirant-1').removeClass('d-none');
        $('.form-aspirant-2').addClass('d-none');
    }
}

function forgot_password(response) 
{
    $('#btn_forgot_confirm_admin').attr('style', 'cursor: auto;');
    $('#btn_forgot_confirm_admin').html('Enviar');

    if (response.data)
    {
        $('#modal_forgot_aspirant').iziModal('close');
    }

    modal_alert(response.data, response.message);
    
}

function login_aspirants(response)
{
    $('#btn_login_aspirant').attr('style', 'cursor: auto;');
    $('#btn_login_aspirant').html('Ingresar');

    if (response.data)
    {
        $("#modal_security_aspirant").iziModal(
        {
            title: 'Pregunta de seguridad',
            subtitle: '<label class="text-center wd-100p-force">Responde la siguiente pregunta para verificar tu identidad.</label>',
            icon: 'fas fa-question',
            headerColor: '#e0922f',
            width: 400,
            setZindex: 99999,
            overlayColor: 'rgba(0, 0, 0, 0.8)',
            onClosing: function()
            {
                var validate_security_question = $('#form_security_aspirant').validate();

                validate_security_question.resetForm();
                $('#form_security_aspirant')[0].reset();

                $('#modal_security_aspirant').iziModal('destroy');
            },
            onOpening: function(modal)
            {
                $('#label_security_aspirant').text(response.question.name_security_question);
                $('#id_security_aspirant').val(response.question.id_aspirant_security_question);

                var validate_security_question = $('#form_security_aspirant').validate(
                {
                    rules:
                    {
                        value_security_question:
                        {
                            required: true
                        }
                    },
                    messages:
                    {
                        value_security_question:
                        {
                            required: 'Por favor ingresa tu respuesta.'
                        }
                    }
                });

                $('#btn_security_aspirant').on('click', function() 
                {
                    $('#form_security_aspirant').submit();
                });

                $('#form_security_aspirant').ajaxForm(
                {
                    dataType:  'json',
                    success:   verify_security_questions,
                    beforeSubmit: function() 
                    { 
                        $('#btn_security_aspirant').attr('style', 'cursor: wait;');
                        $('#btn_security_aspirant').html('<i class="fas fa-spinner fa-spin"></i>  Procesando');
                    }
                });

            }
        });

        $('#modal_security_aspirant').iziModal('open');
    }
    else
    {
        modal_alert(response.data, response.message);
    }
}

function verify_security_questions(response)
{
    $('#btn_security_aspirant').attr('style', 'cursor: auto;');
    $('#btn_security_aspirant').html('Enviar');

    if (response.location) 
    {
        window.location.href = response.location;
    }
    else
    {
        modal_alert(response.data, response.message);
    }

}

function modal_alert(data, message)
{  
    iziToast.show(
    {
        timeout: 5000,
        backgroundColor: (data ? '#23BF08' : '#DC3545'),
        titleColor: '#FFF',
        messageColor: '#FFF',
        iconColor: '#FFF',
        maxWidth: 420,
        position: 'topRight',
        icon: (data ? 'far fa-check-circle' : 'far fa-times-circle'),
        title: (data ? 'Información' : 'Alerta'),
        message: '<br/><br/>' + message
    });
}