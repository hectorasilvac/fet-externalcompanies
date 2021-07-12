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

            $.ajax({
                url: $path_view,
                type: 'POST',
                data: data,
                dataType: 'json',
                success:function(data)
                {
                    callback({
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
                data: 'name_role',
                createdCell: function(td, cellData, rowData, row, col)
                {
                   $(td).attr('data-label', 'Rol');
                },
                render: function(data, type, row)
                {
                    return '<br/><br/><div class="span-left"><span>' + data + '</span></div>';
                }
            },
            {
                targets: [1],
                data: 'name_submodule',
                createdCell: function(td, cellData, rowData, row, col)
                {
                   $(td).attr('data-label', 'Submodulo');
                },
                render: function(data, type, row)
                {
                    return '<br/><br/><div class="span-left"><span>' + data + '</span></div>';
                }
            },
            {
                targets: [2],
                data: 'name_action',
                createdCell: function(td, cellData, rowData, row, col)
                {
                   $(td).attr('data-label', 'Acción');
                },
                render: function(data, type, row)
                {
                    return '<br/><br/><div class="span-left"><span>' + data + '</span></div>';
                }
            },
            {
                targets: [3],
                data: 'id_role',
                orderable: false,
                render: function(data, type, row)
                {
                    var content = '<div class="span-center">';

                    if (act_drop)
                    {
                        content += '<a title="Eliminar" href="javascript:void(0)" class="remove-row pd-x-5-force" data-role="' + row.id_role + '" data-submodule="' + row.id_submodule + '" data-action="' + row.id_action + '"><i class="fas fa-trash-alt"></i></a>';
                    }

                    if (act_trace)
                    {
                        content += '<a title="Trazabilidad" href="javascript:void(0)" class="pd-x-5-force" onclick="trace(\'' + $path_trace + '\', \'ids\',\'' + row.id_role + '-' + row.id_submodule + '-' + row.id_action + '\')"><i class="fas fa-history"></i></a>';
                    }

                    return content + '</div>';
                },
                visible: (act_drop || act_trace ? true : false)
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
        }
    });

    var validate = $('#form_add').validate(
    {
        rules:
        {
            id_role: 'required',
            id_submodule: 'required'
        },
        messages:
        {
            id_role: 'Por favor ingresa el rol',
            id_submodule: 'Por favor ingresa el submodulo'
        },
        errorPlacement: function(error, element)
        {
            switch (element.prop('type'))
            {
                case 'select-one':
                    error.insertAfter(element.next('.select2-container'));
                    break;
                default:
                    error.insertAfter(element);
            }
        }
    });

    var validate_continue = $('#form_add_continue').validate(
    {
        rules:
        {
            id_role: 'required',
            id_submodule: 'required'
        },
        messages:
        {
            id_role: 'Por favor ingresa el rol',
            id_submodule: 'Por favor ingresa el submodulo'
        },
        errorPlacement: function(error, element)
        {
            switch (element.prop('type'))
            {
                case 'select-one':
                    error.insertAfter(element.next('.select2-container'));
                    break;
                default:
                    error.insertAfter(element);
            }
        }
    });

    $('#id_role').select2({
        theme: 'bootstrap4',
        width: '100%',
        language: 'es',
        placeholder: 'Selecciona roles',
        ajax: {
            url: roles,
            dataType: 'json',
            delay: 250,
            data: function(params)
            {
                var id = $('#id_role').val();

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
        escapeMarkup: function (markup) { return markup; },
    }).on('change', function (e)
    {
        $(this).valid();
    });

    $('#id_role_continue').select2({
        theme: 'bootstrap4',
        width: '100%',
        language: 'es',
        placeholder: 'Selecciona roles',
        ajax: {
            url: roles,
            dataType: 'json',
            delay: 250,
            data: function(params)
            {
                var id = $('#id_role_continue').val();

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
        escapeMarkup: function (markup) { return markup; },
    }).on('change', function (e)
    { 
        $(this).valid();
    });

    $('#id_submodule').select2({
        theme: 'bootstrap4',
        width: '100%',
        language: 'es',
        placeholder: 'Selecciona submodulo',
        ajax: {
            url: submodules,
            dataType: 'json',
            delay: 250,
            data: function(params)
            {
                var id = $('#id_submodule').val();

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
        escapeMarkup: function (markup) { return markup; },
    }).on('change', function (e)
    {
        $(this).valid();
    });

    $('#id_submodule_continue').select2({
        theme: 'bootstrap4',
        width: '100%',
        language: 'es',
        placeholder: 'Selecciona submodulo',
        ajax: {
            url: submodules,
            dataType: 'json',
            delay: 250,
            data: function(params)
            {
                var id = $('#id_submodule_continue').val();

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
        escapeMarkup: function (markup) { return markup; },
    }).on('change', function (e)
    {
        $(this).valid();
    });

    $('#id_role, #id_submodule').change(function()
    {
        var id_role = $('#id_role').val();
        var id_submodule = $('#id_submodule').val();

        if (id_role != null && id_submodule != null)
        {
            $('#loading').removeClass('d-none');

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
                scrollY: height,
                scroller:
                {
                    loadingIndicator: true
                },
                ajax: function(data, callback, settings)
                {
                    data.length = 50;
                    data.id_submodule = id_submodule;

                    $.ajax(
                    {
                        url: $path_actions_submodule,
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
                            if (data == "1-VIEW-Ver")
                            {
                                state_action = 'checked disabled';
                            }
                            else
                            {
                                state_action = '';
                            }

                            return '<div class="mg-l-47p"><label class="ckbox">' + '<input class="check-add" name="checkbox' + meta.row + '" type="checkbox" ' + state_action + ' value="' + data + '">' + '<span></span></label></div>';
                        }
                    }
                ],
                drawCallback: function(settings)
                {
                    $('.content-actions-table').removeClass('d-none');
                    $('#btn_confirm_add').removeClass('d-none');
                    $('#btn_select_actions_table').attr('data-flag', '0');
                    $('#loading').addClass('d-none');
                }
            });
        }
    });

    $('#id_role_continue, #id_submodule_continue').change(function()
    {
        var id_role_continue = $('#id_role_continue').val();
        var id_submodule_continue = $('#id_submodule_continue').val();

        if (id_role_continue != null && id_submodule_continue != null)
        {
            $('#loading').removeClass('d-none');

            $('#actions_table_continue').DataTable(
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
                    data.id_submodule = id_submodule_continue;

                    $.ajax(
                    {
                        url: $path_actions_submodule,
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
                            if (data == "1-VIEW-Ver")
                            {
                                state_action = 'checked disabled';
                            }
                            else
                            {
                                state_action = '';
                            }

                            return '<div class="mg-l-47p"><label class="ckbox">' + '<input class="check-add-continue" name="checkbox' + meta.row + '" type="checkbox" ' + state_action + ' value="' + data + '">' + '<span></span></label></div>';
                        }
                    }
                ],
                drawCallback: function(settings)
                {
                    $('.content-actions-table-continue').removeClass('d-none');
                    $('#btn_confirm_add_continue').removeClass('d-none');
                    $('#btn_select_actions_table_continue').attr('data-flag', '0');
                    $('#loading').addClass('d-none');
                }
            });
        }
    });

    $('#btn_select_actions_table').on('click', function ()
    {
        var flag = $('#btn_select_actions_table').attr('data-flag');

        if (flag == "0")
        {
            $('.check-add').prop('checked', true);
            $('#btn_select_actions_table').attr('data-flag', '1');
            $('#btn_select_actions_table').attr('data-original-title', 'Desmarcar Todos');
            $('#select_actions_all').val('1');
        }
        else
        {
            $('.check-add').prop('checked', false);
            $('#btn_select_actions_table').attr('data-flag', '0');
            $('#btn_select_actions_table').attr('data-original-title', 'Marcar Todos');
            $('#select_actions_all').val('0');
        }
    });

    $('#btn_select_actions_table_continue').on('click', function ()
    {
        var flag = $('#btn_select_actions_table_continue').attr('data-flag');

        if (flag == "0")
        {
            $('.check-add-continue').prop('checked', true);
            $('#btn_select_actions_table_continue').attr('data-flag', '1');
            $('#btn_select_actions_table_continue').attr('data-original-title', 'Desmarcar Todos');
            $('#select_actions_all_continue').val('1');
        }
        else
        {
            $('.check-add-continue').prop('checked', false);
            $('#btn_select_actions_table_continue').attr('data-flag', '0');
            $('#btn_select_actions_table_continue').attr('data-original-title', 'Marcar Todos');
            $('#select_actions_all_continue').val('0');
        }
    });

    //ADD
    $('#btn_add').on('click', function ()
    {
        $('.content-actions-table').addClass('d-none');
        $('#btn_confirm_add').addClass('d-none');
        $('#view_table').addClass('d-none');
        $('#view_form_add').removeClass('d-none');

        $('#id_role').empty().trigger('change');
        $('#id_submodule').empty().trigger('change');
        validate.resetForm();
        $('#form_add')[0].reset();
        $('#select_actions_all').val('0');
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

        $('#view_form_add').addClass('d-none');
        $('#view_table').removeClass('d-none');
    });

    //ADD AND CONTINUE
    $('#btn_add_continue').on('click', function ()
    {
        $('.content-actions-table-continue').addClass('d-none');
        $('#btn_confirm_add_continue').addClass('d-none');
        $('#view_table').addClass('d-none');
        $('#view_form_add_continue').removeClass('d-none');

        $('#id_submodule_continue').empty().trigger('change');
        validate_continue.resetForm();
        $('#select_actions_all_continue').val('0');
    });

    $('#btn_confirm_add_continue').on('click', function()
    {
        $('#form_add_continue').submit();
    });

    $('#form_add_continue').ajaxForm(
    {
        dataType:  'json',
        success:   add_continue,
        beforeSubmit: function()
        {
            $('#loading').removeClass('d-none');
        }
    });

    $('#btn_cancel_add_continue').on('click', function()
    {
        var defaultTable = $('#default_table').DataTable();
        defaultTable.ajax.reload();

        $('#view_form_add_continue').addClass('d-none');
        $('#view_table').removeClass('d-none');
    });

    //DROP
    $('#default_table').on('click', 'a.remove-row', function()
    {
        var permission = $(this);
        var $row = $(this).closest('tr');

        $('#modal_delete').iziModal({
            title: 'Eliminar permiso',
            icon: 'fas fa-trash-alt',
            headerColor: '#dc3545',
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
                            id_role:      permission.attr('data-role'),
                            id_submodule: permission.attr('data-submodule'),
                            id_action:    permission.attr('data-action')
                        },
                        dataType: 'json',
                        success: function (response)
                        {
                            modal_alert(response.data, response.message);
                        },
                        error: function ()
                        {
                            modal_alert(false, 'Conexión fallida, por favor intentelo nuevamente.');
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
        $('#select_actions_all').val('0');
    }
}

function add_continue(response)
{
    var validate_continue = $('#form_add_continue').validate(
    {
        rules:
        {
            id_role: 'required',
            id_submodule: 'required'
        },
        messages:
        {
            id_role: 'Por favor ingresa el rol',
            id_submodule: 'Por favor ingresa el submodulo'
        },
        errorPlacement: function(error, element)
        {
            switch (element.prop('type'))
            {
                case 'select-one':
                    error.insertAfter(element.next('.select2-container'));
                    break;
                default:
                    error.insertAfter(element);
            }
        }
    });

    modal_alert_and_continue(response.data, response.message);

    if (response.data)
    {
        $('#id_submodule_continue').empty().trigger('change');
        validate_continue.resetForm();
        $('#id_role_continue').val(response.id_role).trigger('change.select2');
        $('#select_actions_all_continue').val('0');
        $('.content-actions-table').addClass('d-none');
        $('.content-actions-table-continue').addClass('d-none');
        $('#btn_confirm_add_continue').addClass('d-none');
    }
}