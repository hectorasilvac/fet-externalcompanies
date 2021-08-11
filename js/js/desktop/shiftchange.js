$(function($)
{
    //BUILD DESKTOP
    $.fn.editable.defaults.mode = 'inline';

    shiftchange_datatable();

    $('#shiftchange_state, #shiftchange_coordinator').on('change', function ()
    {
        shiftchange_datatable();
    });

    $('#id_worker_applicant').select2({
        theme: 'bootstrap4',
        width: '100%',
        language: 'es',
        placeholder: 'Seleccionate',
        allowClear: true,
        ajax: {
            url: $path_workers,
            dataType: 'json',
            delay: 250,
            data: function(params)
            {
                var id = $('#id_worker_applicant option:selected').val();

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

    $('#id_worker_replacement').select2({
        theme: 'bootstrap4',
        width: '100%',
        language: 'es',
        placeholder: 'Selecciona tu reemplazo',
        allowClear: true,
        ajax: {
            url: $path_workers,
            dataType: 'json',
            delay: 250,
            data: function(params)
            {
                var id = $('#id_worker_replacement option:selected').val();

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

    $('#shiftchange_coordinator').select2({
        theme: 'bootstrap4',
        language: 'es',
        placeholder: 'Reportado a',
        allowClear: true,
        ajax: {
            url: $path_coordinators,
            dataType: 'json',
            delay: 250,
            data: function(params)
            {
                var id = $('#shiftchange_coordinator option:selected').val();

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
    });

    $('#id_coordinator').select2({
        theme: 'bootstrap4',
        width: '100%',
        language: 'es',
        placeholder: 'Selecciona tu coordinador de enlace',
        allowClear: true,
        ajax: {
            url: $path_coordinators,
            dataType: 'json',
            delay: 250,
            data: function(params)
            {
                var id = $('#id_coordinator option:selected').val();

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

    
    var validate = $('#form_add').validate(
    {
        rules:
        {
            id_worker_applicant:
            {
                required: true
            },
            email_worker_applicant:
            {
                required: true,
                email: true,
                maxlength: 50
            },
            id_worker_replacement:
            {
                required: true
            },
            email_worker_replacement:
            {
                required: true,
                email: true,
                maxlength: 50
            },
            date_shiftchange:
            {
                required: true
            },
            type_shiftchange:
            {
                required: true
            },
            date_return_shiftchange:
            {
                required: true
            },
            type_return_shiftchange:
            {
                required: true
            },
            id_coordinator:
            {
                required: true
            },
            flag_signature_applicant:
            {
                required: true
            }
        },
        messages:
        {
            id_worker_applicant:
            {
                required: 'Seleccionate dentro de la lista.'
            },
            email_worker_applicant:
            {
                required: 'Ingresa tu correo electronico personal.',
                email: 'Valida que el correo este correcto.',
                maxlength: 'Solo se permiten 50 caracteres.'
            },
            id_worker_replacement:
            {
                required: 'Selecciona a tu reemplazo dentro de la lista.'
            },
            email_worker_replacement:
            {
                required: 'Ingresa el correo electronico personal de tu reemplazo.',
                email: 'Valida que el correo este correcto.',
                maxlength: 'Solo se permiten 50 caracteres.'
            },
            date_shiftchange:
            {
                required: 'Selecciona la fecha de cambio.'
            },
            type_shiftchange:
            {
                required: 'Selecciona el turno de cambio.'
            },
            date_return_shiftchange:
            {
                required: 'Selecciona la fecha de devolución.'
            },
            type_return_shiftchange:
            {
                required: 'Selecciona el turno de devolución.'
            },
            id_coordinator:
            {
                required: 'Selecciona tu coordinador de enlace.'
            },
            flag_signature_applicant:
            {
                required: 'Por favor autoriza la firma del cambio.'
            }
        },
        errorPlacement: function (error, element)
        {
            error.addClass('invalid-feedback');

            switch (element.prop('name'))
            {
                case 'id_worker_applicant':
                    error.insertAfter(element.next('.select2-container'));
                    break;
                case 'id_worker_replacement':
                    error.insertAfter(element.next('.select2-container'));
                    break;
                case 'id_coordinator':
                    error.insertAfter(element.next('.select2-container'));
                    break;
                case 'flag_signature_applicant':
                    error.insertAfter(element.parent('.ckbox'));
                    break;
                default:
                    error.insertAfter(element);
            }
        }
    });

    $('#btn_add').on('click', function()
    {
        $('#view_table').addClass('d-none');
        $('#view_form_add').removeClass('d-none');
        $('#id_worker_applicant').empty().trigger('change');
        $('#id_worker_replacement').empty().trigger('change');
        $('#id_coordinator').empty().trigger('change');
        validate.resetForm();
        $('#form_add')[0].reset();
    });

    $('#date_shiftchange, #date_return_shiftchange').datepicker({
        language: 'es-ES',
        format: 'dd-mm-yyyy',
        startDate: day_now,
        endDate: month,
        autoHide: true
    });

    $('#date_shiftchange, #date_return_shiftchange').on('change', function()
    {
        $(this).valid();
    });

    $('#btn_confirm_add').on('click', function()
    {
        if ($('#form_add').valid())
        {
            $('#modal_signature').iziModal({
                title: 'Firmar cambio de turno',
                icon: 'fas fa-file-signature',
                headerColor: '#224978',
                zindex: 9999,
                onClosed: function()
                {
                    $('#btn_confirm_sign').off('click');
                    $('#modal_signature').iziModal('destroy');
                },
                onOpening: function()
                {
                    $('#btn_confirm_sign').on('click', function()
                    {
                        $('#form_add').submit();
                        $('#modal_signature').iziModal('close');
                    });

                    $('#btn_cancel_sign').on('click', function()
                    {
                        $('#modal_signature').iziModal('close');
                    });
                }
            });

            $('#modal_signature').iziModal('open');
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

    $('#btn_cancel_add').on('click', function()
    {
        var defaultTable = $('#default_table').DataTable();
        defaultTable.ajax.reload();
        $('#id_worker_applicant').empty().trigger('change');
        $('#id_worker_replacement').empty().trigger('change');
        $('#id_coordinator').empty().trigger('change');
        validate.resetForm();
        $('#form_add')[0].reset();
        $('#view_form_add').addClass('d-none');
        $('#view_table').removeClass('d-none');
    });

    $('#default_table').on('click', 'a.detail-row', function()
    {
        $('#loading').removeClass('d-none');
        var id_shiftchange = $(this).attr('data-id');
        $('.firm_applicant').html('');
        $('.firm_replacement').html('');
        $('.firm-applicant-detail').addClass('d-none');
        $('.file-detail td').html('');
        $('.file-detail').addClass('d-none');

        $.ajax({
            type: 'POST',
            url: $path_detail,
            data: {
                id_shiftchange: id_shiftchange,
            },
            dataType: 'json',
            success: function (response)
            {
                if (response.data)
                {
                    $.each(response.data, function( key, value )
                    {
                        if (key == 'signature_applicant' && value != null && value != '')
                        {
                            if (value.includes('base64'))
                            {
                                var i = new Image();
                                i.src = "data:" + value;
                                i.className = "wd-100p";
                                $('.firm_applicant').html(i);
                                $('.firm-applicant-detail').removeClass('d-none');
                            }
                            else
                            {
                                $('.firm_applicant').html('<img class="ht-100" src="' + $path_signature + value + '" alt="Firma solicitante">');
                            }
                        }
                        else if (key == 'signature_replacement' && value != null && value != '')
                        {
                            if (value.includes('base64'))
                            {
                                var i = new Image();
                                i.src = "data:" + value;
                                i.className = "wd-100p";
                                $('.firm_replacement').html(i);
                            }
                            else
                            {
                                $('.firm_replacement').html('<img class="ht-100" src="' + $path_signature + value + '" alt="Firma reemplazo">');
                            }
                        }
                        else
                        {
                            $('.' + key).html(value);
                        }
                    });

                    $('#view_table').addClass('d-none');
                    $('#view_detail').removeClass('d-none');
                    $('#loading').addClass('d-none');
                }
                else
                {
                    modal_alert(response.data, response.message);
                }

                $('#loading').addClass('d-none');
            },
            error: function () {
                modal_alert(false, 'Error de conexión.');
            }
        });
    });

    $('#btn_cancel_detail').on('click', function()
    {
        $('#view_detail').addClass('d-none');
        $('#view_table').removeClass('d-none');
    });

    $('#default_table').on('click', 'a.mail-row', function()
    {
        var id_shiftchange = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: $path_mail_replacement,
            data: {
                id_shiftchange: id_shiftchange
            },
            dataType: 'json',
            success: function (response)
            {
                modal_alert(response.data, response.message);
            },
            error: function ()
            {
                modal_alert(false, 'Error de conexión.');
            }
        });
    });

    $('#default_table').on('click', 'a.assign-row', function()
    {
        var id_shiftchange = $(this).attr('data-id');

        $('#modal_assign').iziModal({
            title: 'Aprobar cambio de turno',
            icon: 'fa fa-check',
            headerColor: '#f7941e',
            zindex: 9999,
            onClosed: function()
            {
                $('#btn_confirm_assign').off('click');
                $('#modal_assign').iziModal('destroy');
            },
            onOpening: function()
            {
                $('#btn_confirm_assign').on('click', function()
                {
                    var vob_shiftchange = $('#vob_shiftchange').val();

                    $('#loading').removeClass('d-none');

                    $.ajax({
                        type: 'POST',
                        url: $path_assign,
                        data: {
                            id_shiftchange: id_shiftchange,
                            vob_shiftchange: vob_shiftchange
                        },
                        dataType: 'json',
                        success: function (response)
                        {
                            var defaultTable = $('#default_table').DataTable();
                            defaultTable.ajax.reload();
                            modal_alert(response.data, response.message);
                        },
                        error: function ()
                        {
                            modal_alert(false, 'Error de conexión.');
                        }
                    });

                    $('#modal_assign').iziModal('close');
                });

                $('#btn_cancel_assign').on('click', function()
                {
                    $('#modal_assign').iziModal('close');
                });
            }
        });

        $('#modal_assign').iziModal('open');
    });

    var validate_change = $('#form_change_assign').validate(
    {
        rules:
        {
            id_coordinator:
            {
                required: true
            }
        },
        messages:
        {
            id_coordinator:
            {
                required: 'Selecciona tu coordinador de enlace.'
            }
        },
        errorPlacement: function (error, element)
        {
            error.addClass('invalid-feedback');
            error.insertAfter(element.next('.select2-container'));        
        }
    });

    $('#id_coordinator_change').select2({
        theme: 'bootstrap4',
        width: '100%',
        language: 'es',
        placeholder: 'Selecciona coordinador de enlace',
        allowClear: true,
        ajax: {
            url: $path_coordinators,
            dataType: 'json',
            delay: 250,
            data: function(params)
            {
                var id = $('#id_coordinator_change option:selected').val();

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

    $('#form_change_assign').ajaxForm(
    {
        dataType:  'json',
        success:   function (response){
            modal_alert(response.data, response.message);
            $('#id_shiftchange_change').val('');
            $('#id_coordinator_change').val('');
            $('#id_coordinator_change').empty().trigger('change');
            validate_change.resetForm();
            $('#view_change_assign').addClass('d-none');
            $('#loading').addClass('d-none');
        },
        beforeSubmit: function()
        {
            $('#loading').removeClass('d-none');
        }
    });

    $('#default_table').on('click', 'a.change-row', function()
    {
        $('#loading').removeClass('d-none');
        $('#id_shiftchange_change').val($(this).attr('data-id'));
        $('#id_coordinator_change').val($(this).attr('data-coo'));
        var option = new Option($(this).attr('data-tcoo'), $(this).attr('data-coo'), true, true);
        $('#id_coordinator_change').append(option).trigger('change');
        $('#view_table').addClass('d-none');
        $('#view_change_assign').removeClass('d-none');
        $('#loading').addClass('d-none');
    });

    $('#btn_cancel_change').on('click', function()
    {
        $('#id_shiftchange_change').val('');
        $('#id_coordinator_change').val('');
        $('#id_coordinator_change').empty().trigger('change');
        validate_change.resetForm();
        $('#view_change_assign').addClass('d-none');
        $('#view_table').removeClass('d-none');
    });

    $('#default_table').on('click', 'a.remove-row', function()
    {
        var id_shiftchange = $(this).attr('data-id');

        $('#modal_delete').iziModal({
            title: 'Eliminar cambio de turno',
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
                            id_shiftchange: id_shiftchange,
                        },
                        dataType: 'json',
                        success: function (response)
                        {
                            var defaultTable = $('#default_table').DataTable();
                            defaultTable.ajax.reload();
                            modal_alert(response.data, response.message);
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

function shiftchange_datatable()
{
    $('#loading').removeClass('d-none');

    $('#default_table').DataTable(
    {
        language:
        {
            sUrl: 'resources/lib/datatables/Spanish.json',
        },
        destroy: true,
        info: true,
        lengthChange: true,
        processing: true,
        serverSide: true,
        ajax:
        {
            url: $path_view,
            dataType: 'json',
            data:
            {
                shiftchange_state: $('#shiftchange_state').val(),
                shiftchange_coordinator: $('#shiftchange_coordinator').val(),
            },
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
                data: 'name_worker_applicant',
                render: function(data, type, row)
                {
                    var profession_aa = '';

                    if (row.profession_aa != null)
                    {
                        profession_aa = '<br><b>PROFESIÓN: </b>' + row.profession_aa;
                    }

                    return '<div><span>' + data + profession_aa + '<br><b>TURNO DE CAMBIO: </b>' + row.type_shiftchange + '</span></div>';
                }
            },
            {
                targets: [2],
                data: 'date_shiftchange',
                render: function(data, type, row)
                {
                    return '<div class="span-center"><span>' + data + '</span></div>';
                }
            },
            {
                targets: [3],
                data: 'name_worker_replacement',
                render: function(data, type, row)
                {
                    var profession_ar = '';

                    if (row.profession_ar != null)
                    {
                        profession_ar = '<br><b>PROFESIÓN: </b>' + row.profession_ar;
                    }

                    return '<div><span>' + data + profession_ar + '<br><b>TURNO DE REEMPLAZO: </b>' + row.type_return_shiftchange + '</span></div>';
                }
            },
            {
                targets: [4],
                data: 'date_return_shiftchange',
                render: function(data, type, row)
                {
                    return '<div class="span-center"><span>' + data + '</span></div>';
                }
            },
            {
                targets: [5],
                data: 'vob_shiftchange',
                render: function(data, type, row)
                {
                    var vob = '';

                    if (data == '0')
                    {
                        vob = '<div class="span-center"><span data-toggle="tooltip" data-placement="top" title="Sin Aprobar"><i class="fa fa-minus tx-warning"></i></span></div>';
                    }
                    else if (data == '1')
                    {
                        vob = '<div class="span-center"><span data-toggle="tooltip" data-placement="top" title="Aprobado"><i class="fa fa-check tx-primary"></i></span></div>';
                    }
                    else if (data == '2')
                    {
                        vob = '<div class="span-center"><span data-toggle="tooltip" data-placement="top" title="Rechazado"><i class="fa fa-times tx-danger"></i></span></div>';
                    }

                    return vob;
                }
            },
            {
                targets: [6],
                data: 'id_shiftchange',
                orderable: false,
                render: function(data, type, row)
                {
                    var content = '<div class="span-center">';

                    if (flag_worker && row.vob_shiftchange == '0')
                    {
                        content += '<a data-toggle="tooltip" data-placement="top" title="Revisar, Aprobar o Rechazar" href="' + $path_application + 'shiftchanged/ebd082b0f8' + data + '" class="pd-x-5-force"><i class="fas fa-file-signature"></i></a>';
                    
                    }

                    if (act_detail)
                    {
                        content += '<a data-toggle="tooltip" data-placement="top" title="Detalle" href="javascript:void(0)" class="detail-row pd-x-5-force" data-id="' + data + '"><i class="fas fa-asterisk"></i></a>';
                    }

                    if (act_assign && row.vob_shiftchange == '0')
                    {
                        content += '<a data-toggle="tooltip" data-placement="top" title="Enviar a Reemplazo" href="javascript:void(0)" class="mail-row pd-x-5-force" data-id="' + data + '"><i class="fa fa-at"></i></a>'
                                + '<a data-toggle="tooltip" data-placement="top" title="Aprobar" href="javascript:void(0)" class="assign-row pd-x-5-force" data-id="' + data + '"><i class="fa fa-check"></i></a>'
                                + '<a data-toggle="tooltip" data-placement="top" title="Cambiar coordinador" href="javascript:void(0)" class="change-row pd-x-5-force" data-id="' + data + '" data-coo="' + row.id_coordinator + '" data-tcoo="' + row.coordinator + '"><i class="fas fa-user-edit"></i></a>';
                    }

                    if (act_drop && flag_worker && row.vob_shiftchange == '0')
                    {
                        content += '<a data-toggle="tooltip" data-placement="top" title="Eliminar" href="javascript:void(0)" class="remove-row pd-x-5-force" data-id="' + data + '"><i class="fas fa-trash"></i></a>';
                    }

                    if (act_drop && flag_worker == '0' && row.vob_shiftchange == '1')
                    {
                        content += '<a data-toggle="tooltip" data-placement="top" title="Eliminar" href="javascript:void(0)" class="remove-row pd-x-5-force" data-id="' + data + '"><i class="fas fa-trash"></i></a>';
                    }

                    if(act_trace)
                    {
                        content += '<a data-toggle="tooltip" data-placement="top" title="Trazabilidad" href="javascript:void(0)" class="trace-row pd-x-5-force" data-id="' + data + '" onclick="trace(\'' + $path_trace + '\', \'id_shiftchange\',' + data + ')"><i class="fas fa-history"></i></a>';
                    }

                    return content + '</div>';
                },
                visible: (act_detail || act_assign || act_drop || act_trace ? true : false)
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

            $('#loading').addClass('d-none');
        }
    });
}

function add(response)
{
    modal_alert(response.data, response.message);

    if (response.data)
    {
        $('#view_form_add').addClass('d-none');
    }
}