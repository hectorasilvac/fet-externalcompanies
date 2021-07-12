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
                    return '<div class="span-center">' + data + '</div>';
                }
            },
            {
                targets: [1],
                data: 'name_module',
                render: function(data, type, row)
                {
                    return '<span id="name_module" data-type="text" data-pk="' + row.id_module + '" data-url="' + $path_edit + '" data-value="' + data + '">' + data + '</span>';
                }
            },
            {
                targets: [2], 
                data: 'name_es_module',
                render: function(data, type, row)
                {
                    return '<span id="name_es_module" data-type="text" data-pk="' + row.id_module + '" data-url="' + $path_edit + '" data-value="' + data + '">' + data + '</span>';
                }
            },
            {
                targets: [3], 
                data: 'url_module',
                render: function(data, type, row)
                {
                    return '<span id="url_module" data-type="text" data-pk="' + row.id_module + '" data-url="' + $path_edit + '" data-value="' + data + '">' + data + '</span>';
                }
            },
            {
                targets: [4], 
                data: 'icon_module',
                render: function(data, type, row)
                {
                    return '<span id="icon_module" data-type="text" data-pk="' + row.id_module + '" data-url="' + $path_edit + '" data-value="' + data + '">' + data + '</span> <i class="' + data + '"></i>';
                }
            },
            {
                targets: [5], 
                data: 'id_module',
                orderable: false,
                render: function(data, type, row)
                {
                    var content = '<div class="span-center">';

                    if(act_trace)
                    {
                        content += '<a data-toggle="tooltip" data-placement="top" title="Trazabilidad" href="javascript:void(0)" class="pd-xs-x-5-force" onclick="trace(\'' + $path_trace + '\', \'id_module\',' + data + ')"><i class="fas fa-history"></i></a>';
                    }

                    return content + '</div>';
                },
                visible: (act_trace ? true : false)
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
                if (inputSearch != "")
                {
                    $('#btn_export_xlsx').attr('href', $path_export_xlsx + '/?search=' + inputSearch);
                }
                else
                {
                    $('#btn_export_xlsx').attr('href', $path_export_xlsx);
                }
            }

            if(act_edit)
            {
                $('#default_table td span').editable(
                {
                    validate: function(value)
                    {
                        var item = $(this);
                        var old_value = $(this).attr('data-value');
                        var pk = $(this).attr('data-pk');

                        if (value === null || value === '')
                        {
                            return 'Campo obligatorio';
                        }

                        switch($(this).attr('id')) 
                        {
                            case 'name_module':
                                if(value.match(/[^a-zA-Z]/g) )
                                {
                                    return 'Solo se permiten letras.';
                                }

                                if (value.length > 20) 
                                {
                                    return 'Solo se permiten 20 caracteres.';
                                }

                                break;

                            case 'name_es_module':
                                if(value.match(/[^a-zA-ZáéíóúñÑÁÉÍÓÚ ]/g))
                                {
                                    return 'Solo se permiten letras.';
                                }

                                if (value.length > 25)
                                {
                                    return 'Solo se permiten 25 caracteres.';
                                }

                                break;

                            case 'url_module':
                                if(value.match(/[^a-zA-Z\/\#]/g) )
                                {
                                    return 'Por favor ingresa una URL valida.';
                                }
                                
                                var flag =  0;
                                var message =  '';

                                if ((old_value == '#' && value != null && value != '' && value != '#') || (old_value != '#' && old_value != '' && old_value != null && value == '#'))
                                {
                                    $('#loading').removeClass('d-none');

                                    $.ajax({
                                        type: 'POST',
                                        url: $path_users_permission,
                                        data:
                                        {
                                            module: pk
                                        },
                                        dataType: 'json',
                                        success: function (response)
                                        {
                                            $('#loading').addClass('d-none');

                                            switch(response.data)
                                            {
                                                case 0:
                                                    users = '';
                                                break;

                                                case 1:
                                                    users = ' (' + response.data + ' usuario tiene asignado este módulo)';
                                                break;

                                                default:
                                                    users = ' (' + response.data + ' usuarios tienen asignado este módulo)';
                                                break;
                                            }

                                            if (old_value == '#' && value != null && value != '' && value != '#')
                                            {
                                                message =  'Recuerde que si se cambia a una URL diferente de \'#\' los submodulos asociados serán eliminados y los permisos serán modificados, siendo una opción de menú directa' + users + '.<br/><br/>Seguro deseas ejecutar esta acción?';
                                                flag =  1;
                                            }
                                            else
                                            {
                                                message =  'Recuerde que si se cambia la URL a \'#\' se tendrán que agregar nuevos submodulos y permisos posteriormente, dejando de ser una opción directa del menú' + users + '.<br/><br/>Seguro deseas ejecutar esta acción?';
                                                flag =  2;
                                            }

                                            iziToast.question({
                                                rtl: false,
                                                layout: 1,
                                                drag: false,
                                                timeout: false,
                                                close: false,
                                                overlay: true,
                                                displayMode: 1,
                                                maxWidth: 500,
                                                id: 'question',
                                                progressBar: true,
                                                icon :'fas fa-exclamation-triangle',
                                                iconColor: '#fff',
                                                titleColor : '#fff',
                                                title: 'Confirmación',
                                                messageColor: '#fff',
                                                message: message,
                                                position: 'center',
                                                backgroundColor :'#dc3545',
                                                color: '#fff',
                                                buttons: [
                                                    ['<button class="btn btn-izi-confirm">SI</button>', function (instance, toast)
                                                    {
                                                        $(item).editable('submit',{
                                                            data:
                                                            {
                                                                value: value,
                                                                flag: flag
                                                            }
                                                        });

                                                        iziToast.hide({}, toast);

                                                        $('#loading').removeClass('d-none');
                                                    }],
                                                    ['<button class="btn btn-izi-confirm">NO</button>', function (instance, toast)
                                                    {
                                                        iziToast.hide({}, toast);
                                                    }, true],
                                                ]
                                            });

                                        },
                                        error: function ()
                                        {
                                            $('#loading').addClass('d-none');
                                            modal_alert(false, 'Error de conexión.');
                                        }
                                    });
                                    
                                    return 'Por favor confirme antes de enviar.';
                                }

                                break;

                            case 'icon_module':
                                if (value.length > 50)
                                {
                                    return 'Solo se permiten 50 caracteres.';
                                }

                                break;
                        }
                    },
                    success: function(response, newValue) 
                    {
                        response = $.parseJSON(response);
                        $('#loading').addClass('d-none');
                        modal_alert(response.data, response.message);
                    }
                });
            }
        }
    });

    var validate = $('#form_add').validate(
    {
        rules:
        {
            name_module:
            {
                required: true,
                lettersonly: true,
                maxlength: 20
            },
            name_es_module:
            {
                required: true,
                lettersonly_es: true,
                maxlength: 25
            },
            url_module:
            {
                required: true,
                letters_slash: true,
                maxlength: 200
            },
            icon_module:
            {
                required: true,
                maxlength: 50
            }
        },
        messages:
        {
            name_module:
            {
                required: 'Por favor ingresa el módulo.',
                lettersonly: 'Solo se permiten letras.',
                maxlength: 'Solo se permiten 20 caracteres.'
            },
            name_es_module:
            {
                required: 'Por favor ingresa el significado.',
                lettersonly_es: 'Solo se permiten letras.',
                maxlength: 'Solo se permiten 25 caracteres.'
            },
            url_module:
            {
                required: 'Por favor ingresa la URL.',
                letters_slash: 'Por favor ingresa una URL valida.',
                maxlength: 'Solo se permiten 200 caracteres.'
            },
            icon_module:
            {
                required: 'Por favor ingresa el icono',
                maxlength: 'Solo se permiten 50 caracteres.'
            }
        },
        errorPlacement: function(error, element)
        {
            error.addClass('invalid-feedback');

            switch (element.prop('type'))
            {
                case 'select-one': 
                    error.insertAfter($('#form_add .select2-container'));
                    break;
                default:
                    error.insertAfter(element);
            }
        }
    });

    $('#type_m').select2(
    {
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'Selecciona tipo de módulo',
    }).on('change', function(e) 
    {
        $(this).valid();
    });

    //ADD
    $('#btn_add').on('click', function()
    {
        $('#view_table').addClass('d-none');
        $('#view_form_add').removeClass('d-none');
        $('#type_m').val(null).trigger('change');
        validate.resetForm();
        $('#form_add')[0].reset();
    });

    $('#btn_confirm_add').on('click', function() 
    {
        $('#form_add').submit();
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

    $('#btn_cancel_add').on('click', function()
    {
        var defaultTable = $('#default_table').DataTable();
        defaultTable.ajax.reload();
        validate.resetForm();
        $('#form_add')[0].reset();
        $('#view_form_add').addClass('d-none');
        $('#view_table').removeClass('d-none');
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