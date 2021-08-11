$(function ($) {
  // BUILD - DESKTOP
  $.fn.editable.defaults.mode = "inline";

  $("#default_table").DataTable({
    language: {
      sUrl: "resources/lib/datatables/Spanish.json",
    },
    info: true,
    lengthChange: true,
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
        data: "name_cv_ec",
        render: function (data, type, row) {
          return `<span data-name='name_cv_ec' data-type='text' data-pk='${row.id_cv_ec}' data-url='${$path_edit}'>${data}</span>`;
        },
      },
      {
        targets: [1],
        data: "nit_cv_ec",
        render: function (data, type, row) {
          return `<span data-name='nit_cv_ec' data-type='number' data-pk='${row.id_cv_ec}' data-url='${$path_edit}'>${data}</span>`;
        },
      },
      {
        targets: [2],
        data: "type_cv_ec",
        render: function (data, type, row) {
          return `<span data-name='type_cv_ec' data-type='select2' data-value='${data}' data-pk='${row.id_cv_ec}' data-url='${$path_edit}'>${data}</span>`;
        },
      },
      {
        targets: [3],
        data: "email_cv_ec",
        render: function (data, type, row) {
          return `<span data-name='email_cv_ec' data-type='text' data-pk='${row.id_cv_ec}' data-url='${$path_edit}'>${data}</span>`;
        },
      },
      {
        targets: [4],
        data: "phone_cv_ec",
        render: function (data, type, row) {
          return `<span data-name='phone_cv_ec' data-type='number' data-pk='${row.id_cv_ec}' data-url='${$path_edit}'>${data}</span>`;
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

          if (act_detail) {
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
        visible: act_edit || act_detail || act_assign || act_drop || act_trace ? true : false,
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
          $("#default_table td span[data-type='text']").editable({
            emptytext: "Vacío",
            validate: function (value) {
              switch ($(this).attr("data-name")) {

                case "name_cv_ec":
                  if (value.length < 3)
                    return "Mínimo se permiten 3 caracteres.";
                  if (value.length > 150)
                    return "Solo se permiten 150 caracteres.";
                  if (!value.match(/^[a-zA-Z\d\s\u00f1\u00d1\.\u00E0-\u00FC]*$/))
                    return "Solo se permiten letras y números.";
                  if (value === null || value.trim() === "")
                    return "Campo obligatorio.";
                  break;

                case "email_cv_ec":
                  if (!value === null || value.trim() !== "") {
                    if (value.length < 3)
                      return "Mínimo se permiten 3 caracteres.";
                    if (value.length > 150)
                      return "Solo se permiten 150 caracteres.";
                    if (!value.match(/^\S+@\S+\.\S+\D$/))
                      return "Ingresa una cuenta válida de correo.";
                  }
                  break;
              }
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

          $("#default_table td span[data-type='number']").editable({
            emptytext: "Vacío",
            validate: function (value) {
              switch ($(this).attr("data-name")) {
                
                case "nit_cv_ec":
                    if (value.length > 12)
                      return "Solo se permiten 12 caracteres.";
                    if (value.length < 10)
                      return "Mínimo se permiten 10 caracteres.";
                    if (!value.match(/^[0-9]+$/))
                      return "Solo se permiten números.";
                    if (value === null || value.trim() === "")
                      return "Campo obligatorio.";
                  break;

                case "phone_cv_ec":
                  if (!value === null || value.trim() !== "") {
                    if (value.length < 7)
                      return "Mínimo permiten 7 caracteres.";
                    if (value.length > 20)
                      return "Solo se permiten 20 caracteres.";
                    if (!value.match(/^[0-9]+$/))
                      return "Solo se permiten números.";
                  }
                  break;
              }
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

          $("#default_table td span[data-type='select2']").editable({
            type: "select",
            source: [
              { value: "Privada", text: "Privada" },
              { value: "Pública", text: "Pública" },
            ],
            validate: function (value) {
              if (value === null || value.trim() === "")
                return "Campo obligatorio.";
              if (value.match(/[^a-zA-ZáéíóúñÑÁÉÍÓÚ ]/g))
                return "Solo se permiten letras.";
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
        }
    },
  });

  jQuery.validator.addMethod(
    "lettersonly_es",
    function (value, element) {
      return (
        this.optional(element) ||
        /^[a-zA-Z\s\u00f1\u00d1\.\u00E0-\u00FC]*$/.test(value)
      );
    },
    "Solo se permiten letras."
  );

  jQuery.validator.addMethod(
    "letters_numbers",
    function (value, element) {
      return (
        this.optional(element) ||
        /^[a-zA-Z\d\s\u00f1\u00d1\.\u00E0-\u00FC]*$/.test(value)
      );
    },
    "Solo se permiten letras y números."
  );

  jQuery.validator.addMethod(
    "email",
    function (value, element) {
      return (
        this.optional(element) ||
        /^\S+@\S+\.\S+\D$/.test(value)
      );
    },
    "Ingresa una cuenta válida de correo."
  );

  var validate = $("#form_add").validate({
    rules: {
      name_cv_ec: {
        required: true,
        minlength: 3,
        maxlength: 150,
        letters_numbers: true,
      },
      nit_cv_ec: {
        digits: true,
        minlength: 10,
        maxlength: 12,
        required: true,
      },
      type_cv_ec: {
        maxlength: 15,
        required: true,
        lettersonly_es: true,
      },
      email_cv_ec: {
        email: true,
        minlength: 3,
        maxlength: 150,
      },
      phone_cv_ec: {
        digits: true,
        minlength: 7,
        maxlength: 20,
      },
      address_cv_ec: {
        minlength: 5,
        maxlength: 150,
      },
      country_cv_ec: {
        required: true,
      },
      department_cv_ec: {
        required: true,
      },
      city_cv_ec: {
        required: true,
      },
    },
    messages: {
      name_cv_ec: {
        required: "Por favor ingresa el nombre.",
        minlength: "Mínimo se permiten 3 caracteres.",
        maxlength: "Solo se permiten 150 caracteres.",
      },
      nit_cv_ec: {
        required: "Por favor ingresa el NIT.",
        minlength: "Solo se permiten 10 caracteres.",
        maxlength: "Solo se permiten 12 caracteres.",
        digits: "Solo se permiten números.",
      },
      type_cv_ec: {
        required: "Seleccione el tipo de empresa.",
        maxlength: "Solo se permiten 15 caracteres.",
        lettersonly_es: "Solo se permiten letras.",
      },
      email_cv_ec: {
        email: "Por favor ingresa un correo electrónico válido.",
        minlength: "Mínimo se permiten 3 caracteres.",
        maxlength: "Solo se permiten 150 caracteres.",
      },
      phone_cv_ec: {
        digits: "Solo se permiten números.",
        minlength: "Mínimo se permiten 7 caracteres.",
        maxlength: "Solo se permiten 20 caracteres.",
      },
      address_cv_ec: {
        minlength: "Mínimo se permiten 5 caracteres.",
        maxlength: "Solo se permiten 150 caracteres.",
      },
      country_cv_ec: {
        required: "Por favor selecciona un país.",
      },
      department_cv_ec: {
        required: "Por favor selecciona un departamento.",
      },
      city_cv_ec: {
        required: "Por favor selecciona una ciudad.",
      },
    },
    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");

      var selects = ["country_cv_ec", "department_cv_ec", "city_cv_ec"];

      if (selects.includes(element.prop("name"))) {
        error.insertAfter(element.next(".select2-container"));
      } else {
        error.insertAfter(element);
      }
    },
  });

  var validate_edit = $("#form_edit").validate({
    rules: {
      address_cv_ec: {
        minlength: 5,
        maxlength: 150,
      },
      country_cv_ec: {
        required: true,
      },
      department_cv_ec: {
        required: true,
      },
      city_cv_ec: {
        required: true,
      },
    },
    messages: {
      address_cv_ec: {
        minlength: "Mínimo se permiten 5 caracteres.",
        maxlength: "Solo se permiten 150 caracteres.",
      },
      country_cv_ec: {
        required: "Por favor selecciona un país.",
      },
      department_cv_ec: {
        required: "Por favor selecciona un departamento.",
      },
      city_cv_ec: {
        required: "Por favor selecciona una ciudad.",
      },
    },
    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");

      var selects = ["country_cv_ec", "department_cv_ec", "city_cv_ec"];

      if (selects.includes(element.prop("name"))) {
        error.insertAfter(element.next(".select2-container"));
      } else {
        error.insertAfter(element);
      }
    },
  });

  $("select[name='country_cv_ec']")
    .select2({
      theme: "bootstrap4",
      width: "100%",
      language: "es",
      placeholder: "Selecciona el país",
      allowClear: true,
      ajax: {
        url: $path_countries,
        dataType: "json",
        delay: 250,
        data: function (params) {
          return {
            q: params.term,
            page: params.page || 1,
          };
        },
        processResults: function (data, params) {
          var page = params.page || 1;
          return {
            results: $.map(data.items, function (item) {
              return {
                id: item.id,
                text: item.text,
              };
            }),
            pagination: {
              more: page * 10 <= data.total_count,
            },
          };
        },
      },
      escapeMarkup: function (markup) {
        return markup;
      },
    })
    .on("change", function (e) {
      $(this).valid();
      $("select[name='department_cv_ec']").val("");
      $("select[name='department_cv_ec']").val(null).trigger("change");
      $("select[name='city_cv_ec']").val("");
      $("select[name='city_cv_ec']").val(null).trigger("change");
    });

  $("select[name='department_cv_ec']")
    .select2({
      theme: "bootstrap4",
      width: "100%",
      language: "es",
      placeholder: "Selecciona el departamento",
      allowClear: true,
      ajax: {
        url: $path_departments,
        dataType: "json",
        delay: 250,
        data: function (params) {
          var country = $("select[name='country_cv_ec'] option:selected").val();

          return {
            q: params.term,
            page: params.page || 1,
            country: country,
          };
        },
        processResults: function (data, params) {
          if (data.data == false) {
            modal_alert(data.data, data.message);
          }

          var page = params.page || 1;
          return {
            results: $.map(data.items, function (item) {
              return {
                id: item.id,
                text: item.text,
              };
            }),
            pagination: {
              more: page * 10 <= data.total_count,
            },
          };
        },
      },
      escapeMarkup: function (markup) {
        return markup;
      },
    })
    .on("change", function (e) {
      $(this).valid();
      $("select[name='city_cv_ec']").val("");
      $("select[name='city_cv_ec']").val(null).trigger("change");
    });

  $("select[name='city_cv_ec']")
    .select2({
      theme: "bootstrap4",
      width: "100%",
      language: "es",
      placeholder: "Selecciona la ciudad",
      allowClear: true,
      ajax: {
        url: $path_cities,
        dataType: "json",
        delay: 250,
        data: function (params) {
          var department = $(
            "select[name='department_cv_ec'] option:selected"
          ).val();

          return {
            q: params.term,
            page: params.page || 1,
            department: department,
          };
        },
        processResults: function (data, params) {
          if (data.data == false) {
            modal_alert(data.data, data.message);
          }

          var page = params.page || 1;
          return {
            results: $.map(data.items, function (item) {
              return {
                id: item.id,
                text: item.text,
              };
            }),
            pagination: {
              more: page * 10 <= data.total_count,
            },
          };
        },
      },
      escapeMarkup: function (markup) {
        return markup;
      },
    })
    .on("change", function (e) {
      $(this).valid();
    });

  // Add

  $("#btn_add").on("click", function () {
    $("#view_table").addClass("d-none");
    $("#view_form_add").removeClass("d-none");
    $("#country_cv_ec").empty().trigger("change");
    validate.resetForm();
    $("#form_add")[0].reset();
  });

  $("#btn_confirm_add").on("click", function () {
    $("#form_add").submit();
  });

  $("#form_add").ajaxForm({
    dataType: "json",
    success: add,
    beforeSubmit: function () {
      $("#loading").removeClass("d-none");
    },
  });

  $("#btn_cancel_add").on("click", function () {
    var defaultTable = $("#default_table").DataTable();
    defaultTable.ajax.reload();
    validate.resetForm();
    $("#form_add")[0].reset();
    $("#country_cv_ec").empty().trigger("change");
    $("#view_form_add").addClass("d-none");
    $("#view_table").removeClass("d-none");
  });

  // Edit

  $("#default_table").on("click", "a.edit-row", function () {
    $("#view_table").addClass("d-none");
    $("#view_form_edit").removeClass("d-none");
    validate_edit.resetForm();
    $("#form_edit")[0].reset();

    var companyId = $(this).attr("data-id");

    $.ajax({
      url: $path_detail,
      type: "POST",
      dataType: "json",
      data: {
        pk: "id_cv_ec",
        value: companyId,
      },
      beforeSend: function () {
        $("#loading").removeClass("d-none");
      },
      success: function ({ data }) {
        $('#form_edit input[name="pk"]').attr("value", data.id_cv_ec);
        $('#form_edit input[name="address_cv_ec"]').attr(
          "value",
          data.address_cv_ec
        );

        $('#form_edit select[name="country_cv_ec"]').val(data.country_cv_ec);
        var country_cv_ec = new Option(
          data.text_country_cv_ec,
          data.country_cv_ec,
          true,
          true
        );
        $('#form_edit select[name="country_cv_ec"]')
          .append(country_cv_ec)
          .trigger("change");

        $('#form_edit select[name="department_cv_ec"]').val(
          data.department_cv_ec
        );
        var department_cv_ec = new Option(
          data.text_department_cv_ec,
          data.department_cv_ec,
          true,
          true
        );
        $('#form_edit select[name="department_cv_ec"]')
          .append(department_cv_ec)
          .trigger("change");

        $('#form_edit select[name="city_cv_ec"]').val(data.city_cv_ec);
        var city_cv_ec = new Option(
          data.text_city_cv_ec,
          data.city_cv_ec,
          true,
          true
        );
        $('#form_edit select[name="city_cv_ec"]')
          .append(city_cv_ec)
          .trigger("change");

        $("#loading").toggleClass("d-none");
      },
    });
  });

  $("#btn_confirm_edit").on("click", function () {
    $("#form_edit").submit();
  });

  $("#form_edit").ajaxForm({
    dataType: "json",
    success: edit,
    beforeSubmit: function () {
      $("#loading").removeClass("d-none");
    },
  });

  $("#btn_cancel_edit").on("click", function () {
    var defaultTable = $("#default_table").DataTable();
    defaultTable.ajax.reload();
    validate_edit.resetForm();
    $("#form_edit")[0].reset();
    $("#view_form_edit").addClass("d-none");
    $("#view_table").removeClass("d-none");
    $("select[name='country_cv_ec']").val("");
    $("select[name='country_cv_ec']").val(null).trigger("change");
    $("select[name='department_cv_ec']").val("");
    $("select[name='department_cv_ec']").val(null).trigger("change");
    $("select[name='city_cv_ec']").val("");
    $("select[name='city_cv_ec']").val(null).trigger("change");
  });

  // Detail

  $("#default_table").on("click", "a.detail-row", function () {
    var companyById = $(this).attr("data-id");

    $.ajax({
      url: $path_detail,
      type: "POST",
      dataType: "json",
      data: {
        pk: "id_cv_ec",
        value: companyById,
      },
      beforeSend: function () {
        $("#loading").removeClass("d-none");
      },
      success: function ({ data, message }) {
        $("#view_table").toggleClass("d-none");
        $("#view_detail").removeClass("d-none");

        $.each(data, function (index, value) {
          $(`#view_detail td[data-name="${index}"]`).text(value);
        });

        $("#loading").toggleClass("d-none");
      },
    });
  });

  $("#btn_cancel_detail").on("click", function () {
    $("#view_detail").addClass("d-none");
    $("#view_table").toggleClass("d-none");
  });

  // Assign

  $("#default_table").on("click", "a.assign-row", function () {
    $("#view_assign").toggleClass("d-none");
    $("#view_table").toggleClass("d-none");

    var companyId = $(this).attr("data-id");

    $.ajax({
      url: $path_affiliated_workers,
      type: "POST",
      dataType: "json",
      data: {
        pk: "id_cv_ec",
        value: companyId,
      },
      beforeSend: function () {
        $("#loading").toggleClass("d-none");
      },
      success: function ({ data, message }) {
        $("#loading").toggleClass("d-none");
        $("tbody#assign_content").empty();
        $("thead#assign_head").removeClass("d-none");

        if (data) {
          $.each(data, function (index, user) {
            $("tbody#assign_content").append(`
            <tr>
              <td>${user.full_name}</td>
              <td>${user.number_dcv}</td>
            </tr>`);
          });
        } else {
          $("thead#assign_head").addClass("d-none");
          $("tbody#assign_content").append(`
                <tr>
                  <td colspan="2">No hay trabajadores afiliados a esta empresa.</td>
                </tr>
                `);
        }
      },
    });
  });

  $("#btn_cancel_assign").on("click", function () {
    $("#view_assign").toggleClass("d-none");
    $("#view_table").toggleClass("d-none");
  });

  // Udrop

  $("#default_table").on("click", "a.remove-row", function () {
    var companyId = $(this).attr("data-id");

    $("#modal_delete").iziModal({
      title: "Eliminar empresa externa",
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
              id_cv_ec: companyId,
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

function add(response) {
  modal_alert(response.data, response.message);

  if (response.data) {
    $("#view_form_add").addClass("d-none");
  }
}

function edit(response) {
  modal_alert(response.data, response.message);

  if (response.data) {
    $("#view_form_edit").addClass("d-none");
  }
}
