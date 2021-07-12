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
                data: 'name_action',
                createdCell:  function(td, cellData, rowData, row, col)
                {
                   $(td).attr('data-label', 'Acción');
                },
                render: function(data, type, row)
                {
                    return '<br><br><div class="span-left"><span id="name_action" data-type="text">' + data + '</span></div>';
                }
            },
            {
                targets: [1],
                data: 'name_es_action',
                createdCell:  function(td, cellData, rowData, row, col)
                {
                   $(td).attr('data-label', 'Significado');
                },
                render: function(data, type, row)
                {
                    return '<br><br><div class="span-left"><span id="name_es_action" data-type="text">' + data + '</span></div>';
                }
            },
            {
                targets: [2],
                data: 'id_action',
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