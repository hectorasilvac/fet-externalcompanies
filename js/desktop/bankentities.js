// function appendTwoColumns({ firstCol, secondCol, elementRef }) {

//     $(elementRef).append(`
//     <tr>
//       <td>
//         ${firstCol}
//       </td>
//       <td>
//         ${secondCol}
//       </td>
//     </tr>`);
//   }
  
//   function locationSelect2({
//           selectRef,
//           dataName,
//           placeholder,
//           parentId = null,
//           parentName = null,
//           disabled = false,
//         }) {
//           return $(selectRef)
//             .select2({
//               disabled: disabled,
//               theme: "bootstrap4",
//               width: "100%",
//               language: "es",
//               placeholder: placeholder,
//               allowClear: true,
//               ajax: {
//                 url: $path_location,
//                 dataType: "json",
//                 delay: 250,
//                 data: function (params) {
//                   var id = $(`${selectRef} option:selected`).val();
        
//                   return {
//                     name: dataName,
//                     q: params.term,
//                     page: params.page || 1,
//                     id: id,
//                     parentId: parentId,
//                     parentName: parentName,
//                   };
//                 },
//                 processResults: function (data, params) {
//                   var page = params.page || 1;
//                   return {
//                     results: $.map(data.items, function (item) {
//                       return {
//                         id: item.id,
//                         text: item.text,
//                       };
//                     }),
//                     pagination: {
//                       more: page * 10 <= data.total_count,
//                     },
//                   };
//                 },
//               },
//               escapeMarkup: function (markup) {
//                 return markup;
//               },
//             })
//             .on("change", function (e) {
//               $(this).valid();
//             });
//         }
        
//         function retrieveLocationSelect2({
//           selectRef,
//           dataName,
//           placeholder,
//           pk,
//           value,
//           table,
//           parentId = null,
//           parentName = null,
//           optionText,
//           optionValue,
//           count = null,
//         }) {
//           locationSelect2({
//             selectRef: selectRef,
//             dataName: dataName,
//             placeholder: placeholder,
//             parentId: parentId,
//             parentName: parentName,
//           });
  
//           if (count === 1) {
//             $.ajax({
//               type: "POST",
//               dataType: "json",
//               url: $path_find,
//               data: {
//                 pk: pk,
//                 value: value,
//                 table: table,
//               },
//             }).then(function ({ data }) {
//               var selectInput = selectRef;
//               var option = new Option(
//                 data[optionText],
//                 data[optionValue],
//                 true,
//                 true
//               );
//               $(selectInput).append(option).trigger("change");
//             });
//           }
//         }
        
        // Method for validating letters of the Spanish alphabet
        jQuery.validator.addMethod(
          "lettersonly_es",
          function (value, element) {
            return this.optional(element) || /^[a-zA-Z\s\u00f1\u00d1]*$/.test(value);
          },
          "Este campo no puede contener números."
        );
  
        jQuery.validator.addMethod(
          'notEmpty',
          function (value, element) {
            return this.optional(element) || $.trim(value).length > 0;
          },
          'Este campo no puede estar vacío.'
        );

        jQuery.validator.addMethod(
          'isEmail',
          function (value, element) {
            return this.optional(element) || (/^\S+@\S+\.\S+\D$/).test(value);
          },
          'El formato del correo electrónico no es válido.'
        );
  
        
