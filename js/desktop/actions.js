$(function($)
{
    //BUILD - DESKTOP
    $.fn.editable.defaults.mode = 'inline';

    var default_table = $('#default_table').DataTable(
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
                data: 'name_action',
                render: function(data, type, row)
                {
                    return '<span>' + data + '</span>';
                }
            },
            {
                targets: [2],
                data: 'name_es_action',
                render: function(data, type, row)
                {
                    return '<span>' + data + '</span>';
                }
            },
            {
                targets: [3],
                data: 'id_action',
                orderable: false,
                render: function(data, type, row)
                {
                    var content = '<div class="span-center">';

                    if(act_trace)
                    {
                        content += '<a data-toggle="tooltip" data-placement="top" title="Trazabilidad" href="javascript:void(0)" class="trace-row pd-x-5-force" data-id="' + data + '" onclick="trace(\'' + $path_trace + '\', \'id_action\',' + data + ')"><i class="fas fa-history"></i></a>' ;
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
        }
    });

    var validate = $('#form_add').validate(
    {
        rules:
        {
            name_action:
            {
                required: true,
                lettersonly: true,
                maxlength: 100
            },
            name_es_action:
            {
                required: true,
                lettersonly_es: true,
                maxlength: 100
            }
        },
        messages:
        {
            name_action:
            {
                required: 'Por favor ingresa la acción.',
                lettersonly: 'Solo se permiten letras.',
                maxlength: 'Solo se permiten 100 caracteres.'
            },
            name_es_action:
            {
                required: 'Por favor ingresa su significado.',
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

});

function add(response)
{
    modal_alert(response.data, response.message);

    if (response.data)
    {
        $('#view_form_add').addClass('d-none');
    }
}