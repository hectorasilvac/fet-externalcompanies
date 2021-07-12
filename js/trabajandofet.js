'use strict';
$(document).ready(function()
{
    $('.show-sub + .br-menu-sub').slideDown();

    $('#btnLeftMenu').on('click', function()
    {
        var menuText = $('.menu-item-label,.menu-item-arrow');

        if($('body').hasClass('collapsed-menu')) 
        {
            $('body').removeClass('collapsed-menu');
            $('.show-sub + .br-menu-sub').slideDown();
            $('.br-sideleft').one('transitionend', function(e) 
            {
                menuText.removeClass('op-lg-0-force');
                menuText.removeClass('d-lg-none');
            });
        }
        else 
        {
            $('body').addClass('collapsed-menu');
            $('.show-sub + .br-menu-sub').slideUp();
            menuText.addClass('op-lg-0-force');
            $('.br-sideleft').one('transitionend', function(e) 
            {
                menuText.addClass('d-lg-none');
            });
        }

        return false;
    });

    $(document).on('mouseover', function(e)
    {
        e.stopPropagation();

        if($('body').hasClass('collapsed-menu') && $('#btnLeftMenu').is(':visible')) 
        {
            var targ = $(e.target).closest('.br-sideleft').length;

            if(targ) 
            {
                $('body').addClass('expand-menu');
                $('.show-sub + .br-menu-sub').slideDown();
                var menuText = $('.menu-item-label,.menu-item-arrow');
                menuText.removeClass('d-lg-none');
                menuText.removeClass('op-lg-0-force');
            } 
            else 
            {
                $('body').removeClass('expand-menu');
                $('.show-sub + .br-menu-sub').slideUp();
                var menuText = $('.menu-item-label,.menu-item-arrow');
                menuText.addClass('op-lg-0-force');
                menuText.addClass('d-lg-none');
            }
        }
    });

    $('.br-menu-link').on('click', function()
    {
        var nextElem = $(this).next();
        var thisLink = $(this);

        if(nextElem.hasClass('br-menu-sub')) 
        {

            if(nextElem.is(':visible')) 
            {
                thisLink.removeClass('show-sub');
                nextElem.slideUp();
            } 
            else 
            {
                $('.br-menu-link').each(function()
                {
                    $(this).removeClass('show-sub');
                });
                $('.br-menu-sub').each(function()
                {
                    $(this).slideUp();
                });
                thisLink.addClass('show-sub');
                nextElem.slideDown();
            }

            return false;
        }
    });

    $('#btnLeftMenuMobile').on('click', function()
    {
        $('body').addClass('show-left');
        return false;
    });

    $('#btnRightMenu').on('click', function()
    {
        $('body').addClass('show-right');
        return false;
    });

    $(document).on('click', function(e)
    {
        e.stopPropagation();

        if($('body').hasClass('show-left')) 
        {
            var targ = $(e.target).closest('.br-sideleft').length;

            if(!targ) 
            {
                $('body').removeClass('show-left');
            }
        }

        if($('body').hasClass('show-right')) 
        {
            var targ = $(e.target).closest('.br-sideright').length;

            if(!targ) 
            {
                $('body').removeClass('show-right');
            }
        }
    });

    $(function()
    {
        'use strict';
        $(window).resize(function()
        {
            minimizeMenu();
        });

        minimizeMenu();

        function minimizeMenu() 
        {
            if(window.matchMedia('(min-width: 992px)').matches && window.matchMedia('(max-width: 1440px)').matches) 
            {
                $('.menu-item-label,.menu-item-arrow').addClass('op-lg-0-force d-lg-none');
                $('body').addClass('collapsed-menu');
                $('.show-sub + .br-menu-sub').slideUp();
            } 
            else if(window.matchMedia('(min-width: 1300px)').matches && !$('body').hasClass('collapsed-menu')) 
            {
                $('.menu-item-label,.menu-item-arrow').removeClass('op-lg-0-force d-lg-none');
                $('body').removeClass('collapsed-menu');
                $('.show-sub + .br-menu-sub').slideDown();
            }
        }
    });

    $('.overflow-y-auto').mCustomScrollbar({
        theme:"minimal-blue",
        axis:"y"
    });

    $('.carousel-x').mCustomScrollbar({
        theme:"minimal",
        axis:"x",
        advanced:{
            autoExpandHorizontalScroll:true
        }
    });

    $('.initialform-y').mCustomScrollbar({
        theme:"minimal-dark",
        axis:"y"
    });

    var interval = setInterval(function() 
    {
        var momentNow = moment();
        momentNow.locale('es');

        $('#brDate').html(momentNow.format('MMMM DD, YYYY') + ' '
            + momentNow.format('dddd')
            .substring(0,3).toUpperCase());

        $('#brTime').html(momentNow.format('hh:mm:ss A'));

    }, 100);

    $.datepicker.regional['es'] = 
    {
        closeText: 'Cerrar',
        prevText: '<< Anterior',
        nextText: 'Siguiente >>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };

    $.datepicker.setDefaults($.datepicker.regional['es']);

    if($().datepicker) 
    {
        $('.form-control-datepicker').datepicker().on("change", function (e) 
        {
            console.log("Date changed: ", e.target.value);
        });
    }

    $('.datepicker').datepicker();

    $('.switch-button').switchButton();

    $('[data-toggle="tooltip"]').tooltip({
        trigger : 'hover'
    });

    $('[data-toggle="tooltip"]').click(function()
    {
        $('[data-toggle="tooltip"]').tooltip('hide');           
    });

    $("#panel-fullscreen").click(function (e) 
    {
        e.preventDefault();
        
        var $this = $(this);
    
        if ($this.children('svg').hasClass('fa-expand-arrows-alt'))
        {
            $this.children('svg').removeClass('fa-expand-arrows-alt');
            $this.children('svg').addClass('fa-compress-arrows-alt');
            $('#loading').removeClass('form-loading');
            $('#loading').addClass('form-loading-full');
            $("#panel-fullscreen").attr('data-original-title', 'Contraer');

            $('div.dataTables_scrollBody').height(($(window).height() - 160));
        }
        else if ($this.children('svg').hasClass('fa-compress-arrows-alt'))
        {
            $this.children('svg').removeClass('fa-compress-arrows-alt');
            $this.children('svg').addClass('fa-expand-arrows-alt');
            $('#loading').removeClass('form-loading-full');
            $('#loading').addClass('form-loading');
            $("#panel-fullscreen").attr('data-original-title', 'Expandir');

            $('div.dataTables_scrollBody').height(($(window).height() - 350));
        }

        $(this).closest('.panel').toggleClass('panel-fullscreen');

    });

    $.ajax({
    type: 'POST',
    url: $path_notifications,
    dataType: 'json',
    success: function (response) 
    {
        if (response.data)
        {
            $('.notifications').removeClass('d-none-force');
            $('#notifications_list').append(response.html);
        }
        else
        {
            $('.notifications').addClass('d-none-force');
        }

    },
    error: function () 
    {
        modal_alert(false, 'Error de conexión.');
    }
});
});

function trace($path_trace, idname, id)
{
    $('#text_global_trace').addClass('d-none');
    $('.br-pagebody').attr('style', 'cursor: wait;');
    $('#modal_trace').iziModal({
        title: 'Histórico del registro',
        icon: 'fas fa-history',
        headerColor: '#224978',
        zindex: 9999,
        onClosing: function()
        {
            $('#create_trace span').html('');
            $('#edit_trace span').html('');
            $('#edit_trace').addClass('d-none');
        }
    });

    var data     =  {};
    data[idname] =  id;

    $.ajax({
        type: 'POST',
        url: $path_trace,
        data: data,
        dataType: 'json',
        success: function (response) 
        {
            $('.br-pagebody').attr('style', 'cursor: auto;');

            if (response.data)
            {
                var row_thead   =   '<thead>'
                                +   '<tr>'
                                +   '<th class="wd-30p-force p-2">Acción</th>'
                                +   '<th class="wd-35p-force p-2">Fecha</th>'
                                +   '<th class="wd-35p-force p-2">Usuario</th>'
                                +   '</tr>'
                                +   '</thead>';

                var row_tbody  =   '<tbody>'
                                +   '<tr>'
                                +   '<td><b>Creación</b></td>'
                                +   '<td class="text-center">' + response.data.date_insert + '</td>'
                                +   '<td class="text-center">' + (response.data.user_insert ? response.data.user_insert: '') + '</td>'
                                +   '</tr>'
                                +   '</tbody>';

                if (response.data.user_update != null && response.data.user_update != '') 
                {
                    row_tbody  +=   '<tbody>'
                                +   '<tr>'
                                +   '<td><b>Última edición</b></td>'
                                +   '<td class="text-center">' + response.data.date_update + '</td>'
                                +   '<td class="text-center">' + response.data.user_update + '</td>'
                                +   '</tr>'
                                +   '</tbody>';
                }

                $('#row_trace').html(row_thead + row_tbody);

                var data_global = response.data_global;

                if ((data_global != false) && (data_global.length > 1))
                {
                    var row_thead   =   '<thead>'
                                    +   '<tr>'
                                    +   '<th class="wd-10p-force p-2">No</th>'
                                    +   '<th class="wd-40p-force p-2">Fecha</th>'
                                    +   '<th class="wd-40p-force p-2">Usuario</th>'
                                    +   '</tr>'
                                    +   '</thead>';

                    var row_tbody   =  '<tbody>';

                    for (var i = 1; i < data_global.length; i++)
                    {
                        row_tbody   +=  '<tbody>'
                                    +   '<tr>'
                                    +   '<td class="text-center">' + i + '</td>'
                                    +   '<td class="text-center">' + data_global[i]['date_update'] + '</td>'
                                    +   '<td class="text-center">' + data_global[i]['user_update'] + '</td>'
                                    +   '</tr>';
                    }

                    row_tbody   +=  '</tbody>';

                    $('#global_trace').html(row_thead + row_tbody);

                    $('#text_global_trace').removeClass('d-none');
                }

                $('#modal_trace').iziModal('open'); 
            }           
            else
            {
                modal_alert(response.data, response.message);
            } 

        },
        error: function () 
        {
            modal_alert(false, 'Error de conexión.');
        }
    });

    $('#btn_close_trace').on('click', function() 
    {
        $('#modal_trace').iziModal('close');
        $('#row_trace').html('');
        $('#global_trace').html('');
    });
}

function size_validate(id, size)
{
    var file = $('#' + id)[0].files[0];
    
    if (file != undefined) 
    {
        if (file.size > size) 
        {
            var user = (file.size)/1000000;
            var permit = (size)/1000000;

            modal_alert(false, 'El archivo excede el tamaño máximo permitido de ' + permit + ' MB, actualmente tiene un tamaño de ' + user.toFixed(2) + ' MB.');
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
        return true;
    }
}

function modal_alert(data, message)
{  
    iziToast.show(
    {
        backgroundColor: (data ? '#23BF08' : '#DC3545'),
        icon: (data ? 'far fa-check-circle' : 'far fa-times-circle'),
        iconColor: '#FFF',
        maxWidth: 420,
        message: '<br/><br/>' + message,
        messageColor: '#FFF',
        position: 'topRight',
        timeout: 5000,
        title: (data ? 'Información' : 'Alerta'),
        titleColor: '#FFF'
    });

    var defaultTable = $('#default_table').DataTable();
    defaultTable.ajax.reload();

    if (data)
    {
        $('#view_table').removeClass('d-none');        
    }

    setTimeout(function(){ $('#loading').addClass('d-none'); }, 1000);
}

function modal_alert_and_continue(data, message)
{  
    iziToast.show(
    {
        backgroundColor: (data ? '#23BF08' : '#DC3545'),
        icon: (data ? 'far fa-check-circle' : 'far fa-times-circle'),
        iconColor: '#FFF',
        maxWidth: 420,
        message: '<br/><br/>' + message,
        messageColor: '#FFF',
        position: 'topRight',
        timeout: 5000,
        title: (data ? 'Información' : 'Alerta'),
        titleColor: '#FFF'
    });

    setTimeout(function(){ $('#loading').addClass('d-none'); }, 1000);
}

function modal_alert_and_url(data, message, url)
{  
    iziToast.show(
    {
        backgroundColor: (data ? '#23BF08' : '#DC3545'),
        icon: (data ? 'far fa-check-circle' : 'far fa-times-circle'),
        iconColor: '#FFF',
        maxWidth: 420,
        message: '<br/><br/>' + message,
        messageColor: '#FFF',
        position: 'topRight',
        timeout: 5000,
        title: (data ? 'Información' : 'Alerta'),
        titleColor: '#FFF'
    });

    setTimeout(function()
    {
        $('#loading').addClass('d-none');
        location.href = url;
    }, 6000);
}

function cut_text(param, count)
{
    var count_param = param.length;

    if (count_param > count)
    {
        return param.substring(0,count) + '...';
    }
    else
    {
        return param;
    }
}

function replace_all(text, search, txtreplace)
{
    while (text.toString().indexOf(search) != -1)
        text = text.toString().replace(search, txtreplace);

    return text;
}

function convert_money(amount) 
{
    amount += '';
    amount = parseFloat(amount.replace(/[^0-9\.]/g, ''));
    var decimals = 0;

    decimals = decimals || 0; 

    if (isNaN(amount) || amount === 0) 
    {
        return parseFloat(0).toFixed(decimals);
    }

    amount = '' + amount.toFixed(decimals);

    var amount_parts = amount.split('.'),
        regexp = /(\d+)(\d{3})/;

    while (regexp.test(amount_parts[0]))
    {
        amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');
    }

    return amount_parts.join('.');
}

function convert_pointer(amount) 
{
    amount += '';
    amount = parseFloat(amount.replace(/[^0-9\.]/g, ''));
    var decimals = 0;

    decimals = decimals || 0; 

    if (isNaN(amount) || amount === 0) 
    {
        return parseFloat(0).toFixed(decimals);
    }

    amount = '' + amount.toFixed(decimals);

    var amount_parts = amount.split('.'),
        regexp = /(\d+)(\d{3})/;

    while (regexp.test(amount_parts[0]))
    {
        amount_parts[0] = amount_parts[0].replace(regexp, '$1' + '.' + '$2');
    }

    return amount_parts.join('.');
}

function copy_to_clipboard(element)
{
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).html()).select();
    document.execCommand("copy");
    $temp.remove();
}