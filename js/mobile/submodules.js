$(function($)
{
    //BUILD - MOBILE
    $.fn.editable.defaults.mode = 'inline';

    var height = ($(window).height() - 380);

    $('#default_table').DataTable(
    {
        language:
        {
            sUrl: 'resources/lib/datatables/Spanish.json',
        },
        destroy: true,
        info: false,
        serverSide: true,
        lengthChange: true,
        scrollY: height,
        scroller:
        {
            loadingIndicator: true
        },
        ajax: function(data, callback, settings)
        {
            data.length = 50;

            $.ajax(
            {
                url: $path_view,
                type: 'POST',
                data: data,
                dataType: 'json',
                success:function(data)
                {
                    callback(
                    {
                        draw: data.draw,
                        data: data.data,
                        recordsTotal: data.recordsTotal,
                        recordsFiltered: data.recordsFiltered
                    });
                }
            })
        },
        columnDefs:
        [
            {
                targets: [0],
                data: 'name_submodule',
                createdCell: function(td, cellData, rowData, row, col)
                {
                    $(td).attr('data-label', 'Submodulo');
                },
                render: function(data, type, row)
                {
                    return '<br><br><div class="span-left"><span id="name_submodule" data-type="text" data-pk="' + row.id_submodule + '" data-url="' + $path_edit + '">' + data + '</span></div>';
                }
            },
            {
                targets: [1],
                data: 'name_es_submodule',
                createdCell: function(td, cellData, rowData, row, col)
                {
                    $(td).attr('data-label', 'Significado');
                },
                render: function(data, type, row)
                {
                    return '<br><br><div class="span-left"><span id="name_es_submodule" data-type="text" data-pk="' + row.id_submodule + '" data-url="' + $path_edit + '">' + data + '</span></div>';
                }
            },
            {
                targets: [2],
                data: 'url_submodule',
                createdCell: function(td, cellData, rowData, row, col)
                {
                    $(td).attr('data-label', 'URL');
                },
                render: function(data, type, row)
                {
                    return '<br><br><div class="span-left"><span id="url_submodule" data-type="text" data-pk="' + row.id_submodule + '" data-url="' + $path_edit + '">' + data + '</span></div>';
                }
            },
            {
                targets: [3],
                data: 'name_es_module',
                createdCell: function(td, cellData, rowData, row, col)
                {
                    $(td).attr('data-label', 'Módulo');
                }, 
                render: function(data, type, row)
                {
                    return '<br><br><div class="span-left"><span id="id_module" data-type="select2" data-pk="' + row.id_submodule + '" data-url="' + $path_edit + '" data-value="' + row.id_module + '">' + data + '</span></div>';
                }
            },
            {
                targets: [4],
                data: 'id_submodule',
                orderable: false,
                render: function(data, type, row)
                {
                    var content = '<div class="span-center">';

                    if (act_change)
                    {
                        content += '<a title="Cambiar acciones" href="javascript:void(0)" class="change-row pd-x-5-force" data-id="' + data + '" data-sub="' + row.name_submodule + '"><i class="fas fa-exchange-alt"></i></a>';
                    }

                    if (act_trace)
                    {
                        content += '<a title="Trazabilidad" href="javascript:void(0)" class="pd-x-5-force" onclick="trace(\'' + $path_trace + '\', \'id_submodule\',' + data + ')"><i class="fas fa-history"></i></a>';
                    }
 
                    return content + '</div>';
                },
                visible: (act_change || act_trace ? true : false)
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
                $('#default_table td span[data-type="text"]').editable({
                    validate: function(value)
                    {
                        if (value === null || value === '')
                        {
                            return 'Campo obligatorio.';
                        }

                        switch($(this).attr('id'))
                        {
                            case 'name_submodule':
                                if(value.match(/[^a-zA-Z0-9]/g))
                                {
                                    return 'Solo se permiten letras y números.';
                                }

                                if (value.length > 20)
                                {
                                    return 'Solo se permiten 20 caracteres.';
                                }

                                break;

                            case 'name_es_submodule':
                                if(value.match(/[^a-zA-ZáéíóúñÑÁÉÍÓÚ ]/g))
                                {
                                    return 'Solo se permiten letras.';
                                }

                                if (value.length > 50)
                                {
                                    return 'Solo se permiten 50 caracteres.';
                                }

                                break;

                            case 'url_submodule':
                                if(value.match(/[^a-zA-Z\/]/g))
                                {
                                    return 'Por favor ingresa una URL valida.';
                                }

                                break;
                        }
                    },
                    success: function(response, newValue)
                    {
                        response = $.parseJSON(response);
                        modal_alert(response.data, response.message);
                    },
                });

                $('#default_table td span[data-type="select2"]').editable({
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
                            url: $path_modules,
                            dataType: 'json',
                            delay: 250,
                            data: function(params)
                            {
                                var id = $('#id_module').val();

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
                            },
                            cache: true
                        },
                    }
                });
            }
        }
    });

    var validate = $('#form_add').validate(
    {
        rules:
        {
            name_submodule:
            {
                required: true,
                maxlength: 20
            },
            name_es_submodule:
            {
                required: true,
                lettersonly_es: true,
                maxlength: 50
            },
            id_module: 'required',
            url_submodule:
            {
                required: true,
                letters_slash: true
            }
        },
        messages:
        {
            name_submodule:
            {
                required: 'Por favor ingresa el submodulo.',
                maxlength: 'Solo se permiten 20 caracteres.'
            },
            name_es_submodule:
            {
                required: 'Por favor ingresa su significado.',
                lettersonly_es: 'Solo se permiten letras.',
                maxlength: 'Solo se permiten 50 caracteres.'
            },
            id_module: 'Por favor selecciona un módulo.',
            url_submodule:
            {
                required: 'Por favor ingresa la URL.',
                letters_slash: 'Por favor ingresa una URL valida.',
            }
        },
        errorPlacement: function(error, element)
        {
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

    $('#module').select2({
        theme: 'bootstrap4',
        width: '100%',
        language: 'es',
        placeholder: 'Selecciona módulo',
        ajax: {
            url: $path_modules,
            dataType: 'json',
            delay: 250,
            data: function(params)
            {
                var id = $('#module option:selected').val();

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
            },
        },
        escapeMarkup: function (markup) { return markup; },
    }).on('change', function (e)
    {
        $(this).valid();
    });

    //ADD
    $('#btn_add').on('click', function ()
    {
        $('#module').empty().trigger('change');
        $('#form_add')[0].reset();

        $('#loading').removeClass('d-none');

        $('#view_table').addClass('d-none');
        $('#view_form_add').removeClass('d-none');

        $('#btn_select_actions_table').attr('data-flag', '0');
        $('#btn_select_actions_table').attr('data-original-title', 'Marcar todos');

        $('#loading').addClass('d-none');
    });

    $('#btn_confirm_add').on('click', function ()
    {
        $('#form_add').submit();
    });

    $('#form_add').ajaxForm({
        dataType:  'json',
        success:   add_submodule,
        beforeSubmit: function()
        {
            $('#loading').removeClass('d-none');
        }
    });

    $('#btn_select_actions_table').on('click', function ()
    {
        var submodule_add = $('#submodule_add').val();
        var flag_add = $('#btn_select_actions_table').attr('data-flag');

        if (flag_add == "0")
        {
            flag_add = '1';
        }
        else
        {
            flag_add = '0';
        }

        actions_all(submodule_add, 'add', flag_add);
    });

    $('#btn_cancel_add').on('click', function ()
    {
        var defaultTable = $('#default_table').DataTable();
        defaultTable.ajax.reload();
        validate.resetForm();
        $('#form_add')[0].reset();
        $('#view_form_add').addClass('d-none');
        $('#view_table').removeClass('d-none');
    });

    $('#btn_cancel_add_action').on('click', function ()
    {
        var defaultTable = $('#default_table').DataTable();
        defaultTable.ajax.reload();
        validate.resetForm();
        $('#btn_select_actions_table').addClass('d-none');
        $('#view_form_add_action').addClass('d-none');
        $('#form_add')[0].reset();
        $('#view_form_add').addClass('d-none');
        $('#view_table').removeClass('d-none');
    });

    //CHANGE
    $('#default_table').on('click', 'a.change-row', function()
    {
        $('#loading').removeClass('d-none');

        $('#submodule_change').val($(this).attr('data-id') + ',' + $(this).attr('data-sub'));
        var submodule = $(this).attr('data-id');

        var btn_change = 0;

        var height_actions_table_change = ($(window).height() - 445);

        $('#actions_table_change').DataTable(
        {
            language:
            {
                sUrl: 'resources/lib/datatables/Spanish.json',
            },
            destroy: true,
            info: false,
            serverSide: true,
            lengthChange: true,
            scrollY: height_actions_table_change,
            scroller:
            {
                loadingIndicator: true
            },
            ajax:
            {
                url: $path_actions_submodule,
                data: {
                    id_submodule: submodule
                },
                dataType: 'json',
                type: 'POST'
            },
            columnDefs:
            [
                {
                    targets: [0],
                    data: 'name',
                    render: function(data, type, row)
                    {
                        return '<span>' + data + '</span>';
                    }
                },
                {
                    targets: [1],
                    data: 'id',
                    render: function(data, type, row, meta)
                    {
                        var state_action = '';
                        if (row.state_action == "1")
                        {
                            state_action = 'checked';
                            btn_change++;
                        }
                        else
                        {
                            state_action = '';
                        }

                        var data_count = (meta.settings.json.data_count) * 2;

                        if (parseInt(data_count) == btn_change)
                        {
                            $('#btn_select_actions_table_change').attr('data-flag', '1');
                            $('#btn_select_actions_table_change').attr('data-original-title', 'Desmarcar todos');
                        }
                        else
                        {
                            $('#btn_select_actions_table_change').attr('data-flag', '0');
                            $('#btn_select_actions_table_change').attr('data-original-title', 'Marcar todos');
                        }

                        return '<label class="ckbox ckbox-change">' + '<input class="check-change" type="checkbox" ' + state_action + ' value="' + data + ',' + row.action + '">' + '<span></span></label>';
                    }
                }
            ],
            drawCallback: function(settings)
            {
                $('#view_table').addClass('d-none');
                $('#btn_select_actions_table_change').removeClass('d-none');
                $('#view_form_change').removeClass('d-none');
                $('#loading').addClass('d-none');

                $('.check-change').on('click', function ()
                {
                    $('#loading').removeClass('d-none');

                    var submodule_change = $('#submodule_change').val();
                    var action = $(this).val();

                    $.ajax({
                        type: 'POST',
                        url: $path_update_state_action,
                        data:
                        {
                            submodule: submodule_change,
                            action: action
                        },
                        dataType: 'json',
                        success: function (response)
                        {
                            $('#loading').addClass('d-none');

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
                        },
                        error: function ()
                        {
                            $('#loading').addClass('d-none');
                            modal_alert(false, 'Error de conexión.');
                        }
                    });
                });
            }
        });
    });

    $('#btn_cancel_change').on('click', function ()
    {
        var defaultTable = $('#default_table').DataTable();
        defaultTable.ajax.reload();

        $('#btn_select_actions_table_change').addClass('d-none');
        $('#view_form_change').addClass('d-none');
        $('#view_table').removeClass('d-none');
    });

    $('#btn_select_actions_table_change').on('click', function ()
    {
        var submodule_change = $('#submodule_change').val();
        var flag_change = $('#btn_select_actions_table_change').attr('data-flag');

        if (flag_change == "0")
        {
            flag_change = '1';
        }
        else
        {
            flag_change = '0';
        }

        actions_all(submodule_change, 'change', flag_change);
    });
});

function add_submodule(response)
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

    if (response.data)
    {
        $('#view_form_add').addClass('d-none');
        $('#view_table').addClass('d-none');
        $('#btn_select_actions_table_change').addClass('d-none');
        $('#view_form_change').addClass('d-none');
        $('#submodule_add').val(response.id_submodule + ',' + response.name_submodule);
        var submodule = response.id_submodule;

        var height_actions_table = ($(window).height() - 445);

        $('#actions_table').DataTable(
        {
            language:
            {
                sUrl: 'resources/lib/datatables/Spanish.json',
            },
            destroy: true,
            info: false,
            serverSide: true,
            lengthChange: true,
            scrollY: height_actions_table,
            scroller:
            {
                loadingIndicator: true
            },
            ajax:
            {
                url: $path_actions_submodule,
                data: {
                    id_submodule: submodule
                },
                dataType: 'json',
                type: 'POST'
            },
            columnDefs:
            [
                {
                    targets: [0],
                    data: 'name',
                    render: function(data, type, row)
                    {
                        return '<span>' + data + '</span>';
                    }
                },
                {
                    targets: [1],
                    data: 'id',
                    render: function(data, type, row, meta)
                    {
                        var state_action = '';
                        if (row.state_action == "1")
                        {
                            state_action = 'checked';
                        }
                        else
                        {
                            state_action = '';
                        }

                        return '<label class="ckbox">' + '<input class="check-add" type="checkbox" ' + state_action + ' value="' + data + ',' + row.action + '">' + '<span></span></label>';
                    }
                }
            ],
            drawCallback: function(settings)
            {
                $('.check-add').on('click', function ()
                {
                    var submodule_add = $('#submodule_add').val();
                    var action = $(this).val();

                    $.ajax({
                        type: 'POST',
                        url: $path_update_state_action,
                        data:
                        {
                            submodule: submodule_add,
                            action: action
                        },
                        dataType: 'json',
                        success: function (response)
                        {
                            $('#loading').addClass('d-none');

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
                        },
                        error: function ()
                        {
                            $('#loading').addClass('d-none');
                            modal_alert(false, 'Error de conexión.');
                        }
                    });
                });

                $('#loading').addClass('d-none');
            }
        });

        $('#btn_select_actions_table').removeClass('d-none');
        $('#view_form_add_action').removeClass('d-none');
    }

    $('#loading').addClass('d-none');
}

