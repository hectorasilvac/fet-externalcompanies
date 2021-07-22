function formValidation(formId, path) {
  return $(formId).validate({
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
      customSubmit(form, formId, path);
      return false;
    },
  });
}

function customSubmit(form, formId, path) {
  $(form).ajaxSubmit({
    dataType: "json",
    url: path,
    type: "post",
    beforeSubmit: function () {
      $("#loading").removeClass("d-none");
    },
    success: function ({ data, message }) {
      $("#loading").addClass("d-none");
      $("#error-list").empty();

      if (data === false && typeof message == "object") {
        Object.entries(message).forEach(([key, value]) => {
          modal_alert(data, value);

          var errorElement = `<small id="${key}-error" class="error invalid-feedback font-weight-normal">${value}</small>`;

          $(`${formId} input[name=${key}]`).addClass("error");
          $(errorElement).insertAfter(`${formId} input[name=${key}]`);
        });
        return false;
      }

      if (data === false && typeof message == "string") {
        modal_alert(data, message);
        return false;
      }

      modal_alert(data, message);

      $(formId)[0].reset();

      var formName = $(formId).closest("form")[0].id;

      $(`#view_${formName}`).addClass("d-none");
      table.ajax.reload();
    },
  });
}

function removeFormErrors(formId, event = "change") {
  $(formId).on(event, ".form-control", function () {
    if ($(this).valid()) {
      var name = $(this).attr("name");
      var errorExists = $(`#${name}-error`).length;

      if (errorExists) {
        $(`#${name}-error`).remove();
        return;
      }
      return;
    }
  });
}
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
  "notEmpty",
  function (value, element) {
    return this.optional(element) || $.trim(value).length > 0;
  },
  "Este campo no puede estar vacío."
);

