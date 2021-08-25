$(function ($) {
  //BUILD - DESKTOP
  $.fn.editable.defaults.mode = "inline";

  var height = ($(window).height() - 380);

  $("#default_table").DataTable({
    language: {
      sUrl: "resources/lib/datatables/Spanish.json",
    },
    info: true,
    lengthChange: true,
    serverSide: true,
    scrollY: height,
    scroller:
    {
        loadingIndicator: true
    },
    ajax: function(data, callback, settings) 
    {
      $.ajax({
        url: $path_view,
        dataType: "json",
        type: "POST",
        data: data,
        success: function(data)
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
    columnDefs: [
      {
        targets: [0],
        data: "id_worker",
        createdCell:  function(td, cellData, rowData, row, col)
        {
           $(td).attr('data-label', 'Nombre');
        },
        render: function (data, type, row) {
          return `
            <span data-placement="top" data-title="${row.name_area}" class="mr-1"><i class="fas fa-university"></i></span>
            <span data-type='select2' data-name='id_worker' class="border-bottom-0" data-pk='${row.id_extension}' data-url='${$path_edit}' data-value='${data}'>${data}</span>
            `;
        },
      },
      {
        targets: [1],
        data: "email_extension",
        createdCell:  function(td, cellData, rowData, row, col)
        {
           $(td).attr('data-label', 'Correo');
        },
        render: function (data, type, row) {
          return `<span data-type='text' data-name="email_extension" class="border-bottom-0" data-pk="${row.id_extension}" data-url="${$path_edit}">${data}</span>`;
        },
      },
      {
        targets: [2],
        data: "internal_extension",
        createdCell:  function(td, cellData, rowData, row, col)
        {
           $(td).attr('data-label', 'Ext. Interna');
        },
        render: function (data, type, row) {
          return `
          <span data-name="internal_extension" class="border-bottom-0" data-type='number' data-pk="${row.id_extension}" data-url="${$path_edit}" data-title="${row.ip_extension ?? "No hay IP registrada"}">
            ${data}
          </span>
          `;
        },
      },
      {
        targets: [3],
        data: "external_extension",
        createdCell:  function(td, cellData, rowData, row, col)
        {
           $(td).attr('data-label', 'Ext. Externa');
        },
        render: function (data, type, row) {
          return `
          <span data-name="external_extension" class="border-bottom-0" data-type='number' data-pk="${row.id_extension}" data-url="${$path_edit}" data-title="${row.ip_extension ?? "No hay IP registrada"}">
            ${data}
          </span>`;
        },
      },
      {
        targets: [4],
        orderable: false,
        data: "id_extension",
        render: function (data, type, row) {
          var content = '<div class="span-center">';

          if (act_edit) {
            content +=
              '<a data-toggle="tooltip" data-placement="top" data-title="Editar" href="javascript:void(0)" class="edit-row pd-x-5-force" data-id="' +
              data +
              '"><i class="fas fa-pencil-alt"></i></a>';
          }

          if (act_drop) {
            content +=
              '<a data-toggle="tooltip" data-placement="top" data-title="Eliminar" href="javascript:void(0)" class="remove-row pd-x-5-force" data-id="' +
              data +
              '"><i class="fas fa-trash"></i></a>';
          }

          if (act_trace) {
            content +=
              '<a data-toggle="tooltip" data-placement="top" data-title="Trazabilidad" href="javascript:void(0)" class="trace-row pd-x-5-force" data-id="' +
              data +
              '" onclick="trace(\'' +
              $path_trace +
              "', 'id_extension'," +
              data +
              ')"><i class="fas fa-history"></i></a>';
          }

          return content + "</div>";
        },
        visible: act_edit || act_drop || act_trace ? true : false,
      },
    ],
    drawCallback: function (settings) {
      var rows = this.fnGetData();
      var inputSearch = $(".dataTables_filter input").val();

      if (rows.length == 0) {
        $("#btn_export_pdf").removeAttr("href");
        $("#btn_export_xlsx").removeAttr("href");
      } else {
        if (inputSearch != "") {
          $("#btn_export_pdf").attr(
            "href",
            $path_export_pdf + "/?search=" + inputSearch + "&multiplepdf=0"
          );
          $("#btn_export_xlsx").attr(
            "href",
            $path_export_xlsx + "/?search=" + inputSearch
          );
        } else {
          $("#btn_export_pdf").attr("href", $path_export_pdf);
          $("#btn_export_xlsx").attr("href", $path_export_xlsx);
        }
      }

      if (act_edit) {
        $('#default_table td span[data-type="select2"]').editable({
          validate: function (value) {
            if (value === null || value === "") {
              return "Campo obligatorio";
            }
          },
          success: function (response, newValue) {
            response = $.parseJSON(response);
            modal_alert(response.data, response.message);
          },
          tpl: "<select></select>",
          select2: {
            theme: "bootstrap4",
            width: "200px",
            language: "es",
            ajax: {
              url: $path_workers,
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
                  results: data.items,
                  pagination: {
                    more: page * 10 <= data.total_count,
                  },
                };
              },
              cache: true,
            },
          },
        });

        $("#default_table td span[data-type='text']").editable({
          emptytext: "Vacío",
          validate: function (value) {
            switch ($(this).attr("data-name")) {
              case "email_extension":
                if (value.length < 3) return "Mínimo se permiten 3 caracteres.";
                if (value.length > 80) return "Solo se permiten 80 caracteres.";
                if (!value.match(/^\S+@\S+\.\S+\D$/))
                  return "Ingresa una cuenta válida de correo.";
                if (value === null || value.trim() === "")
                  return "Campo obligatorio.";
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
              case "internal_extension":
                if (!value === null || value.trim() !== "") {
                  if (value.length < 1) return "Mínimo permiten 1 carácter.";
                  if (value.length > 3) return "Solo se permiten 3 caracteres.";
                  if (!value.match(/^[0-9]+$/))
                    return "Solo se permiten números.";
                }
                break;

              case "external_extension":
                if (!value === null || value.trim() !== "") {
                  if (value.length < 1) return "Mínimo permiten 1 carácter.";
                  if (value.length > 3) return "Solo se permiten 3 caracteres.";
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
      }
    },
  });

  jQuery.validator.addMethod(
    "lettersonly_es",
    function (value, element) {
      return (
        this.optional(element) ||
        /^[a-zA-Z\s\u00f1\u00d1\.\u00E0-\u00FC\u00C0-\u00DC]*$/.test(value)
      );
    },
    "Solo se permiten letras."
  );

  jQuery.validator.addMethod(
    "email",
    function (value, element) {
      return this.optional(element) || /^\S+@\S+\.\S+\D$/.test(value);
    },
    "Ingresa una cuenta válida de correo."
  );

  jQuery.validator.addMethod(
    "ip",
    function (value, element) {
      return (
        this.optional(element) ||
        /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(
          value
        )
      );
    },
    "Ingresa una IP válida."
  );

  var validate = $("#form_add").validate({
    rules: {
      id_worker: {
        required: true,
        digits: true,
        minlength: 1,
        maxlength: 11,
      },
      id_area: {
        required: true,
        digits: true,
        minlength: 1,
        maxlength: 11,
      },
      id_element1: {
        digits: true,
        maxlength: 11,
      },
      id_element2: {
        digits: true,
        maxlength: 11,
      },
      email_extension: {
        required: true,
        email: true,
        minlength: 3,
        maxlength: 80,
      },
      phone_extension: {
        digits: true,
        minlength: 7,
        maxlength: 12,
      },
      internal_extension: {
        digits: true,
        minlength: 1,
        maxlength: 3,
      },
      external_extension: {
        digits: true,
        minlength: 1,
        maxlength: 3,
      },
      ip_extension: {
        ip: true,
        maxlength: 15,
      },
      git_company: {
        maxlength: 1,
      },
    },
    messages: {
      id_worker: {
        required: "Por favor selecciona el trabajador",
      },
      id_area: {
        required: "Por favor selecciona el área.",
      },
      email_extension: {
        required: "Por favor ingresa el correo electrónico.",
        minlength: "Mínimo se permiten 3 caracteres",
        maxlength: "Solo se permiten 80 caracteres",
      },
      phone_extension: {
        digits: "Solo se permiten números.",
        minlength: "Mínimo se permiten 7 caracteres.",
        maxlength: "Solo se permiten 12 caracteres.",
      },
      internal_extension: {
        digits: "Solo se permiten números.",
        minlength: "Mínimo se permiten 1 carácter.",
        maxlength: "Solo se permiten 3 caracteres.",
      },
      external_extension: {
        digits: "Solo se permiten números.",
        minlength: "Mínimo se permiten 1 carácter.",
        maxlength: "Solo se permiten 3 caracteres.",
      },
      ip_extension: {
        maxlength: "Solo se permiten 15 caracteres.",
      },
    },
    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");

      var selects = ["id_worker", "id_area", "id_element1", "id_element2"];

      if (selects.includes(element.prop("name"))) {
        error.insertAfter(element.next(".select2-container"));
      } else {
        error.insertAfter(element);
      }
    },
  });

  var validate_edit = $("#form_edit").validate({
    rules: {
      id_area: {
        required: true,
        digits: true,
        minlength: 1,
        maxlength: 11,
      },
      id_element1: {
        digits: true,
        maxlength: 11,
      },
      id_element2: {
        digits: true,
        maxlength: 11,
      },
      phone_extension: {
        digits: true,
        minlength: 7,
        maxlength: 12,
      },
      ip_extension: {
        ip: true,
        maxlength: 15,
      },
      git_company: {
        maxlength: 1,
      },
    },
    messages: {
      id_area: {
        required: "Por favor selecciona el área.",
      },
      phone_extension: {
        digits: "Solo se permiten números.",
        minlength: "Mínimo se permiten 7 caracteres.",
        maxlength: "Solo se permiten 12 caracteres.",
      },
      ip_extension: {
        maxlength: "Solo se permiten 15 caracteres.",
      },
    },
    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");

      var selects = ["id_worker", "id_area", "id_element1", "id_element2"];

      if (selects.includes(element.prop("name"))) {
        error.insertAfter(element.next(".select2-container"));
      } else {
        error.insertAfter(element);
      }
    },
  });

  $("select[name='id_worker']")
    .select2({
      theme: "bootstrap4",
      width: "100%",
      language: "es",
      placeholder: "Selecciona el trabajador",
      allowClear: true,
      ajax: {
        url: $path_workers,
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
    });

  $("select[name='id_area']")
    .select2({
      theme: "bootstrap4",
      width: "100%",
      language: "es",
      placeholder: "Selecciona el área",
      allowClear: true,
      ajax: {
        url: $path_areas,
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
    });

  $("select[name='id_element1']")
    .select2({
      theme: "bootstrap4",
      width: "100%",
      language: "es",
      placeholder: "Selecciona el teléfono asignado",
      allowClear: true,
      ajax: {
        url: $path_telephones,
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
    });

  $("select[name='id_element2']")
    .select2({
      theme: "bootstrap4",
      width: "100%",
      language: "es",
      placeholder: "Selecciona el celular asignado",
      allowClear: true,
      ajax: {
        url: $path_cellphones,
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
    });

  // Add

  $("#btn_add").on("click", function () {
    $("#view_table").addClass("d-none");
    $("#view_form_add").removeClass("d-none");
    $("#id_worker").empty().trigger("change");
    $("#id_area").empty().trigger("change");
    $("#id_element1").empty().trigger("change");
    $("#id_element2").empty().trigger("change");
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
    $("#id_worker").empty().trigger("change");
    $("#id_worker").val(null).trigger("change");
    $("#id_area").empty().trigger("change");
    $("#id_area").val(null).trigger("change");
    $("#id_element1").empty().trigger("change");
    $("#id_element1").val(null).trigger("change");
    $("#id_element2").empty().trigger("change");
    $("#id_element2").val(null).trigger("change");
  });

  // Edit

  $("#default_table").on("click", "a.edit-row", function () {
    $("#view_table").addClass("d-none");
    $("#view_form_edit").removeClass("d-none");
    validate_edit.resetForm();
    $("#form_edit")[0].reset();
    $("#id_area_edit").empty().trigger("change");
    $("#id_area_edit").val(null).trigger("change");
    $("#id_element1_edit").empty().trigger("change");
    $("#id_element1_edit").val(null).trigger("change");
    $("#id_element2_edit").empty().trigger("change");
    $("#id_element2_edit").val(null).trigger("change");

    var extensionId = $(this).attr("data-id");

    $.ajax({
      url: $path_detail,
      type: "POST",
      dataType: "json",
      data: {
        pk: "id_extension",
        value: extensionId,
      },
      beforeSend: function () {
        $("#loading").removeClass("d-none");
      },
      success: function ({ data }) {
        $('#form_edit input[name="pk"]').attr("value", data.id_extension);

        $('#form_edit input[name="phone_extension"]').attr(
          "value",
          data.phone_extension
        );

        $('#form_edit input[name="ip_extension"]').attr(
          "value",
          data.ip_extension
        );

        $('#form_edit select[name="id_area"]').val(data.id_area);
        var id_area = new Option(data.name_area, data.id_area, true, true);
        $('#form_edit select[name="id_area"]')
          .append(id_area)
          .trigger("change");

        if (data.id_element1) {
          $('#form_edit select[name="id_element1"]').val(data.id_element1);
          var id_element1 = new Option(
            data.name_element1,
            data.id_element1,
            true,
            true
          );
          $('#form_edit select[name="id_element1"]')
            .append(id_element1)
            .trigger("change");
        }

        if (data.id_element2) {
          $('#form_edit select[name="id_element2"]').val(data.id_element2);
          var id_element2 = new Option(
            data.name_element2,
            data.id_element2,
            true,
            true
          );
          $('#form_edit select[name="id_element2"]')
            .append(id_element2)
            .trigger("change");
        }

        if (data.git_company === "A") {
          $("#git_company_edit").prop("checked", true);
        }
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
    $("#id_area_edit").empty().trigger("change");
    $("#id_area_edit").val(null).trigger("change");
    $("#id_element1_edit").empty().trigger("change");
    $("#id_element1_edit").val(null).trigger("change");
    $("#id_element2_edit").empty().trigger("change");
    $("#id_element2_edit").val(null).trigger("change");
  });

  // Udrop

  $("#default_table").on("click", "a.remove-row", function () {
    var extensionId = $(this).attr("data-id");

    $("#modal_delete").iziModal({
      title: "Eliminar extensión",
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
              id_extension: extensionId,
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
