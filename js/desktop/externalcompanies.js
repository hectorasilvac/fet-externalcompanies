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

// Code to execute when the DOM is fully loaded 
$(document).ready(function () {

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
            maxlength: 60,
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
        url: window.location.href + "/add",
        type: "post",
        resetForm: true,
        clearForm: true,
        success: function ({ data, message, error }) {
            if (error && data.length >= 1) {
                $("#form-errors").removeClass("d-none");
                $("#form-errors").addClass("alert alert-danger");
                data.forEach((item) => {
                    $(item).appendTo("#form-errors");
                });
            } else if (error) {
                $("#form-errors").addClass("d-none");
                iziToast.error({
                    title: "Error",
                    message: message,
                    position: "topRight",
                });
            } else {
                $("#form-errors").addClass("d-none");
                iziToast.success({
                    message: message,
                    position: "topRight",
                });
            }
            table.ajax.reload();
        },
    });
}

});