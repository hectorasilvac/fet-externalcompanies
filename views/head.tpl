{extends file='layout.tpl'}
{block name='head'}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>.: TRABAJANDOFET :.</title>
    <link rel="shortcut icon" type="image/png" href="{$RESOURCES}img/favicon.png">
    {if $css_style eq ''}
    <link href="{$RESOURCES}css/trabajandofet.css" rel="stylesheet"> 
    {else}
    <link href="{$RESOURCES}css/{$css_style}.css" rel="stylesheet"> 
    {/if}
    <link href="{$RESOURCES}lib/jquery-ui/css/jquery-ui.css" rel="stylesheet">
    <link href="{$RESOURCES}lib/malihu-scrollbar/css/jquery.mCustomScrollbar.css" rel="stylesheet"> 
    <link href="{$RESOURCES}lib/switchbutton/css/switchButton.css" rel="stylesheet"> 
    <link href="{$RESOURCES}lib/izimodal/css/iziModal.css" rel="stylesheet"> 
    <link href="{$RESOURCES}lib/izitoast/css/iziToast.css" rel="stylesheet">
    <link href="{$RESOURCES}lib/select2/css/select2.css" rel="stylesheet"> 
    <link href="{$RESOURCES}lib/select2/css/select2-bootstrap4.css" rel="stylesheet"> 
    <link href="{$RESOURCES}lib/datatables/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{$RESOURCES}lib/x-editable/css/bootstrap-editable.css" rel="stylesheet">
    <link href="{$RESOURCES}lib/datatables/css/scroller.bootstrap4.min.css" rel="stylesheet">    
    <link href="{$RESOURCES}lib/font-awesome/css/all.css" rel="stylesheet">
    <link href="{$RESOURCES}lib/smart-wizard/css/smart_wizard.css" rel="stylesheet">
    <link href="{$RESOURCES}lib/smart-wizard/css/smart_wizard_theme_circles.min.css" rel="stylesheet">
    <link href="{$RESOURCES}lib/spectrum/spectrum.css" rel="stylesheet">
    <link href="{$RESOURCES}lib/nestable/css/style.css" rel="stylesheet">
    <link href="{$RESOURCES}lib/nestable/css/style.css" rel="stylesheet">
    <link href="{$RESOURCES}lib/datepicker/datepicker.css" rel="stylesheet">
    <link href="{$RESOURCES}lib/timepicker/dist/wickedpicker.min.css" rel="stylesheet">
{/block}