jQuery.validator.addMethod(
  "isEmail",
  function (value, element) {
    return this.optional(element) || /^\S+@\S+\.\S+\D$/.test(value);
  },
  "El formato del correo electrónico no es válido."
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
        data: "id_bankentity",
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
              '<a data-toggle="tooltip" data-placement="top" title="Ver Aspirantes Pertenecientes (' +
              row.aspirants +
              ')" href="javascript:void(0)" class="assign-row pd-x-5-force" data-id="' +
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
              data +
              '" onclick="trace(\'' +
              $path_trace +
              "', 'id_cv_ec'," +
              data +
              ')"><i class="fas fa-history"></i></a>';
          }

          return content + "</div>";
        },
      },
    ],
    drawCallback: function (settings) {
      var rows = this.fnGetData();
      var inputSearch = $(".dataTables_filter input").val();

      $('[data-toggle="tooltip"]').tooltip();

      if (rows.length == 0) {
        $("#btn_export_xlsx").removeAttr("href");
      } else {
        if (inputSearch != "") {
          $("#btn_export_xlsx").attr(
            "href",
            $path_export_xlsx + "/?search=" + inputSearch
          );
        } else {
          $("#btn_export_xlsx").attr("href", $path_export_xlsx);
        }
      }

      if (act_edit) {
        $("#default_table td span[data-type]").editable({
          emptytext: "Vacío",
          inputclass: "py-2 pl-2 pr-3 mw-50",
          validate: function (value) {
            return validate_input({
              value: value,
              minLength: $(this).attr("data-minlength") || null,
              maxLength: $(this).attr("data-maxlength") || null,
              isEmail: $(this).attr("data-type") == "email" || null,
              isNumeric: $(this).attr("data-type") == "number" || null,
              isText: $(this).attr("data-type") == "text" || null,
              isRequired:
                $(this).attr("data-required") == "false" ? false : true,
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

      $("span.editable").css("border-bottom", "none");
    },
  });

  /****************************************************************************************/
  /**************************************** DETAILS ***************************************/
  /****************************************************************************************/
  $("#default_table").on("click", "a.detail-row", function () {
    var bankById = $(this).attr("data-id");

    $.ajax({
      url: $path_details,
      type: "POST",
      dataType: "json",
      data: {
        pk: "id_bankentity",
        value: bankById,
      },
      beforeSend: function () {
        $("#loading").removeClass("d-none");
      },
      success: function ({ data, message }) {
        $("#view_table").toggleClass("d-none");
        $("#view_details").removeClass("d-none");

        $.each(data, function (index, value) {
          $(`#view_details td[data-name="${index}"]`).text(value);
        });

        $("#loading").toggleClass("d-none");
      },
    });
  });

  $("#btn_cancel_details").on("click", function () {
    $("#view_details").addClass("d-none");
    $("#view_table").toggleClass("d-none");
  });

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

  /****************************************************************************************/
  /***************************************** ADD ******************************************/
  /****************************************************************************************/
  var validationAddForm = formValidation("#form_add", $path_add);
  removeFormErrors("#form_add", "keypress");

  $("#btn_add").on("click", function () {
    $("#view_table").addClass("d-none");
    $("#view_form_add").removeClass("d-none");
    validationAddForm.resetForm();
    $("#form_add")[0].reset();
  });

  $("#btn_confirm_edit").on("click", function () {
    $("#form_add").submit();
  });

  $("#btn_cancel_add").on("click", function () {
    var defaultTable = $("#default_table").DataTable();
    defaultTable.ajax.reload();
    validationAddForm.resetForm();
    $(".select2-hidden-accessible").empty();
    $("#form_add")[0].reset();
    $("#view_form_add").addClass("d-none");
    $("#view_table").removeClass("d-none");
  });

  /****************************************************************************************/
  /***************************************** EDIT *****************************************/
  /****************************************************************************************/

  var validationeEditForm = formValidation("#form_edit", $path_edit);
  removeFormErrors("#form_edit", "keypress");

  $("#default_table").on("click", "a.edit-row", function () {
    $("#view_table").addClass("d-none");
    $("#view_form_edit").removeClass("d-none");
    validationeEditForm.resetForm();
    $("#form_edit")[0].reset();

    var bankId = $(this).attr("data-id");

    // Display information retrieved from the database
    $.ajax({
      url: $path_find,
      type: "POST",
      dataType: "json",
      data: {
        pk: "id_bankentity",
        value: bankId,
        table: "fet_bankentities",
      },
      beforeSend: function () {
        $("#loading").removeClass("d-none");
      },
      success: function ({ data }) {
        $('#form_edit input[name="pk"]').attr("value", data.id_bankentity);

        $.each(data, function (index, value) {
          $(`#form_edit input[name="${index}"]`).attr("value", value);
        });

        $("#loading").addClass("d-none");
      },
    });
  });

  $("#btn_confirm_edit").on("click", function () {
    $("#form_edit").submit();
  });

  $("#btn_cancel_edit").on("click", function () {
    var defaultTable = $("#default_table").DataTable();
    defaultTable.ajax.reload();
    validationeEditForm.resetForm();
    $("#form_edit")[0].reset();
    $("#view_form_edit").addClass("d-none");
    $("#view_table").removeClass("d-none");
  });

  /****************************************************************************************/
  /***************************************** DROP *****************************************/
  /****************************************************************************************/

  $("#default_table").on("click", "a.remove-row", function () {
    var bankId = $(this).attr("data-id");

    $("#modal_delete").iziModal({
      title: "Eliminar entidad bancaria",
      icon: "fas fa-trash-alt",
      headerColor: "#DC3545",
      zindex: 9999,
      onClosed: function () {
        $("#btn_confirm_delete").off("click");
        $("#modal_delete").iziModal("destroy");
      },
      onOpening: function () {
        $("#btn_confirm_delete").on("click", function () {
          $("#loading").removeClass("d-none");

          $.ajax({
            type: "POST",
            url: $path_drop,
            data: {
              id_bankentity: bankId,
            },
            dataType: "json",
            success: function (response) {
              var defaultTable = $("#default_table").DataTable();
              defaultTable.ajax.reload();
              modal_alert(response.data, response.message);
              $("#loading").addClass("d-none");
            },
            error: function () {
              modal_alert(false, "Error de conexión.");
            },
          });

          $("#modal_delete").iziModal("close");
        });

        $("#btn_cancel_delete").on("click", function () {
          $("#modal_delete").iziModal("close");
        });
      },
    });

    $("#modal_delete").iziModal("open");
  });
});
