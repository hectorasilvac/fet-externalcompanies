$(function($)
{
    var height = ($(window).height() - 60);
    $('#welcome_biomechanical').css('height', height + 'px');

    //BUILD DESKTOP
    $.fn.editable.defaults.mode = 'inline';

    var validate = $('#form_add').validate(
    {
        rules:
        {
            axa_3:
            {
                required: true,
                maxlength: 2
            },
            axa_4:
            {
                required: true
            },
            axa_5:
            {
                required: true
            },
            axa_6:
            {
                required: true
            },
            axa_7:
            {
                required: true
            },
            axa_8:
            {
                maxlength: 20
            },
            axa_9:
            {
                required: true
            },
            axa_10:
            {
                required: true,
                maxlength: 4,
                digits: true
            },
            axa_11:
            {
                required: true,
                maxlength: 4,
                digits: true
            },
            axa_12:
            {
                required: true
            },
            axa_13:
            {
                maxlength: 20
            },
            axa_14:
            {
                required: true
            },
            axa_15:
            {
                required: true
            },
            axa_17:
            {
                required: true,
                maxlength: 2
            },
            axa_18:
            {
                required: true
            },
            axa_19:
            {
                required: true
            },
            axa_20:
            {
                required: true
            },
            axa_21:
            {
                required: true
            },
            axa_22:
            {
                required: true
            },
            axa_23:
            {
                required: true
            },
            axa_24:
            {
                required: true
            },
            axa_25:
            {
                required: true
            },
            axa_26:
            {
                required: true
            },
            axa_27:
            {
                required: true
            },
            axa_28:
            {
                required: true
            },
            axa_29:
            {
                required: true
            },
            axa_30:
            {
                required: true
            },
            axa_31:
            {
                required: true
            },
            axa_34:
            {
                required: true
            },
            axa_35:
            {
                required: true
            },
            axa_36:
            {
                required: true
            },
            axa_37:
            {
                required: true
            },
            axa_38:
            {
                required: true,
                maxlength: 11
            },
            axa_39:
            {
                required: true
            }
        },
        messages:
        {
            axa_3:
            {
                required: 'Pregunta requerida, por favor revisala.',
                maxlength: 'Solo se permiten 2 caracteres.'
            },
            axa_4:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_5:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_6:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_7:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_8:
            {
                maxlength: 'Solo se permiten 20 caracteres.'
            },
            axa_9:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_10:
            {
                required: 'Pregunta requerida, por favor revisala.',
                maxlength: 'Solo se permiten 4 caracteres.',
                digits: 'Solo se permiten numeros enteros.'
            },
            axa_11:
            {
                required: 'Pregunta requerida, por favor revisala.',
                maxlength: 'Solo se permiten 4 caracteres.',
                digits: 'Solo se permiten numeros enteros.'
            },
            axa_12:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_13:
            {
                maxlength: 'Solo se permiten 20 caracteres.'
            },
            axa_14:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_15:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_17:
            {
                required: 'Pregunta requerida, por favor revisala.',
                maxlength: 'Solo se permiten 2 caracteres.'
            },
            axa_18:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_19:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_20:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_21:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_22:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_23:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_24:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_25:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_26:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_27:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_28:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_29:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_30:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_31:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_34:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_35:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_36:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_37:
            {
                required: 'Pregunta requerida, por favor revisala.'
            },
            axa_38:
            {
                required: 'Pregunta requerida, por favor revisala.',
                maxlength: 'Solo se permiten 11 caracteres.'
            },
            axa_39:
            {
                required: 'Pregunta requerida, por favor revisala.'
            }
        },
        errorPlacement: function (error, element)
        {
            error.addClass('invalid-feedback');

            switch (element.prop('type'))
            {
                case 'id_worker_applicant':
                    error.insertAfter(element.next('.select2-container'));
                    break;
                default:
                    error.insertAfter(element.parent());
            }
        }
    });

    $('#btn_confirm_add').on('click', function()
    {
        $('#form_add').submit();
    });

    $('#btn_show_form').on('click', function()
    {
        var participate_valid = JSON.parse(participate);

        if (participate_valid.data) 
        {
            $('#welcome_biomechanical').removeClass('d-flex');
            $('#welcome_biomechanical').addClass('d-none');
            $('#biomechanical').removeClass('d-none');
        }
        else
        {
            modal_alert_and_continue(participate_valid.data, participate_valid.message);
        }
    });

    $('#form_add').ajaxForm(
    {
        dataType:  'json',
        success:   add,
        beforeSubmit: function()
        {
            $('#loading').removeClass('d-none');
        }
    });

    $('input[name=axa_7]').on('change', function() 
    {
        if ($("input[name=axa_7]:checked").val() == 'B') 
        {
            $(".axa_8").prop("checked", false);
            $(".axa_8").prop("disabled", true);
            $("#axa_8").addClass("disabled-biomechanical");
        }
        else
        {
            $("#axa_8").removeClass("disabled-biomechanical");
            $(".axa_8").prop("disabled", false);
        }
    });


    $('input[name=axa_12]').on('change', function() 
    {
        if ($("input[name=axa_12]:checked").val() == 'B') 
        {
            $("#axa_13").val("");
            $("#axa_13").prop("disabled", true);
        }
        else
        {
            $("#axa_13").prop("disabled", false);
        }
    });

    $('input[name=axa_15]').on('change', function() 
    {
        if ($("input[name=axa_15]:checked").val() == 'B') 
        {
            $("input[name=axa_16]").prop("checked", false);
            $("input[name=axa_16]").prop("disabled", true);
            $("#axa_16").addClass("disabled-biomechanical");
        }
        else
        {
            $("#axa_16").removeClass("disabled-biomechanical");
            $("input[name=axa_16]").prop("disabled", false);
        }
    });

    $(".axa_32").on('click', function() 
    {  
        if($(".axa_32").is(':checked')) 
        {
            $("input[name=axa_33]").prop("disabled", false);
            $("input[name=axa_34]").prop("disabled", false);
            $("input[name=axa_35]").prop("disabled", false);

            $("#axa_33").removeClass("disabled-biomechanical");
            $("#axa_34_35").removeClass("disabled-biomechanical");
        } 
        else 
        {
            $("#axa_33").addClass("disabled-biomechanical");
            $("#axa_34_35").addClass("disabled-biomechanical");
            $("#axa_final").addClass("disabled-biomechanical");
            $("#axa_40").addClass("disabled-biomechanical");

            $("input[name=axa_33]").prop("checked", false);
            $("input[name=axa_33]").prop("disabled", true);

            $("input[name=axa_34]").prop("checked", false);
            $("input[name=axa_34]").prop("disabled", true);

            $("input[name=axa_35]").prop("checked", false);
            $("input[name=axa_35]").prop("disabled", true);

            $("input[name=axa_36]").prop("checked", false);
            $("input[name=axa_36]").prop("disabled", true);

            $("input[name=axa_37]").prop("checked", false);
            $("input[name=axa_37]").prop("disabled", true);

            $(".axa_38").prop("checked", false);
            $(".axa_38").prop("disabled", true);

            $("input[name=axa_39]").prop("checked", false);
            $("input[name=axa_39]").prop("disabled", true);

            $("input[name=axa_40]").prop("checked", false);
            $("input[name=axa_40]").prop("disabled", true);
        }
    });

    $("input[name=axa_35]").on('click', function() 
    {  
        if ($("input[name=axa_35]:checked").val() == 'B') 
        {
            $("#axa_final").addClass("disabled-biomechanical");
            $("#axa_40").addClass("disabled-biomechanical");

            $("input[name=axa_36]").prop("checked", false);
            $("input[name=axa_36]").prop("disabled", true);

            $("input[name=axa_37]").prop("checked", false);
            $("input[name=axa_37]").prop("disabled", true);

            $(".axa_38").prop("checked", false);
            $(".axa_38").prop("disabled", true);

            $("input[name=axa_39]").prop("checked", false);
            $("input[name=axa_39]").prop("disabled", true);

            $("input[name=axa_40]").prop("checked", false);
            $("input[name=axa_40]").prop("disabled", true);
        } 
        else 
        {
            $("#axa_final").removeClass("disabled-biomechanical");
            $("input[name=axa_36]").prop("disabled", false);
            $("input[name=axa_37]").prop("disabled", false);
            $(".axa_38").prop("disabled", false);
            $("input[name=axa_39]").prop("disabled", false);
        }
    });

    $('input[name=axa_39]').on('change', function() 
    {
        if ($("input[name=axa_39]:checked").val() == 'B') 
        {
            $("input[name=axa_40]").prop("checked", false);
            $("input[name=axa_40]").prop("disabled", true);
            $("#axa_40").addClass("disabled-biomechanical");
        }
        else
        {
            $("input[name=axa_40]").prop("disabled", false);
            $("#axa_40").removeClass("disabled-biomechanical");
        }
    });
});

function add(response)
{
    iziToast.show(
    {
        backgroundColor: (response.data ? '#23BF08' : '#DC3545'),
        icon: (response.data ? 'far fa-check-circle' : 'far fa-times-circle'),
        iconColor: '#FFF',
        maxWidth: 420,
        message: '<br/><br/>' + response.message,
        messageColor: '#FFF',
        position: 'topRight',
        timeout: 5000,
        title: (response.data ? 'Información' : 'Alerta'),
        titleColor: '#FFF'
    });

    setTimeout(function()
    {
        $('#loading').addClass('d-none');
        location.href = $path_finish;
    }, 1000);
}