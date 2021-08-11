$(function ($) {
  //BUILD - DESKTOP
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
        data: "name_bankentity",
        createdCell:  function(td, cellData, rowData, row, col)
        {
           $(td).attr('data-label', 'Nombre');
        },
        render: function (data, type, row) {
          return ` <span>${data} (${row.code_bankentity})</span>`;
        },
      },
      {
        targets: [1],
        data: "nit_bankentity",
        createdCell:  function(td, cellData, rowData, row, col)
        {
           $(td).attr('data-label', 'NIT');
        },
        render: function (data, type, row) {
          return `<span>${data}-${row.digit_bankentity}</span>`;
        },
      },
      {
        targets: [2],
        data: "contact_bankentity",
        createdCell:  function(td, cellData, rowData, row, col)
        {
           $(td).attr('data-label', 'Contacto');
        },
        render: function (data, type, row) {
          return `<span>${data}</span>`;
        },
      },
      {
        targets: [3],
        data: "phone_bankentity",
        createdCell:  function(td, cellData, rowData, row, col)
        {
           $(td).attr('data-label', 'Teléfono');
        },
        render: function (data, type, row) {
          return `<span>${data}</span>`;
        },
      },
      {
        targets: [4],
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

          if (act_detail) {
            content +=
              '<a data-toggle="tooltip" data-placement="top" title="Ver Detalle" href="javascript:void(0)" class="detail-row pd-x-5-force" data-id="' +
              data +
              '"><i class="fas fa-asterisk"></i></a>';
          }

          if (act_assign) {
            content +=
              '<a data-toggle="tooltip" data-placement="top" title="Ver Trabajadores Afiliados (' +
              row.workers +
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
              "', 'id_bankentity'," +
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
      name_bankentity: {
        required: true,
        minlength: 3,
        maxlength: 50,
        lettersonly_es: true,
      },
      abbreviation_bankentity: {
        required: true,
        minlength: 2,
        maxlength: 15,
        lettersonly_es: true,
      },
      nit_bankentity: {
        required: true,
        digits: true,
        minlength: 9,
        maxlength: 9,
      },
      digit_bankentity: {
        required: true,
        digits: true,
        minlength: 1,
        maxlength: 1,
      },
      code_bankentity: {
        required: true,
        digits: true,
        minlength: 1,
        maxlength: 4,
      },
      address_bankentity: {
        required: true,
        minlength: 5,
        maxlength: 70,
      },
      contact_bankentity: {
        required: true,
        lettersonly_es: true,
        minlength: 3,
        maxlength: 50,
      },
      phone_bankentity: {
        required: true,
        digits: true,
        minlength: 7,
        maxlength: 13,
      },
      email_bankentity: {
        required: true,
        email: true,
        minlength: 5,
        maxlength: 50,
      },
    },
    messages: {
      name_bankentity: {
        required: "Por favor ingresa el nombre.",
        minlength: "Mínimo se permiten 3 caracteres.",
        maxlength: "Solo se permiten 50 caracteres.",
      },
      abbreviation_bankentity: {
        required: "Por favor ingresa la abreviatura.",
        minlength: "Mínimo se permiten 2 caracteres.",
        maxlength: "Solo se permiten 15 caracteres.",
      },
      nit_bankentity: {
        required: "Por favor ingresa el NIT.",
        digits: "Solo se permiten números.",
        minlength: "Solo se permiten 9 caracteres.",
        maxlength: "Solo se permiten 9 caracteres.",
      },
      digit_bankentity: {
        required: "Por favor ingresa el díigito de verificación.",
        digits: "Solo se permiten números.",
        minlength: "Solo se permite 1 carácter",
        maxlength: "Solo se permite 1 carácter",
      },
      code_bankentity: {
        required: "Por favor ingresa el código.",
        digits: "Solo se permiten números.",
        minlength: "Mínimo se permite 1 carácter.",
        maxlength: "Solo se permiten 4 caracteres.",
      },
      address_bankentity: {
        required: "Por favor ingresa la dirección.",
        minlength: "Mínimo se permiten 5 caracteres.",
        maxlength: "Solo se permiten 70 caracteres.",
      },
      contact_bankentity: {
        required: "Por favor ingresa el contacto.",
        minlength: "Mínimo se permiten 3 caracteres.",
        maxlength: "Solo se permiten 50 caracteres.",
      },
      phone_bankentity: {
        required: "Por favor ingresa el número del contacto.",
        digits: "Solo se permiten números.",
        minlength: "Mínimo se permiten 7 caracteres.",
        maxlength: "Solo se permiten 13 caracteres.",
      },
      email_bankentity: {
        required: "Por favor ingresa el correo electrónico.",
        minlength: "Mínimo se permiten 3 caracteres",
        maxlength: "Solo se permiten 50 caracteres",
      },
    },
    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      error.insertAfter(element);
    },
  });

  var validate_edit = $("#form_edit").validate({
    rules: {
      name_bankentity: {
        required: true,
        minlength: 3,
        maxlength: 50,
        lettersonly_es: true,
      },
      abbreviation_bankentity: {
        required: true,
        minlength: 2,
        maxlength: 15,
        lettersonly_es: true,
      },
      nit_bankentity: {
        required: true,
        digits: true,
        minlength: 9,
        maxlength: 9,
      },
      digit_bankentity: {
        required: true,
        digits: true,
        minlength: 1,
        maxlength: 1,
      },
      code_bankentity: {
        required: true,
        digits: true,
        minlength: 1,
        maxlength: 4,
      },
      address_bankentity: {
        required: true,
        minlength: 5,
        maxlength: 70,
      },
      contact_bankentity: {
        required: true,
        lettersonly_es: true,
        minlength: 3,
        maxlength: 50,
      },
      phone_bankentity: {
        required: true,
        digits: true,
        minlength: 7,
        maxlength: 13,
      },
      email_bankentity: {
        required: true,
        email: true,
        minlength: 5,
        maxlength: 50,
      },
    },
    messages: {
      name_bankentity: {
        required: "Por favor ingresa el nombre.",
        minlength: "Mínimo se permiten 3 caracteres.",
        maxlength: "Solo se permiten 50 caracteres.",
      },
      abbreviation_bankentity: {
        required: "Por favor ingresa la abreviatura.",
        minlength: "Mínimo se permiten 2 caracteres.",
        maxlength: "Solo se permiten 15 caracteres.",
      },
      nit_bankentity: {
        required: "Por favor ingresa el NIT.",
        digits: "Solo se permiten números.",
        minlength: "Solo se permiten 9 caracteres.",
        maxlength: "Solo se permiten 9 caracteres.",
      },
      digit_bankentity: {
        required: "Por favor ingresa el díigito de verificación.",
        digits: "Solo se permiten números.",
        minlength: "Solo se permite 1 carácter",
        maxlength: "Solo se permite 1 carácter",
      },
      code_bankentity: {
        required: "Por favor ingresa el código.",
        digits: "Solo se permiten números.",
        minlength: "Mínimo se permite 1 carácter.",
        maxlength: "Solo se permiten 4 caracteres.",
      },
      address_bankentity: {
        required: "Por favor ingresa la dirección.",
        minlength: "Mínimo se permiten 5 caracteres.",
        maxlength: "Solo se permiten 70 caracteres.",
      },
      contact_bankentity: {
        required: "Por favor ingresa el contacto.",
        minlength: "Mínimo se permiten 3 caracteres.",
        maxlength: "Solo se permiten 50 caracteres.",
      },
      phone_bankentity: {
        required: "Por favor ingresa el número del contacto.",
        digits: "Solo se permiten números.",
        minlength: "Mínimo se permiten 7 caracteres.",
        maxlength: "Solo se permiten 13 caracteres.",
      },
      email_bankentity: {
        required: "Por favor ingresa el correo electrónico.",
        minlength: "Mínimo se permiten 3 caracteres",
        maxlength: "Solo se permiten 50 caracteres",
      },
    },
    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      error.insertAfter(element);
    },
  });

  // Add

  $("#btn_add").on("click", function () {
    $("#view_table").addClass("d-none");
    $("#view_form_add").removeClass("d-none");
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
    $("#view_form_add").addClass("d-none");
    $("#view_table").removeClass("d-none");
  });

  // Edit

  $("#default_table").on("click", "a.edit-row", function () {
    $("#view_table").addClass("d-none");
    $("#view_form_edit").removeClass("d-none");
    validate_edit.resetForm();
    $("#form_edit")[0].reset();

    var bankId = $(this).attr("data-id");

    $.ajax({
      url: $path_detail,
      type: "POST",
      dataType: "json",
      data: {
        pk: "id_bankentity",
        value: bankId,
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
  });

  // Detail

  $("#default_table").on("click", "a.detail-row", function () {
    var bankById = $(this).attr("data-id");

    $.ajax({
      url: $path_detail,
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

    var bankId = $(this).attr("data-id");

    $.ajax({
      url: $path_affiliated_workers,
      type: "POST",
      dataType: "json",
      data: {
        pk: "id_bankentity",
        value: bankId,
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
                  <td colspan="2">No hay trabajadores afiliados a esta entidad bancaria.</td>
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
