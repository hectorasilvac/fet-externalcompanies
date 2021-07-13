function location_select2({ selectRef, dataName, placeholder, parentId = null, parentName = null, disabled = false }) {
    return $(selectRef).select2({
        disabled: disabled,
        theme: 'bootstrap4',
        width: '100%',
        language: 'es',
        placeholder: placeholder,
        allowClear: true,
        ajax: {
            url: $path_location,
            dataType: 'json',
            delay: 250,
            data: function(params)
            {
                var id = $(`${selectRef} option:selected`).val();

                return {
                    name: dataName,
                    q: params.term,
                    page: params.page || 1,
                    id: id,
                    parentId: parentId,
                    parentName: parentName,
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
}

// Method for validating letters of the Spanish alphabet
jQuery.validator.addMethod(
    "lettersonly_es",
    function (value, element) {
        return this.optional(element) || /^[a-zA-Z\s\u00f1\u00d1]*$/.test(value);
    },
    "Este campo no puede contener números."
);

function validate_input({ 
    value,
    minLength   = null,
    maxLength   = null,
    isEmail     = null,
    isText      = null,
    isNumeric   = null
 })
{
    if ($.trim(value) == "")                                          return "No puede estar vacío";
    if (minLength !== null && value.length < minLength)               return "Debe contener mínimo 3 letras";
    if (maxLength !== null && value.length > maxLength)               return "Debe contener máximo 50 letras";
    if (isText    !== null && value.match(/[^a-zA-ZáéíóúñÑÁÉÍÓÚ ]/g)) return "Solo se permiten letras";
    if (isEmail   !== null && !value.match(/^\S+@\S+\.\S+$/))         return "El correo electrónico no es válido";
    if (isNumeric !== null && !value.match(/^[0-9]+$/))               return "El campo debe contener solo números";
}

// Code to execute when the DOM is fully loaded 
$(document).ready(function () {

    $.fn.editable.defaults.mode = "inline";

// Implementing Select2 in the country, department and city fields.
    location_select2({
    selectRef: "#country_cv_ec",
    dataName: "countries",
    placeholder: "Selecciona un país"
  }).on('change', function (e)
  {
    var countryParent = $("#country_cv_ec option:selected").val();

    if (typeof countryParent !== 'undefined') {
        location_select2({
          selectRef: "#department_cv_ec",
          dataName: "departments",
          placeholder: "Selecciona un departamento",
          parentId: countryParent,
          parentName: 'countries'
        });
    } else {
            $("#department_cv_ec").prop('disabled', true);
            $("#department_cv_ec").empty();

            $("#city_cv_ec").prop('disabled', true);
            $("#city_cv_ec").empty(); 
    }

  });

  $("#department_cv_ec")
    .select2({
      placeholder: "Seleccione un departamento",
      theme: "bootstrap4",
      disabled: true,
      width: '100%',
    })
    .on("change", function (e) {
      var departmentParent = $("#department_cv_ec option:selected").val();

      if (typeof departmentParent !== "undefined") {
        location_select2({
          selectRef: "#city_cv_ec",
          dataName: "cities",
          placeholder: "Selecciona una ciudad",
          parentId: departmentParent,
          parentName: "departments",
        });
      }  else {

        $("#city_cv_ec").prop('disabled', true);
        $("#city_cv_ec").empty();  
}
    });

  $("#city_cv_ec").select2({
    placeholder: "Seleccione una ciudad",
    theme: "bootstrap4",
    disabled: true,
    width: '100%',
  });

  // Implementing jQuery Validate in all fields.
  var validateForm = $("#form_add").validate({
    onkeyup: false,
    ignore: [],
    rules: {
        normalizer: function (value) {
            return $.trim(value);
        },
        onkeyup: false,
        focusCleanup: true,
        name_cv_ec: {
            required: true,
            minlength: 3,
            maxlength: 50,
            lettersonly_es: true,
        },
        nit_cv_ec: {
            digits: true,
            minlength: 3,
            maxlength: 15,
        },
        type_cv_ec: {
            required: true,
        },
        email_cv_ec: {
            email: true,
            minlength: 3,
            maxlength: 50,
            // remote: {
            //     url: window.location.href + "/check-email",
            //     type: "post",
            // },
        },
        phone_cv_ec: {
            digits: true,
            minlength: 7,
            maxlength: 10,
        },
        address_cv_ec: {
            minlength: 5,
            maxlength: 80,
        },
        country_cv_ec: {
            required: true,
        },
        department_cv_ec: {
            required: true,
        },
        city_cv_ec: {
            required: true,
        }
    },
    messages: {
        name_cv_ec: {
            required: "Ingrese el nombre de la empresa",
            minlength: "El nombre debe tener al menos 3 caracteres",
            maxlength: "El nombre debe tener máximo 50 caracteres",
            lettersonly_es: "El nombre solo debe contener letras",
        },
        nit_cv_ec: {
            digits: "El NIT debe contener sólo números",
            minlength: "El NIT debe tener al menos 3 caracteres",
            maxlength: "El NIT debe tener máximo 15 caracteres"
        },
        type_cv_ec: {
            required: "Seleccione el tipo de empresa",
        },
        email_cv_ec: {
            email: "El formato del correo electrónico no es válido",
            minlength: "El correo electrónico debe tener al menos 3 caracteres",
            maxlength: "El correo electrónico debe tener máximo 50 caracteres",
            // remote: "El correo electrónico ya existe",
        },
        phone_cv_ec: {
            digits: "El número de teléfono debe contener sólo números",
            minlength: "El número de teléfono debe tener al menos 7 caracteres",
            maxlength: "El número de teléfono debe tener máximo 10 caracteres",
        },
        address_cv_ec: {
            minlength: "La dirección debe contener al menos 5 caracteres",
            maxlength: "La dirección debe contener máximo 60 caracteres",
        },
        country_cv_ec: {
            required: "Seleccione el país",
        },
        department_cv_ec: {
            required: "Seleccione el departamento",
                },
        city_cv_ec: {
            required: "Seleccione la ciudad",
        }
    },
    errorElement: "small",
    errorPlacement: function (error, element) {
        $(error).addClass("invalid-feedback font-weight-normal");

            $(error).insertAfter(element);
    },
    submitHandler: function (form) {
        submit(form);
        return false;
    },
});

function submit(form) {
  $(form).ajaxSubmit({
    dataType: "json",
    type: "post",
    resetForm: true,
    clearForm: true,
    success: function ({ data, message }) {
      $('#error-list').empty();

      if (data === false && typeof message == 'object') {
        Object.values(message).forEach((item) => {
            $(`<li>${item}</li>`).appendTo("#error-list");
        });

        $("#form-errors").removeClass("d-none");
        return false;
      }

      if (data === false && typeof message == 'string') {
        $(`<li>${message}</li>`).appendTo("#error-list");

        errorAlert(message);

        $("#form-errors").removeClass("d-none");
        return false;
    }

      $("#form-errors").addClass("d-none");
      successAlert("Empresa agregada correctamente");

      $('.select2-hidden-accessible').empty();

      $('#form_add')[0].reset();

      table.ajax.reload();
    },
  });
}

// Implementing DataTable
var table = $('#default_table').DataTable({
    info: true,
    orderCellsTop: true,
    lengthChange: true,
    fixedHeader: true,
    processing: true,
    serverSide: true,
    language: {
        sUrl: '/resources/lib/datatables/Spanish.json',
    },
    pagingType: 'numbers',
    processing: true,
    serverSide: true,
    ajax: {
        url: $path_view,
        dataType: 'json',
        type: 'POST',
    },
    columnDefs: [
        {
            targets: [0],
            data: 'name_cv_ec',
            render: function (data, type, row) {
                return `<span data-name='name_cv_ec' data-type='text' data-pk='${row.id_cv_ec}' data-url='${$path_edit}'>${data}</span>`;
            },
        },
        {
            targets: [1],
            data: 'nit_cv_ec',
            render: function (data, type, row) {
                return `
                <span 
                class='nit_cv_ec' 
                data-pk="">
                ${data}
                </span>
                `;
            },
        },
        {
            targets: [2],
            data: 'type_cv_ec',
            render: function (data, type, row) {
                return `
                <span 
                class='type_cv_ec' 
                data-pk="">
                ${data}
                </span>
                `;
            },
        },
        {
            targets: [3],
            data: 'email_cv_ec',
            render: function (data, type, row) {
                return `
                <span 
                class='email_cv_ec' 
                data-pk="">
                ${data}
                </span>
                `;
            },
        },
        {
            targets: [4],
            data: 'phone_cv_ec',
            render: function (data, type, row) {
                return `
                <span 
                class='phone_cv_ec' 
                data-pk="">
                ${data}
                </span>
                `;
            },
        },
        {
            targets: [5],
            data: 'id_cv_ec',
            render: function (data, type, row) 
            {
                var content = '<div class="span-center">';

                if (act_edit)
                {
                    content +=  '<a data-toggle="tooltip" data-placement="top" title="Editar clave" href="javascript:void(0)" class="edit-row pd-x-5-force" data-id="' + data + '"><i class="fas fa-pencil-alt"></i></a>'
                }

                if (act_details)
                {
                    content +=  '<a data-toggle="tooltip" data-placement="top" title="Editar clave" href="javascript:void(0)" class="edit-row pd-x-5-force" data-id="' + data + '"><i class="fas fa-asterisk"></i></a>'
                }

                if (act_assign)
                {
                    content +=  '<a data-toggle="tooltip" data-placement="top" title="Editar clave" href="javascript:void(0)" class="edit-row pd-x-5-force" data-id="' + data + '"><i class="fas fa-exchange-alt"></i></a>'
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

                // return `
                // <span 
                // class='phone_cv_ec' 
                // data-pk="">
                // ${data}
                // </span>
                // `;
            },
        },
    ],
    drawCallback: function (settings) {
        var rows = this.fnGetData();
        // var inputSearch = $('.dataTables_filter input').val();

        // if (rows.length == 0)
        // {
        //     $('#btn_export_xlsx').removeAttr('href');
        // }
        // else
        // {
        //     if (inputSearch != '')
        //     {
        //         $('#btn_export_xlsx').attr('href', $path_export_xlsx + '/?search=' + inputSearch);
        //     }
        //     else
        //     {
        //         $('#btn_export_xlsx').attr('href', $path_export_xlsx);
        //     }
        // }

        if (act_edit)
        {
            $('#default_table td span[data-type]').editable(
            {
                validate: function(value)
                {
                    return validate_input({
                        value       : value,
                        minLength   : $(this).attr('data-minlength')          || null,
                        maxLength   : $(this).attr('data-maxlength')          || null, 
                        isEmail     : $(this).attr('data-type') == 'email'    || null,
                        isNumeric   : $(this).attr('data-type') == 'numeric'  || null,
                        isText      : $(this).attr('data-type') == 'text'     || null,
                    });
                },
                success: function(response, newValue)
                {
                    response = $.parseJSON(response);
                    modal_alert(response.data, response.message);
                }
            });

            // $('#default_table td span[data-name="id_role"]').editable({
            //     validate: function(value)
            //     {
            //         if (value === null || value === '')
            //         {
            //             return 'Campo obligatorio';
            //         }
            //     },
            //     success: function(response, newValue)
            //     {
            //         response = $.parseJSON(response);
            //         modal_alert(response.data, response.message);
            //     },
            //     tpl: '<select></select>',
            //     select2: {
            //         theme: 'bootstrap4',
            //         width: '200px',
            //         language: 'es',
            //         ajax: {
            //             url: $path_roles,
            //             dataType: 'json',
            //             delay: 250,
            //             data: function(params)
            //             {
            //                 return {
            //                     q: params.term,
            //                     page: params.page || 1
            //                 };
            //             },
            //             processResults: function(data, params)
            //             {
            //                 var page = params.page || 1;
            //                 return {
            //                     results: data.items,
            //                     pagination: {
            //                         more: (page * 10) <= data.total_count
            //                     }
            //                 };
            //             },
            //             cache: true
            //         }
            //     }
            // });

            // $('#default_table td span[data-name="id_aspirant"]').editable({
            //     success: function(response, newValue)
            //     {
            //         response = $.parseJSON(response);
            //         modal_alert(response.data, response.message);
            //     },
            //     tpl: '<select></select>',
            //     defaultValue : 'sin aspirante',
            //     select2: {
            //         theme: 'bootstrap4',
            //         width: '200px',
            //         language: 'es',
            //         ajax: {
            //             url: $path_aspirants,
            //             dataType: 'json',
            //             delay: 250,
            //             data: function(params)
            //             {
            //                 return {
            //                     q: params.term,
            //                     page: params.page || 1
            //                 };
            //             },
            //             processResults: function(data, params)
            //             {
            //                 var page = params.page || 1;
            //                 return {
            //                     results: data.items,
            //                     pagination: {
            //                         more: (page * 10) <= data.total_count
            //                     }
            //                 };
            //             },
            //             cache: true
            //         }
            //     }
            // });
        }
    },
});

// generate tempalte for editable jquery plugin

// Show/hide add form
$('#btn_add').on('click', function ()
{
    $('#view_table').addClass('d-none');
    $('#view_form_add').removeClass('d-none');
    // $('#role').empty().trigger('change');
    // validate.resetForm();
    $('#form_add')[0].reset();
    // $('.flags').prop("checked", false);
});

$('#btn_cancel_add').on('click', function ()
{
    var defaultTable = $('#default_table').DataTable();
    defaultTable.ajax.reload();
    // validate.resetForm();
    $('#form_add')[0].reset();
    // $('#role').empty().trigger("change");
    // $('.flags').prop("checked", false);
    $('#view_form_add').addClass('d-none');
    $('#view_table').removeClass('d-none');
});

// iziToast - Alerts
function errorAlert(message) {
    iziToast.warning({
        message: message,
        position: "topRight",
    });
}

function successAlert(message) {
    iziToast.success({
        message: message,
        position: "topRight",
    });
}
});