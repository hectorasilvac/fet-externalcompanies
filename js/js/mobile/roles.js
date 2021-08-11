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
                data: 'name_role',
                createdCell:  function(td, cellData, rowData, row, col)
                {
                   $(td).attr('data-label', 'Rol');
                },
                render: function(data, type, row)
                {
                    return '<br><br><div class="span-left"><span id="name_rol" data-type="text" data-pk="' + row.id_role + '" data-url="' + $path_edit + '">' + data + '</span></div>';
                }
            },
            {
                targets: [1],
                data: 'id_role',
                render: function(data, type, row)
                {
                    var content = '<div class="span-center">';

                    if(act_drop)
                    {
                        content += '<a data-toggle="tooltip" data-placement="top" title="Eliminar" href="javascript:void(0)" class="remove-row pd-x-5-force" data-id="' + data + '""><i class="fas fa-trash"></i></a>' ;
                    }

                    if(act_trace)
                    {
                        content += '<a data-toggle="tooltip" data-placement="top" title="Trazabilidad" href="javascript:void(0)" class="trace-row pd-x-5-force" data-id="' + data + '" onclick="trace(\'' + $path_trace + '\', \'id_role\',' + data + ')"><i class="fas fa-history"></i></a>' ;
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

            if (act_edit == '1')
            {
                $('#default_table td span').editable(
                {
                    validate: function(value)
                    {
                        if (value === null || value === '')
                        {
                            return 'Campo obligatorio.';
                        }

                        if (value.length > 100)
                        {
                            return 'Solo se permiten 100 caracteres.';
                        }

                        if(value.match(/[^a-zA-ZáéíóúñÑÁÉÍÓÚ ]/g))
                        {
                            return 'Solo se permiten letras.'; 
                        }

                    },
                    success: function(response, newValue) 
                    {
                        response = $.parseJSON(response);
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
            name_role:
            {
                required: true,
                lettersonly_es: true,
                maxlength: 100
            }
        },
        messages:
        {
            name_role:
            {
                required: 'Por favor ingresa el rol.',
                lettersonly_es: 'Solo se permiten letras.',
                maxlength: 'Solo se permiten 100 caracteres.'
            }
        }
    });

    $('#btn_add').on('click', function()
    {
        $('#view_table').addClass('d-none');
        $('#view_form_add').removeClass('d-none');
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

    //DELETE
    $('#default_table').on('click', 'a.remove-row', function()
    {
        var id = $(this).attr('data-id');

        $('#modal_delete').iziModal({
            title: 'Eliminar rol',
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
                            id_role: id
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
                            $('#loading').addClass('d-none');
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