function actions_all(submodule, action, flag)
{
    $('#loading').removeClass('d-none');

    $.ajax({
        type: 'POST',
        url: $path_update_actions_all,
        data:
        {
            submodule: submodule,
            flag: flag
        },
        dataType: 'json',
        success: function (response)
        {
            $('#loading').addClass('d-none');

            if (action == 'add')
            {
                if (flag == '0')
                {
                    $('.check-add').prop('checked', false);
                    $('#btn_select_actions_table').attr('data-flag', '0');
                    $('#btn_select_actions_table').attr('data-original-title', 'Marcar todos');
                }
                else if (flag == '1')
                {
                    $('.check-add').prop('checked', true);
                    $('#btn_select_actions_table').attr('data-flag', '1');
                    $('#btn_select_actions_table').attr('data-original-title', 'Desmarcar todos');
                }
            }
            else if (action == 'change')
            {
                if (flag == '0')
                {
                    $('.check-change').prop('checked', false);
                    $('#btn_select_actions_table_change').attr('data-flag', '0');
                    $('#btn_select_actions_table_change').attr('data-original-title', 'Marcar todos');
                }
                else if (flag == '1')
                {
                    $('.check-change').prop('checked', true);
                    $('#btn_select_actions_table_change').attr('data-flag', '1');
                    $('#btn_select_actions_table_change').attr('data-original-title', 'Desmarcar todos');
                }
            }

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
        },
        error: function ()
        {
            $('#loading').addClass('d-none');
            modal_alert(false, 'Error de conexión.');
        }
    });
}