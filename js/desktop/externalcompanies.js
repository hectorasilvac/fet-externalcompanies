function location_select2({ selectRef, dataName, placeholder, parentId = null, parentName = null }) {
    return $(selectRef).select2({
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

// Code to execute when the DOM is fully loaded 
$(document).ready(function () {

  location_select2({
    selectRef: "#country_cv_ec",
    dataName: "countries",
    placeholder: "Selecciona un país"
  }).on('change', function (e)
  {
    var countryParent = $("#country_cv_ec option:selected").val();
        console.log(countryParent);
    
        location_select2({
          selectRef: "#department_cv_ec",
          dataName: "departments",
          placeholder: "Selecciona un departamento",
          parentId: countryParent,
          parentName: "countries"
        });  
  });

  $('#department_cv_ec').select2({
      placeholder: 'Seleccione un departamento',
      theme: 'bootstrap4',
  });


//   $("#country_cv_ec").on("change", function () {
//     var countryParent = $("#country_cv_ec option:selected").val();
//     console.log(countryParent);

//     location_select2({
//       selectRef: "#department_cv_ec",
//       dataName: "departments",
//       placeholder: "Selecciona un departamento",
//       parent: countryParent,
//     });
//   });

//   $("#department_cv_ec").on("change", function () {
//     var departmentParent = $("#department_cv_ec option:selected").attr("data-select2-id");

//     location_select2({
//       selectRef: "#city_cv_ec",
//       dataName: "cities",
//       placeholder: "Selecciona una ciudad",
//       parent: departmentParent,
//     });
//   });
});