//         function validate_input({
//           value,
//           minLength = null,
//           maxLength = null,
//           isEmail = null,
//           isText = null,
//           isNumeric = null,
//           isRequired = true,
//         }) {
//           if ($.trim(value) == "" && isRequired === true) return "No puede estar vacío";
//           if (minLength !== null && value.length < minLength)
//             return `Debe contener mínimo ${minLength} caracteres`;
//           if (maxLength !== null && value.length > maxLength)
//             return `Debe contener máximo ${maxLength} caracteres`;
//           if (isText !== null && value.match(/[^a-zA-ZáéíóúñÑÁÉÍÓÚ ]/g))
//             return "Solo se permiten letras";
//           if (isEmail !== null && value !== '' && !value.match(/^\S+@\S+\.\S+\D$/))
//             return " ";
//           if (isNumeric !== null && value !== '' && !value.match(/^[0-9]+$/))
//             return "El campo debe contener solo números";
//         }
  
  
    /****************************************************************************************/
    /*********************************** DOCUMENT IS READY **********************************/
    /****************************************************************************************/
  $(document).ready(function () {
  //     $.fn.editable.defaults.mode = "inline";
  
//     locationSelect2({
//       selectRef: "#country_cv_ec",
//       dataName: "countries",
//       placeholder: "Selecciona un país",
//     }).on("change", function (e) {
//       var countryParent = $("#country_cv_ec option:selected").val();
  
//       if (typeof countryParent !== "undefined") {
//         locationSelect2({
//           selectRef: "#department_cv_ec",
//           dataName: "departments",
//           placeholder: "Selecciona un departamento",
//           parentId: countryParent,
//           parentName: "countries",
//         });
//       } else {
//         $("#department_cv_ec").prop("disabled", true);
//         $("#department_cv_ec").empty();
  
//         $("#city_cv_ec").prop("disabled", true);
//         $("#city_cv_ec").empty();
//       }
//     });
  
//     $("#department_cv_ec")
//       .select2({
//         placeholder: "Seleccione un departamento",
//         theme: "bootstrap4",
//         disabled: true,
//         width: "100%",
//       })
//       .on("change", function (e) {
//         var departmentParent = $("#department_cv_ec option:selected").val();
  
//         if (typeof departmentParent !== "undefined") {
//           locationSelect2({
//             selectRef: "#city_cv_ec",
//             dataName: "cities",
//             placeholder: "Selecciona una ciudad",
//             parentId: departmentParent,
//             parentName: "departments",
//           });
//         } else {
//           $("#city_cv_ec").prop("disabled", true);
//           $("#city_cv_ec").empty();
//         }
//       });
  
//     $("#city_cv_ec").select2({
//       placeholder: "Seleccione una ciudad",
//       theme: "bootstrap4",
//       disabled: true,
//       width: "100%",
//     });
  

    /****************************************************************************************/
    /******************************** IMPLEMENTING DATATABLE ********************************/
    /****************************************************************************************/
  
    var table = $("#default_table").DataTable({
      info: true,
      orderCellsTop: true,
      processing: true,
      serverSide: true,
      language: {
        sUrl: "/resources/lib/datatables/Spanish.json",
      },
      pagingType: "numbers",
      processing: true,
      serverSide: true,
      ajax: {
        url: $path_view,
        dataType: "json",
        type: "POST",
      },
      columnDefs: [
        {
          targets: [0],
          data: "number",
          render: function (data, type, row) {
            return `<span>${data}</span>`;
          },
        },
        {
          targets: [1],
          data: "name_bankentity",
          render: function (data, type, row) {
            return `<span>${data} (${row.code_bankentity})</span>`;
          },
        },
        {
          targets: [2],
          data: "nit_bankentity",
          render: function (data, type, row) {
            return `<span>${data}-${row.digit_bankentity}</span>`;
          },
        },
        {
          targets: [3],
          data: "contact_bankentity",
          render: function (data, type, row) {
            return `<span>${data}</span>`;
          },
        },
        {
          targets: [4],
          data: "phone_bankentity",
          render: function (data, type, row) {
            return `<span>${data}</span>`;
          },
        },
        {
          targets: [5],
          orderable: false,
          data: "id_cv_ec",
          render: function (data, type, row) {
            var content = '<div class="span-center">';
  
            if (act_edit) {
              content +=
                '<a data-toggle="tooltip" data-placement="top" title="Editar" href="javascript:void(0)" class="edit-row pd-x-5-force" data-id="' +
                data +
                '"><i class="fas fa-pencil-alt"></i></a>';
            }
  
            if (act_details) {
              content +=
                '<a data-toggle="tooltip" data-placement="top" title="Ver Detalle" href="javascript:void(0)" class="detail-row pd-x-5-force" data-id="' +
                data +
                '"><i class="fas fa-asterisk"></i></a>';
            }
  
            if (act_assign) {
  
              content +=
                '<a data-toggle="tooltip" data-placement="top" title="Ver Aspirantes Pertenecientes (' + row.aspirants + ')" href="javascript:void(0)" class="assign-row pd-x-5-force" data-id="' +
                data +
                '"><i class="fas fa-exchange-alt"></i></a>';
            }
  
            if (act_drop) {
              content +=
                '<a data-toggle="tooltip" data-placement="top" title="Eliminar" href="javascript:void(0)" class="remove-row pd-x-5-force" data-id="' +
                data +
                '"><i class="fas fa-trash"></i></a>';
            }
  
            if (act_trace) {
              content +=
                '<a data-toggle="tooltip" data-placement="top" title="Trazabilidad" href="javascript:void(0)" class="trace-row pd-x-5-force" data-id="' +
                data + '" onclick="trace(\'' + $path_trace +"', 'id_cv_ec'," + data + ')"><i class="fas fa-history"></i></a>';
            }
  
            return content + "</div>";
          },
        },
      ],
      drawCallback: function (settings) {
        var rows = this.fnGetData();
        var inputSearch = $('.dataTables_filter input').val();
  
        $('[data-toggle="tooltip"]').tooltip();
  
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
  
        if (act_edit) {
          $("#default_table td span[data-type]").editable({
            emptytext: 'Vacío',
            inputclass: 'py-2 pl-2 pr-3 mw-50',
            validate: function (value) {
              return validate_input({
                value: value,
                minLength: $(this).attr("data-minlength") || null,
                maxLength: $(this).attr("data-maxlength") || null,
                isEmail: $(this).attr("data-type") == "email" || null,
                isNumeric: $(this).attr("data-type") == "number" || null,
                isText: $(this).attr("data-type") == "text" || null,
                isRequired: $(this).attr("data-required") == "false" ? false : true,
              });
            },
            success: function (response) {
              var { data, message } = $.parseJSON(response);
              var successful_update = data === true;
  
              if (successful_update) {
                modal_alert(data, message);
                return true;
              }
  
              modal_alert(data, message);
              return false;
            },
          });
  
          $(".type_cv_ec").editable({
            type: "select",
            source: [
              { value: "PRIVADA", text: "PRIVADA" },
              { value: "PUBLICA", text: "PUBLICA" },
            ],
            validate: function (value) {
              return validate_input({
                value: value,
                isText: true,
              });
            },
            success: function (response, newValue) {
              response = $.parseJSON(response);
              modal_alert(response.data, response.message);
            },
          });
        }
  
        $('span.editable').css('border-bottom', 'none');
      },
    });
  
//     /****************************************************************************************/
//     /***************************************** EDIT *****************************************/
//     /****************************************************************************************/
  
//     $("#default_table").on("click", "a.edit-row", function () {
  
//       $('#view_table').addClass('d-none');
//       $('#view_form_edit').removeClass('d-none');
//       validate_edit.resetForm();
//       $('#form_edit')[0].reset();
  
//       var companyId = $(this).attr("data-id");
  
//       var getCompanyById = $.ajax({
//         url: $path_find,
//         type: "POST",
//         dataType: "json",
//         data: {
//           pk: "id_cv_ec",
//           value: companyId,
//           table: "fet_cv_ec",
//         },
//         beforeSend: function () {
//           $("#loading").removeClass("d-none");
//         },
//         success: function ({ data }) {
//           $('#form_edit input[name="pk"]').attr("value", data.id_cv_ec);
//           $('#form_edit input[name="address_cv_ec"]').attr(
//             "value",
//             data.address_cv_ec
//           );
  
//           retrieveLocationSelect2({
//             selectRef: '#form_edit select[name="country_cv_ec"]',
//             dataName: "countries",
//             placeholder: "Selecciona un país",
//             pk: "id_country",
//             value: data.country_cv_ec,
//             table: "git_countries",
//             optionText: "name_country",
//             optionValue: "id_country",
//             count: 1,
//           });
  
//           var countryCount = 1;
//           $('#form_edit select[name="country_cv_ec"]').on("change", function (e) {
//             var country = $(
//               '#form_edit select[name="country_cv_ec"] option:selected'
//             ).val();
  
//             if (typeof country !== "undefined") {
//               retrieveLocationSelect2({
//                 selectRef: '#form_edit select[name="department_cv_ec"]',
//                 dataName: "departments",
//                 placeholder: "Selecciona un departamento",
//                 parentId: country,
//                 parentName: "countries",
//                 pk: "id_department",
//                 value: data.department_cv_ec,
//                 table: "git_departments",
//                 optionText: "name_department",
//                 optionValue: "id_department",
//                 count: countryCount,
//               });
//             } else {
//               $('#form_edit select[name="department_cv_ec"]').prop(
//                 "disabled",
//                 true
//               );
//               $('#form_edit select[name="department_cv_ec"]').empty();
  
//               $('#form_edit select[name="city_cv_ec"]').prop("disabled", true);
//               $('#form_edit select[name="city_cv_ec"]').empty();
//             }
  
//             countryCount++;
//           });
  
//           var departmentCount = 1;
//           $('#form_edit select[name="department_cv_ec"]').on(
//             "change",
//             function (e) {
//               var department = $(
//                 '#form_edit select[name="department_cv_ec"] option:selected'
//               ).val();
  
//               if (typeof department !== "undefined") {
//                 retrieveLocationSelect2({
//                   selectRef: '#form_edit select[name="city_cv_ec"]',
//                   dataName: "cities",
//                   placeholder: "Selecciona una ciudad",
//                   parentId: department,
//                   parentName: "departments",
//                   pk: "id_city",
//                   value: data.city_cv_ec,
//                   table: "git_cities",
//                   optionText: "name_city",
//                   optionValue: "id_city",
//                   count: departmentCount,
//                 });
//               } else {
//                 $('#form_edit select[name="city_cv_ec"]').prop("disabled", true);
//                 $('#form_edit select[name="city_cv_ec"]').empty();
//               }
//               departmentCount++;
//               $("#loading").addClass("d-none");
//             }
//           );
//        }
//       });
//     });
  
  
//    var validate_edit = $("#form_edit").validate({
//       onkeyup: false,
//       ignore: [],
//       rules: {
//         normalizer: function (value) {
//           return $.trim(value);
//         },
//         onkeyup: false,
//         focusCleanup: true,
//         address_cv_ec: {
//           minlength: 5,
//           maxlength: 80,
//         },
//         country_cv_ec: {
//           required: true,
//         },
//         department_cv_ec: {
//           required: true,
//         },
//         city_cv_ec: {
//           required: true,
//         },
//       },
//       messages: {
//         address_cv_ec: {
//           minlength: "La dirección debe contener al menos 5 caracteres",
//           maxlength: "La dirección debe contener máximo 60 caracteres",
//         },
//         country_cv_ec: {
//           required: "Seleccione el país",
//         },
//         department_cv_ec: {
//           required: "Seleccione el departamento",
//         },
//         city_cv_ec: {
//           required: "Seleccione la ciudad",
//         },
//       },
//       errorElement: "small",
//       errorPlacement: function (error, element) {
//         $(error).addClass("invalid-feedback font-weight-normal");
  
//         $(error).insertAfter(element);
//       },
//     });
  
  
//     $('#btn_confirm_edit').on('click', function ()
//     {
//         $('#form_edit').submit();
//     });
  
//     $('#form_edit').ajaxForm({
//         dataType:  'json',
//         success:  function(response) {
//           modal_alert(response.data, response.message);
  
//           if (response.data) $('#view_form_edit').addClass('d-none');      
//         },
//         beforeSubmit: function()
//         {
//             $('#loading').removeClass('d-none');
//         }
//     });
  
//     $("#btn_cancel_edit").on("click", function () {
//       var defaultTable = $("#default_table").DataTable();
//       defaultTable.ajax.reload();
//       validate_edit.resetForm();
//       $("#form_edit")[0].reset();
//       $("#view_form_edit").addClass("d-none");
//       $("#view_table").removeClass("d-none");
  
//     });
  
//     /****************************************************************************************/
//     /**************************************** DETAILS ***************************************/
//     /****************************************************************************************/
//     $("#default_table").on("click", "a.detail-row", function () {
//       var companyId = $(this).attr("data-id");
  
//       var getCompanyById = $.ajax({
//         url: $path_details,
//         type: "POST",
//         dataType: "json",
//         data: {
//           pk: "id_cv_ec",
//           value: companyId,
//         },
//         beforeSend: function () {
//           $("#loading").removeClass("d-none");
//         },
//         success: function ({ data, message }) {
//           $("#view_table").toggleClass("d-none");
//           $("#view_details").removeClass("d-none");
  
//           $('#view_details td[data-name="name_cv_ec"]').text(data.name_cv_ec);
//           $('#view_details td[data-name="nit_cv_ec"]').text(data.nit_cv_ec);
//           $('#view_details td[data-name="type_cv_ec"]').text(data.type_cv_ec);
//           $('#view_details td[data-name="phone_cv_ec"]').text(data.phone_cv_ec);
//           $('#view_details td[data-name="email_cv_ec"]').text(data.email_cv_ec);
//           $('#view_details td[data-name="address_cv_ec"]').text(data.address_cv_ec);
//           $('#view_details td[data-name="country_cv_ec"]').text(data.name_country);
//           $('#view_details td[data-name="department_cv_ec"]').text(data.name_department);
//           $('#view_details td[data-name="city_cv_ec"]').text(data.name_city);
  
//           $("#loading").toggleClass("d-none");
//         }
        
//       });
//     });
  
//     $("#btn_cancel_details").on("click", function () {
//       $("#view_details").addClass("d-none");
//       $("#view_table").toggleClass("d-none");
//     });
  
//     /****************************************************************************************/
//     /***************************************** ASSIGN ***************************************/
//     /****************************************************************************************/
//     $("#default_table").on("click", "a.assign-row", function () {
  
//       $("#view_assign").toggleClass("d-none");
//       $("#view_table").toggleClass("d-none");
  
//       var companyId = $(this).attr("data-id");
  
//       var getAspirants = $.ajax({
//         url: $path_find,
//         type: "POST",
//         dataType: "json",
//         data: {
//           pk: "id_cv_ec",
//           value: companyId,
//           get_aspirants: true,
//         },
//         beforeSend: function () {
//           $("#loading").toggleClass("d-none");
//         },
//         success: function ({ data, message })
//         {
//           $("#loading").toggleClass("d-none");
//           $('tbody#assign_content').empty();
//           $('thead#assign_head').removeClass('d-none');
  
//           if (data)
//           {
//             if (data.length > 1) 
//             {
//               $.each(data, function(index, item) {
//                 appendTwoColumns({
//                   firstCol: item.full_name,
//                   secondCol: item.number_dcv,
//                   elementRef: 'tbody#assign_content'
//                 });
//               });
//             }
//             else
//             {
//               appendTwoColumns({
//                 firstCol: data.full_name,
//                 secondCol: data.number_dcv,
//                 elementRef: 'tbody#assign_content'
//               });
//             }
//           }
//           else
//           {
//             $('thead#assign_head').addClass('d-none');
//             $('tbody#assign_content').append(`
//             <tr>
//               <td colspan="2">No hay aspirantes asignados a esta empresa.</td>
//             </tr>
//             `);
//           }
//         },
//       });
//     });
  
//       $("#btn_cancel_assign").on("click", function () {
//         $("#view_assign").toggleClass("d-none");
//         $("#view_table").toggleClass("d-none");
//       });
  
//     /****************************************************************************************/
//     /***************************************** ADD ******************************************/
//     /****************************************************************************************/
  
    $("#btn_add").on("click", function () {
      $("#view_table").addClass("d-none");
      $("#view_form_add").removeClass("d-none");
      validateForm.resetForm();
      $("#form_add")[0].reset();
    });
  
  
    $("#btn_cancel_add").on("click", function () {
      var defaultTable = $("#default_table").DataTable();
      defaultTable.ajax.reload();
      validateForm.resetForm();
      $(".select2-hidden-accessible").empty();
      $("#form_add")[0].reset();
      $("#view_form_add").addClass("d-none");
      $("#view_table").removeClass("d-none");
    });

        var validateFormAdd = $("#form_add").validate({
      onkeyup: false,
      ignore: [],
      rules: {
        normalizer: function (value) {
          return $.trim(value);
        },
        onkeyup: false,
        focusCleanup: true,
        name_bankentity: {
          required: true,
          minlength: 3,
          maxlength: 50,
          lettersonly_es: true,
          notEmpty: true,
        },
        nit_bankentity: {
          required: true,
          digits: true,
          minlength: 3,
          maxlength: 9,
          notEmpty: true,
        },
        digit_bankentity: {
          required: true,
          digits: true,
          minlength: 1,
          maxlength: 2,
          notEmpty: true,
        },
        code_bankentity: {
          required: true,
          digits: true,
          minlength: 1,
          maxlength: 4,
          notEmpty: true,
        },
        address_bankentity: {
          required: true,
          minlength: 5,
          maxlength: 70,
          notEmpty: true,
        },
        contact_bankentity: {
          required: true,
          lettersonly_es: true,
          minlength: 3,
          maxlength: 50,
          notEmpty: true,
        },
        phone_bankentity: {
          required: true,
          digits: true,
          minlength: 7,
          maxlength: 13,
          notEmpty: true,
        },
        email_bankentity: {
          required: true,
          isEmail: true,
          minlength: 5,
          maxlength: 50,
        },
      },
      messages: {
        name_bankentity: {
          required: "Ingrese el nombre del banco",
          minlength: "Debe tener al menos 3 caracteres",
          maxlength: "Debe tener máximo 50 caracteres",
        },
        nit_bankentity: {
          required: "Ingresa el NIT del banco",
          digits: "Debe contener sólo números",
          minlength: "Debe tener al menos 3 caracteres",
          maxlength: "Debe tener máximo 9 caracteres",
        },
        digit_bankentity: {
          required: "Ingresa el dígito de verificación",
          digits: "Debe contener sólo números",
          minlength: "Debe tener al menos 1 carácter",
          maxlength: "Debe tener máximo 2 caracteres",

        },
        code_bankentity: {
          required: "Ingresa el código del banco",
          digits: "Debe contener sólo números",
          minlength: "Debe tener al menos 1 carácter",
          maxlength: "Debe tener máximo 4 caracteres",

        },
        address_bankentity: {
          required: "Ingresa la dirección",
          minlength: "Debe contener al menos 5 caracteres",
          maxlength: "Debe contener máximo 70 caracteres",
        },
        contact_bankentity: {
          required: "Ingresa el contacto",
          minlength: "Debe contener al menos 3 caracteres",
          maxlength: "Debe contener máximo 50 caracteres",
        },
        phone_bankentity: {
          required: "Ingresa el número del contacto",
          digits: "Debe contener sólo números",
          minlength: "Debe tener al menos 7 caracteres",
          maxlength: "Debe tener máximo 13 caracteres",

        },
        email_bankentity: {
          required: "Ingresa el correo corporativo",
          minlength: "Debe tener al menos 3 caracteres",
          maxlength: "Debe tener máximo 50 caracteres",
        },
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
        dataType: 'json',
        url: $path_add,
        type: 'post',
        beforeSubmit: function()
        {
            // $('#loading').removeClass('d-none');
        },
        success: function ({ data, message }) {
    
            $('#loading').addClass('d-none');
            $("#error-list").empty();
  
          if (data === false && typeof message == "object") {
            Object.entries(message).forEach(([key, value]) => {
  
              // var errorExists = $(`#${key}-error`).length;
  
              // if (errorExists) {
              //   $(`#${key}-error`).text(value);
              // } else {
                var errorElement = `<small id="${key}-error" class="error invalid-feedback font-weight-normal">${value}</small>`;
  
                $(`#${key}`).addClass("error");
                $(errorElement).insertAfter(`#${key}`);
              // }
            });
            return false;
          }
  
          if (data === false && typeof message == "string") {
            modal_alert(data, message);
          
            return false;
          }
  
          modal_alert(data, message);
  
          $(".select2-hidden-accessible").empty();
          $("#form_add")[0].reset();
          $('#view_form_add').addClass('d-none');
          table.ajax.reload();
        },
      });
    }
  
    $('#form_add').on('change', '.form-control', function() {
  
      if ( $(this).valid() )
      {
        var id = $(this).attr('id');
        var errorExists = $(`#${id}-error`).length;
  
        if ( errorExists ) {
          $(`#${id}-error`).remove();
          return;
        }
        return;
      }
      
    });
  
//     /****************************************************************************************/
//     /***************************************** DROP *****************************************/
//     /****************************************************************************************/
  
//     $('#default_table').on('click', 'a.remove-row', function()
//     {
//       var companyId = $(this).attr("data-id");
  
//         $('#modal_delete').iziModal({
//             title: 'Eliminar empresa',
//             icon: 'fas fa-trash-alt',
//             headerColor: '#DC3545',
//             zindex: 9999,
//             onClosed: function()
//             {
//                 $('#btn_confirm_delete').off('click');
//                 $('#modal_delete').iziModal('destroy');
//             },
//             onOpening: function()
//             {
//                 $('#btn_confirm_delete').on('click', function()
//                 {
//                     $('#loading').removeClass('d-none');
  
//                     $.ajax({
//                         type: 'POST',
//                         url: $path_drop,
//                         data: {
//                             id_cv_ec: companyId,
//                         },
//                         dataType: 'json',
//                         success: function (response)
//                         {
//                             var defaultTable = $('#default_table').DataTable();
//                             defaultTable.ajax.reload();
//                             modal_alert(response.data, response.message);
//                             $('#loading').addClass('d-none');
//                         },
//                         error: function () {
//                             modal_alert(false, 'Error de conexión.');
//                         }
//                     });
  
//                     $('#modal_delete').iziModal('close');
//                 });
  
//                 $('#btn_cancel_delete').on('click', function()
//                 {
//                     $('#modal_delete').iziModal('close');
//                 });
//             }
//         });
  
//         $('#modal_delete').iziModal('open');
//     });
  });