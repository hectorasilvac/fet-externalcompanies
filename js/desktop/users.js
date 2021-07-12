$(function($)
{
    //BUILD - DESKTOP
    $.fn.editable.defaults.mode = 'inline';

    $('#default_table').DataTable(
    {
        language:
        {
            sUrl: 'resources/lib/datatables/Spanish.json',
        },
        info: true,
        lengthChange: true,
        processing: true,
        serverSide: true,
        ajax:
        {
            url: $path_view,
            dataType: 'json',
            type: 'POST'
        },
        columnDefs:
        [
            {
                targets: [0],
                data: 'number',
                render: function(data, type, row)
                {
                    return '<div class="text-center">' + data + '</div>';
                }
            },
            {
                targets: [1],
                data: 'name_user',
                render: function(data, type, row)
                {
                    return '<span data-name="name_user" data-type="text" data-pk="' + row.id_user + '" data-url="' + $path_edit + '">' + data + '</span><br>'
                    + '<span data-name="lastname_user" data-type="text" data-pk="' + row.id_user + '" data-url="' + $path_edit + '">' + row.lastname_user + '</span>';
                }
            },
            {
                targets: [2],
                data: 'name_role',
                render: function(data, type, row)
                {
                    return '<span data-name="id_role" data-type="select2" data-pk="' + row.id_user + '" data-url="' + $path_edit + '" data-value="' + row.id_role + '">' + data + '</span>';
                }
            },
            {
                targets: [3],
                data: 'user',
                render: function(data, type, row)
                {
                    return '<span data-name="user" data-type="text" data-pk="' + row.id_user + '" data-url="' + $path_edit + '">' + data + '</span>';
                }
            },
            {
                targets: [4],
                data: 'email_user',
                render: function(data, type, row)
                {
                    return '<span data-name="email_user" data-type="text" data-pk="' + row.id_user + '" data-url="' + $path_edit + '">' + data + '</span>';
                }
            },
            {
                targets: [5],
                data: 'date_keepalive',
                render: function(data, type, row)
                {
                    var html = 'No ha ingresado.';
                    if (data != null) 
                    {
                        html = data;
                    }

                    return html;
                }
            },
            {
                targets: [6],
                data: 'id_aspirant',
                render: function(data, type, row)
                {
                    return '<div class="text-center"><span data-name="id_aspirant" data-type="select2" data-pk="' + row.id_user + '" data-url="' + $path_edit + '">' + (row.name_aspirant != null ? row.name_aspirant : '--') + '</span></div>';
                }
            },
            {
                targets: [7],
                data: 'id_user',
                orderable: false,
                render: function(data, type, row)
                {
                    var content = '<div class="span-center">';

                    if (act_display)
                    {
                        if (row.flag_display == '1')
                        {
                            content += '<a data-toggle="tooltip" data-placement="top" title="Inhabilitar" href="javascript:void(0)" class="display-row pd-x-5-force" data-id="' + data + '" data-display="0"><i class="fas fa-eye"></i></a>';
                        }
                        else
                        {
                            content += '<a data-toggle="tooltip" data-placement="top" title="Habilitar" href="javascript:void(0)" class="display-row pd-x-5-force" data-id="' + data + '" data-display="1"><i class="fas fa-eye-slash"></i></a>';   
                        }
                    }

                    if (act_edit)
                    {
                        content +=  '<a data-toggle="tooltip" data-placement="top" title="Editar clave" href="javascript:void(0)" class="edit-row pd-x-5-force" data-id="' + data + '"><i class="fas fa-user-lock"></i></a>'
                                +   '<a data-toggle="tooltip" data-placement="top" title="Editar permisos" href="javascript:void(0)" class="flags-row pd-x-5-force" data-id="' + data + '"><i class="fas fa-flag"></i></a>';
                    }

                    if (act_drop)
                    {
                        content += '<a data-toggle="tooltip" data-placement="top" title="Eliminar" href="javascript:void(0)" class="remove-row pd-x-5-force" data-id="' + data + '"><i class="fas fa-trash"></i></a>';   
                    }

                    if (act_trace)
                    {
                        content += '<a data-toggle="tooltip" data-placement="top" title="Trazabilidad" href="javascript:void(0)" class="trace-row pd-x-5-force" data-id="' + data + '" onclick="trace(\'' + $path_trace + '\', \'id_user\',' + data + ')"><i class="fas fa-history"></i></a>'; 
                    }

                    return content + '</div>';
                },
                visible: (act_display || act_edit || act_drop || act_trace ? true : false)
            }
        ],
        drawCallback: function(settings)
        {
            var rows = this.fnGetData();
            var inputSearch = $('.dataTables_filter input').val();

            if (rows.length == 0)
            {
                $('#btn_export_xlsx').removeAttr('href');
            }
            else
            {
                if (inputSearch != '')
                {
                    $('#btn_export_xlsx').attr('href', $path_export_xlsx + '/?search=' + inputSearch);
                }
                else
                {
                    $('#btn_export_xlsx').attr('href', $path_export_xlsx);
                }
            }

            if (act_edit)
            {
                $('#default_table td span[data-type="text"]').editable(
                {
                    validate: function(value)
                    {
                        if (value === null || value.trim() === '')
                        {
                            return 'Campo obligatorio.';
                        }

                        switch($(this).attr('data-name'))
                        {
                            case 'name_user':

                                if (value.length > 100)
                                {
                                    return 'Solo se permiten 100 caracteres.';
                                }

                                if(value.match(/[^a-zA-ZáéíóúñÑÁÉÍÓÚ ]/g))
                                {
                                    return 'Solo se permiten letras.';
                                }

                                break;

                            case 'lastname_user':

                                if (value.length > 100)
                                {
                                    return 'Solo se permiten 100 caracteres.';
                                }

                                if(value.match(/[^a-zA-ZáéíóúñÑÁÉÍÓÚ ]/g))
                                {
                                    return 'Solo se permiten letras.';
                                }

                                break;

                            case 'user':

                                if (value.length > 50)
                                {
                                    return 'Solo se permiten 50 caracteres.';
                                }

                                if(value.match(/[^a-zA-Z0-9\ \_\.\@\-]/g))
                                {
                                    return 'No se permiten caracteres.';
                                }

                                break;

                            case 'email_user':

                                if (value.length > 130)
                                {
                                    return 'Solo se permiten 130 caracteres.';
                                }

                                if(!value.match(/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/g))
                                {
                                    return 'Ingresa una cuenta válida de correo.';
                                }

                                break;
                        }
                    },
                    success: function(response, newValue)
                    {
                        response = $.parseJSON(response);
                        modal_alert(response.data, response.message);
                    }
                });

                $('#default_table td span[data-name="id_role"]').editable({
                    validate: function(value)
                    {
                        if (value === null || value === '')
                        {
                            return 'Campo obligatorio';
                        }
                    },
                    success: function(response, newValue)
                    {
                        response = $.parseJSON(response);
                        modal_alert(response.data, response.message);
                    },
                    tpl: '<select></select>',
                    select2: {
                        theme: 'bootstrap4',
                        width: '200px',
                        language: 'es',
                        ajax: {
                            url: $path_roles,
                            dataType: 'json',
                            delay: 250,
                            data: function(params)
                            {
                                return {
                                    q: params.term,
                                    page: params.page || 1
                                };
                            },
                            processResults: function(data, params)
                            {
                                var page = params.page || 1;
                                return {
                                    results: data.items,
                                    pagination: {
                                        more: (page * 10) <= data.total_count
                                    }
                                };
                            },
                            cache: true
                        }
                    }
                });

                $('#default_table td span[data-name="id_aspirant"]').editable({
                    success: function(response, newValue)
                    {
                        response = $.parseJSON(response);
                        modal_alert(response.data, response.message);
                    },
                    tpl: '<select></select>',
                    defaultValue : 'sin aspirante',
                    select2: {
                        theme: 'bootstrap4',
                        width: '200px',
                        language: 'es',
                        ajax: {
                            url: $path_aspirants,
                            dataType: 'json',
                            delay: 250,
                            data: function(params)
                            {
                                return {
                                    q: params.term,
                                    page: params.page || 1
                                };
                            },
                            processResults: function(data, params)
                            {
                                var page = params.page || 1;
                                return {
                                    results: data.items,
                                    pagination: {
                                        more: (page * 10) <= data.total_count
                                    }
                                };
                            },
                            cache: true
                        }
                    }
                });
            }
        }
    });

    var validate = $('#form_add').validate(
    {
        rules:
        {
            name_user:
            {
                required: true,
                lettersonly_es: true,
                maxlength: 100
            },
            lastname_user:
            {
                required: true,
                lettersonly_es: true,
                maxlength: 100
            },
            id_role: 'required',
            user:
            {
                required: true,
                letters_user: true,
                maxlength: 50
            },
            email_user:
            {
                required: true,
                email: true,
                maxlength: 130
            },
            password_user:
            {
                required: true,
                minlength: 8,
                maxlength: 255
            }
        },
        messages:
        {
            name_user:
            {
                required: 'Por favor ingresa el nombre.',
                lettersonly_es: 'Solo se permiten letras.',
                maxlength: 'Solo se permiten 100 caracteres.'
            },
            lastname_user:
            {
                required: 'Por favor ingresa el apellido.',
                lettersonly_es: 'Solo se permiten letras.',
                maxlength: 'Solo se permiten 100 caracteres.'
            },
            id_role: 'Por favor selecciona un rol.',
            user:
            {
                required: 'Por favor ingresa un usuario.',
                letters_user: 'Por favor ingresa un usuario válido.',
                maxlength: 'Solo se permiten 50 caracteres.'
            },
            email_user:
            {
                required: 'Por favor ingresa el correo electrónico.',
                email: 'Por favor ingresa un correo electrónico válido.',
                maxlength: 'Solo se permiten 130 caracteres.'
            },
            password_user:
            {
                required: 'Por favor ingresa la contraseña.',
                minlength: 'La contraseña debe tener 8 caracteres como minímo.',
                maxlength: 'Solo se permiten 255 caracteres.'
            }
        },
        errorPlacement: function (error, element) 
        {
            error.addClass('invalid-feedback');

            if (element.prop('name') == 'id_role')
            {
                error.insertAfter(element.next('.select2-container'));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });

    var validate_edit = $('#form_edit').validate(
    {
        rules:
        {
            value:
            {   
                required: true,
                minlength: 8
            }
        },
        messages:
        {
            value:
            {
                required: 'Por favor ingresa la contraseña.',
                minlength: 'La contraseña debe tener 8 caracteres como minímo.'
            }
        }
    });

    $('#role').select2({
        theme: 'bootstrap4',
        width: '100%',
        language: 'es',
        placeholder: 'Selecciona rol',
        allowClear: true,
        ajax: {
            url: $path_roles,
            dataType: 'json',
            delay: 250,
            data: function(params)
            {
                var id = $('#role option:selected').val();

                return {
                    q: params.term,
                    page: params.page || 1,
                    id: id
                };
            },
            processResults: function(data, params)
            {
                var page = params.page || 1;
                return {
                    results: $.map(data.items, function (item)
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
    });

    $('#aspirant').select2({
        theme: 'bootstrap4',
        width: '100%',
        language: 'es',
        placeholder: 'Selecciona aspirante si aplica',
        allowClear: true,
        ajax: {
            url: $path_aspirants,
            dataType: 'json',
            delay: 250,
            data: function(params)
            {
                var id = $('#aspirant option:selected').val();

                return {
                    q: params.term,
                    page: params.page || 1,
                    id: id
                };
            },
            processResults: function(data, params)
            {
                var page = params.page || 1;
                return {
                    results: $.map(data.items, function (item)
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
    });

    $('#btn_add').on('click', function ()
    {
        $('#view_table').addClass('d-none');
        $('#view_form_add').removeClass('d-none');
        $('#role').empty().trigger('change');
        validate.resetForm();
        $('#form_add')[0].reset();
        $('.flags').prop("checked", false);
    });

    $('#btn_confirm_add').on('click', function ()
    {
        $('#form_add').submit();
    });

    $('#form_add').ajaxForm({
        dataType:  'json',
        success:   add,
        beforeSubmit: function()
        {
            $('#loading').removeClass('d-none');
        }
    });

    $('#btn_cancel_add').on('click', function ()
    {
        var defaultTable = $('#default_table').DataTable();
        defaultTable.ajax.reload();
        validate.resetForm();
        $('#form_add')[0].reset();
        $('#role').empty().trigger("change");
        $('.flags').prop("checked", false);
        $('#view_form_add').addClass('d-none');
        $('#view_table').removeClass('d-none');
    });

    $('#default_table').on('click', 'a.edit-row', function()
    {
        $('#view_table').addClass('d-none');
        $('#view_form_edit').removeClass('d-none');
        validate_edit.resetForm();
        $('#form_edit')[0].reset();
        $('#id_user').val($(this).attr('data-id'));
    });

    $('#btn_confirm_edit').on('click', function ()
    {
        $('#form_edit').submit();
    });

    $('#form_edit').ajaxForm({
        dataType:  'json',
        success:   edit,
        beforeSubmit: function()
        {
            $('#loading').removeClass('d-none');
        }
    });

    $('#btn_cancel_edit').on('click', function()
    {
        var defaultTable = $('#default_table').DataTable();
        defaultTable.ajax.reload();
        validate_edit.resetForm();
        $('#form_edit')[0].reset();
        $('#view_form_edit').addClass('d-none');
        $('#view_table').removeClass('d-none');
    });

    $('#default_table').on('click', 'a.flags-row', function()
    {
        $('#loading').removeClass('d-none');
        var user = $(this).attr('data-id');
        $('#id_user_flags').val(user);

        $.ajax({
            type: 'POST',
            url: $path_userflags,
            data:
            {
                id_user: user
            },
            dataType: 'json',
            success: function (response)
            {
                $('.flags_edit').prop("checked", false);

                if (response.data)
                {
                    for (var i = 0; i < response.data.length; i++)
                    {
                        $('#flag' + response.data[i].id_flag).prop("checked", true);
                    }
                }

                $('#loading').addClass('d-none');
                $('#view_table').addClass('d-none');
                $('#view_edit_flags').removeClass('d-none');
            },
            error: function ()
            {
                $('#loading').addClass('d-none');
                modal_alert(false, 'Error de conexión.');
            }
        });
    });

    $('.flags_edit').on('change', function ()
    {
        $('#loading').removeClass('d-none');

        if ($(this).prop("checked"))
        {
            var state = 1;
        }
        else
        {
            var state = 0;
        }

        $.ajax({
            type: 'POST',
            url: $path_editflags,
            data:
            {
                id_user: $('#id_user_flags').val(),
                id_flag: $(this).val(),
                state: state
            },
            dataType: 'json',
            success: function (response)
            {
                iziToast.show(
                {
                    timeout: 5000,
                    backgroundColor: (response.data ? '#23BF08' : '#DC3545'),
                    titleColor: '#FFF',
                    messageColor: '#FFF',
                    iconColor: '#FFF',
                    position: 'topRight',
                    icon: (response.data ? 'far fa-check-circle' : 'far fa-times-circle'),
                    title: (response.data ? 'Información' : 'Alerta'),
                    message: '<br/><br/>' + response.message
                });

                $('#loading').addClass('d-none');
            },
            error: function ()
            {
                $('#loading').addClass('d-none');
                modal_alert(false, 'Error de conexión.');
            }
        });
    });

    $('#btn_cancel_edit_flags').on('click', function ()
    {
        var defaultTable = $('#default_table').DataTable();
        defaultTable.ajax.reload();
        $('.flags_edit').prop("checked", false);
        $('#view_edit_flags').addClass('d-none');
        $('#view_table').removeClass('d-none');
    });

    $('#default_table').on('click', 'a.display-row', function()
    {
        var user    = $(this).attr('data-id');
        var display = $(this).attr('data-display');

        if(display == '0')
        {
            $('#txt_display').html('inhabilitar');
        }
        else
        {
            $('#txt_display').html('habilitar');
        }

        $('#modal_display').iziModal({
            title: (display == '0' ? 'Inhabilitar' : 'Habilitar') + ' Usuario',
            icon: (display == '0' ? 'fas fa-eye-slash' : 'fas fa-eye'),
            headerColor: '#224978',
            zindex: 9999,
            onClosed: function()
            {
                $('#btn_confirm_display').off('click');
                $('#modal_display').iziModal('destroy');
            },
            onOpening: function()
            {
                $('#btn_confirm_display').on('click', function()
                {
                    $('#loading').removeClass('d-none');

                    $.ajax({
                        type: 'POST',
                        url: $path_display,
                        data:
                        {
                            id_user: user,
                            display: display
                        },
                        dataType: 'json',
                        success: function (response)
                        {
                            $('#loading').addClass('d-none');

                            if (response.data)
                            {
                                var defaultTable = $('#default_table').DataTable();
                                defaultTable.ajax.reload();
                            }

                            modal_alert(response.data, response.message);
                        },
                        error: function ()
                        {
                            $('#loading').addClass('d-none');
                            modal_alert(false, 'Error de conexión.');
                        }
                    });

                    $('#modal_display').iziModal('close');
                });

                $('#btn_cancel_display').on('click', function()
                {
                    $('#modal_display').iziModal('close');
                });
            }
        });   

        $('#modal_display').iziModal('open');
    });

    $('#default_table').on('click', 'a.remove-row', function()
    {
        var user = $(this).attr('data-id');

        $('#modal_delete').iziModal({
            title: 'Eliminar usuario',
            icon: 'fas fa-trash-alt',
            headerColor: '#DC3545',
            zindex: 9999,
            onClosed: function()
            {
                $('#btn_confirm_delete').off('click');
                $('#modal_delete').iziModal('destroy');
            },
            onOpening: function()
            {
                $('#btn_confirm_delete').on('click', function()
                {
                    $('#loading').removeClass('d-none');

                    $.ajax({
                        type: 'POST',
                        url: $path_drop,
                        data: {
                            id_user: user,
                        },
                        dataType: 'json',
                        success: function (response)
                        {
                            var defaultTable = $('#default_table').DataTable();
                            defaultTable.ajax.reload();
                            modal_alert(response.data, response.message);
                            $('#loading').addClass('d-none');
                        },
                        error: function () {
                            modal_alert(false, 'Error de conexión.');
                        }
                    });

                    $('#modal_delete').iziModal('close');
                });

                $('#btn_cancel_delete').on('click', function()
                {
                    $('#modal_delete').iziModal('close');
                });
            }
        });

        $('#modal_delete').iziModal('open');
    });

});

function add(response)
{
    modal_alert(response.data, response.message);

    if (response.data)
    {
        $('#view_form_add').addClass('d-none');
    }
}

function edit(response)
{
    modal_alert(response.data, response.message);

    if (response.data)
    {
        $('#view_form_edit').addClass('d-none');
    }
}