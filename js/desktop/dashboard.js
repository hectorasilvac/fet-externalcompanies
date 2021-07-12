$(function()
{
    session_company_project();

 	$('#modal_companies').iziModal(
 	{
		title: 'Listado de empresas',
		subtitle: 'Selecciona una empresa',
		icon: 'far fa-building',
		width: 900,
		headerColor: '#0473BA',
	});

 	$('#btn_group_company_1').on('click', function()
 	{
 		$('#modal_companies').iziModal('open');
 	});

 	$('#id_company').select2({
        theme: 'bootstrap4',
        width: '100%',
        language: 'es',
        placeholder: 'Selecciona empresa',
        allowClear: true,
        ajax: {
            url: $path_companies,
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
        $('#id_project').val(null).trigger('change');
        $('#text_company').val($(this).text());
    });

    $('#id_project').select2({
        theme: 'bootstrap4',
        width: '100%',
        language: 'es',
        placeholder: 'Selecciona proyecto',
        allowClear: true,
        ajax: {
            url: $path_projects,
            dataType: 'json',
            delay: 250,
            data: function(params)
            {
                var id = $('#id_company option:selected').val();

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
        $('#text_project').val($(this).text());
    });

    var validate = $('#form_project_session').validate(
    {
        rules:
        {
            id_company:
            {
                required: true
            }
        },
        messages:
        {
            id_company:
            {
                required: 'Por favor selecciona la empresa.'
            }
        },
        errorPlacement: function (error, element)
        {
            error.addClass('invalid-feedback');
			error.insertAfter(element.next('.select2-container'));                
        }
    });

    $('#form_project_session').ajaxForm(
    {
        dataType:  'json',
        success:   function(response)
        {
            modal_alert(response.data, response.message);
            localStorage.setItem('user_company', $('#id_company').val());
            localStorage.setItem('user_company_text', $('#id_company').text());

            if ($('#id_project').val() != '' && $('#id_project').val() != null)
            {
                localStorage.setItem('user_project', $('#id_project').val());
                localStorage.setItem('user_project_text', $('#id_project').text());
            }
            else
            {
                localStorage.removeItem('user_project');
                localStorage.removeItem('user_project_text');
            }
        },
        beforeSubmit: function() 
        { 
            $('#loading').removeClass('d-none');
        }
    });
});

function session_company_project()
{
    if (localStorage.getItem('user_company') != null)
    {
        var option = new Option(localStorage.getItem('user_company_text'), localStorage.getItem('user_company'), true, true);
        $('#id_company').append(option).trigger('change');
    }
    
    if (localStorage.getItem('user_project') != null)
    {
        var option = new Option(localStorage.getItem('user_project_text'), localStorage.getItem('user_project'), true, true);
        $('#id_project').append(option).trigger('change');
    }
}

