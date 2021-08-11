{extends file='../head.tpl'}
{block name='body'}
<div class="br-pageheader pd-y-15 pd-l-20">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        <span class="breadcrumb-item active">{$module_layout}</span>
        <span class="breadcrumb-item active">{$submodule_layout}</span>
    </nav>
</div>
<div class="pos-relative">
    <div id="loading" class="form-loading">
        <div class="gif-loading">
        </div>
    </div>
    <a href="https://wa.link/1nh8xx" class="btn-flotante" data-toggle="tooltip" data-placement="top" title="Tienes dudas o inconvenientes, contactanos por este medio!">
        <img src="{$RESOURCES}img/wsp.png" class="wd-50">
    </a>
    <div class="br-pagebody mg-t-3 pd-x-30">
        <div class="br-section-wrapper mn-ht-120 panel">
            <h6 class="tx-gray-800 tx-bold tx-14 mg-b-10">{$submodule_layout}</h6>
            {if !$mobile}
            <div class="row">
                <div class="col-lg-6">
                    {if $flag_coordinator}
                    <select class="form-control" id="shiftchange_coordinator">
                    </select>
                    {/if}
                </div>
                <div class="col-lg-6">
                    {if $flag_filter}
                    <select class="form-control" id="shiftchange_state">
                        <option value="0">Sin aprobar</option>
                        <option value="1">Aprobado</option>
                        <option value="2">Rechazado</option>
                    </select>
                    {/if}
                </div>
            </div>
            <br/>
            {/if}
            <div class="ft-right">
                <button type="button" id="panel-fullscreen" class="btn btn-primary ft-right ml-1" data-toggle="tooltip" data-placement="top" title="Expandir">
                    <i class="fas fa-expand-arrows-alt"></i>
                </button>
            </div>
            <div id="view_table">
                {if $mobile}
                <div class="ft-right">
                    {if $act_export_xlsx}
                    <a id="btn_export_xlsx" class="btn btn-primary ft-right ml-1" data-toggle="tooltip" data-placement="top" title="Exportar">
                        <i class="fas fa-file-excel"></i>
                    </a>
                    {/if}
                    {if $flag_filter}
                    <select class="form-control-filter" id="shiftchange_state">
                        <option value="0">Sin aprobar</option>
                        <option value="1">Aprobado</option>
                        <option value="2">Rechazado</option>
                    </select>
                    {/if}
                    {if $act_add}
                    <button type="button" id="btn_add" class="btn btn-primary ft-right ml-1" data-toggle="tooltip" data-placement="top" title="Agregar">
                        <i class="fas fa-plus"></i>
                    </button>
                    {/if}
                </div>
                <br/><br/><br/>
                <div class="row">
                    <div class="span-center mg-t-20-force pd-l-8-force">
                        {if $flag_coordinator}
                        <select class="form-control-filter" id="shiftchange_coordinator">
                        </select>
                        {/if}
                    </div>
                </div>
                {else}
                <div class="ft-right">
                    {if $act_export_xlsx}
                    <a id="btn_export_xlsx" class="btn btn-primary ft-right ml-1" data-toggle="tooltip" data-placement="top" title="Exportar">
                        <i class="fas fa-file-excel"></i>
                    </a>
                    {/if}
                    {if $act_add}
                    <button type="button" id="btn_add" class="btn btn-primary ft-right ml-1" data-toggle="tooltip" data-placement="top" title="Agregar">
                        <i class="fas fa-plus"></i>
                    </button>
                    {/if}
                </div>
                {/if}
                {if $mobile}<br/>{/if}
                <div class="table-wrapper">
                    {if $act_view}
                    <table id="default_table" class="table table-hover tb-responsive wd-100p-force">
                        <thead>
                            <tr>
                                {if !$mobile}<th class="wd-5p-force p-2">No</th>{/if}
                                <th class="wd-25p-force p-2">Trabajador solicitante</th>
                                <th class="wd-10p-force p-2">Fecha de cambio</th>
                                <th class="wd-25p-force p-2">Trabajador de reemplazo</th>
                                <th class="wd-10p-force p-2">Fecha de reemplazo</th>
                                <th class="wd-5p-force p-2">Estado</th>
                                {if $act_drop or $act_trace}
                                <th class="wd-20p-force p-2"></th>
                                {else}
                                <th class="d-none"></th>
                                {/if}
                            </tr>
                        </thead>
                    </table>
                    {/if}
                </div>
            </div>
            {if $mobile}<br/>{/if}
            {if $act_add}
            <div id="view_form_add" class="mg-t-50 d-none">
                <div class="form-layout">
                    <form id="form_add" method="post" action="{$path_add}">
                        <div class="row mg-b-25">
                            {if $worker eq false}
                            <div class="col-lg-6">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Solicitante:</label>
                                    <select class="form-control" id="id_worker_applicant" name="id_worker_applicant">
                                    </select>
                                </div>
                            </div>
                            {else}
                            <input class="form-control" type="hidden" name="id_worker_applicant" value="{$worker}">
                            {/if}
                            {if $flag_email eq true}
                            <div class="col-lg-6">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Correo personal solicitante:</label>
                                    <input class="form-control" type="text" id="email_worker_applicant" name="email_worker_applicant" placeholder="Ingresa tu correo personal" autocomplete="off">
                                </div>
                            </div>
                            {/if}
                            <div class="col-lg-6">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Reemplazo:</label>
                                    <select class="form-control" id="id_worker_replacement" name="id_worker_replacement">
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Correo personal del reemplazo:</label>
                                    <input class="form-control" type="text" id="email_worker_replacement" name="email_worker_replacement" placeholder="Ingresa el correo personal del Reemplazo" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Fecha de cambio:</label>
                                    <input class="form-control" type="text" id="date_shiftchange" name="date_shiftchange" placeholder="Ingresa la fecha de cambio" autocomplete="off" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Turno de cambio:</label>
                                    <select class="form-control" id="type_shiftchange" name="type_shiftchange">
                                        <option value="C">Completo</option>
                                        <option value="N">Noche</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Fecha de devolución:</label>
                                    <input class="form-control" type="text" id="date_return_shiftchange" name="date_return_shiftchange" placeholder="Ingresa la fecha de cambio" autocomplete="off" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Turno de devolución:</label>
                                    <select class="form-control" id="type_return_shiftchange" name="type_return_shiftchange">
                                        <option value="C">Completo</option>
                                        <option value="N">Noche</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Reportar a:</label>
                                    <select class="form-control" id="id_coordinator" name="id_coordinator">
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group pos-relatiwe">
                                    <label class="form-control-label">Aprobación:</label>
                                    <label class="ckbox mg-t-10 mg-l-20">
                                        <input id="flag_signature_applicant" name="flag_signature_applicant" value="1" type="checkbox">
                                        <span>Marca aquí, si deseas solicitar el cambio de turno.</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div>
                        <button id="btn_confirm_add" class="btn btn-info">Enviar</button>
                        <button id="btn_cancel_add" class="btn btn-secondary">Cancelar</button>
                    </div>
                </div>
            </div>
            {/if}
            {if $act_detail}
                {if $mobile}
                <div id="view_detail" class="d-none">
                    <div class="form-layout mg-25 pd-t-40">
                        <table class="table table-bordered-force">
                            <thead>
                                <tr>
                                    <th colspan="4" class="tx-center">Información cambio de turno</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="wd-25p bg-gray-100">
                                        <strong>Solicitante:</strong>
                                    </td>
                                    <td class="wd-25p">
                                        <span class="name_worker_applicant span-center"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="wd-25p bg-gray-100">
                                        <strong>Reemplazo:</strong>
                                    </td>
                                    <td class="wd-25p">
                                        <span class="name_worker_replacement span-center"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-gray-100">
                                        <strong>Fecha de cambio:</strong>
                                    </td>
                                    <td>
                                        <span class="date_shiftchange span-center"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-gray-100">
                                        <strong>Fecha de devolución:</strong>
                                    </td>
                                    <td>
                                        <span class="date_return_shiftchange span-center"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-gray-100">
                                        <strong>Turno de cambio:</strong>
                                    </td>
                                    <td>
                                        <span class="type_shiftchange span-center"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-gray-100">
                                        <strong>Turno de devolución:</strong>
                                    </td>
                                    <td>
                                        <span class="type_return_shiftchange span-center"></span>
                                    </td>
                                </tr>
                                <tr class="firm-applicant-detail d-none">
                                    <td class="bg-gray-100">
                                        <strong>Firma del solicitante:</strong>
                                    </td>
                                    <td>
                                        <span class="firm_applicant span-center"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-gray-100">
                                        <strong>Firma del reemplazo:</strong>
                                    </td>
                                    <td>
                                        <span class="firm_replacement span-center"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div>
                            <button id="btn_cancel_detail" class="btn btn-secondary">Cerrar</button>
                        </div>
                    </div>
                </div>
                {else}
                <div id="view_detail" class="d-none">
                    <div class="form-layout mg-25 pd-t-40">
                        <table class="table table-bordered-force">
                            <thead>
                                <tr>
                                    <th colspan="4" class="tx-center">Información cambio de turno</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="wd-25p bg-gray-100">
                                        <strong>Solicitante:</strong>
                                    </td>
                                    <td class="wd-25p">
                                        <span class="name_worker_applicant span-center"></span>
                                    </td>
                                    <td class="wd-25p bg-gray-100">
                                        <strong>Reemplazo:</strong>
                                    </td>
                                    <td class="wd-25p">
                                        <span class="name_worker_replacement span-center"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-gray-100">
                                        <strong>Fecha de cambio:</strong>
                                    </td>
                                    <td>
                                        <span class="date_shiftchange span-center"></span>
                                    </td>
                                    <td class="bg-gray-100">
                                        <strong>Fecha de devolución:</strong>
                                    </td>
                                    <td>
                                        <span class="date_return_shiftchange span-center"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-gray-100">
                                        <strong>Turno de cambio:</strong>
                                    </td>
                                    <td>
                                        <span class="type_shiftchange span-center"></span>
                                    </td>
                                    <td class="bg-gray-100">
                                        <strong>Turno de devolución:</strong>
                                    </td>
                                    <td>
                                        <span class="type_return_shiftchange span-center"></span>
                                    </td>
                                </tr>
                                <tr class="firm-applicant-detail d-none">
                                    <td class="bg-gray-100">
                                        <strong>Firma del solicitante:</strong>
                                    </td>
                                    <td>
                                        <span class="firm_applicant span-center"></span>
                                    </td>
                                    <td class="bg-gray-100">
                                        <strong>Firma del reemplazo:</strong>
                                    </td>
                                    <td>
                                        <span class="firm_replacement span-center"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div>
                            <button id="btn_cancel_detail" class="btn btn-secondary">Cerrar</button>
                        </div>
                    </div>
                </div>
                {/if}
            {/if}
            {if $act_assign}
            <div id="view_change_assign" class="d-none">
                <div class="form-layout mg-25 pd-t-40">
                    <form id="form_change_assign" method="post" action="{$path_change_coordinator}">
                        <div class="row mg-b-25">
                            <div class="col-lg-12">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Repotar a:</label>
                                    <input type="hidden" class="d-none" id="id_shiftchange_change" name="id">
                                    <select class="form-control" id="id_coordinator_change" name="id_coordinator">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button id="btn_confirm_change" class="btn btn-primary">Cambiar</button>
                        <button id="btn_cancel_change" type="button" class="btn btn-secondary">Cancelar</button>
                    </form>
                </div>
            </div>
            {/if}
        </div>
    </div>
</div>
<footer class="br-footer">
    <div class="footer-left">
        <div class="mg-b-2 tx-gray-800">{$COPYRIGHT}</div>
    </div>
</footer>
{if $act_assign}
<div id="modal_assign" class="d-none">
    <div class="bd-0 tx-14">
        <div class="card bd-0">
            <div class="card-body bd bd-t-0 rounded-bottom-0">
                <div>
                    <div class="col-lg-12">
                        <div class="form-group pos-relative">
                            <label class="form-control-label">Por favor selecciona que deseas hacer:</label>
                            <select class="form-control" id="vob_shiftchange">
                                <option value="1">Aprobar</option>
                                <option value="2">Rechazar</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bd bd-t-0 d-flex justify-content-between">
                <button id="btn_confirm_assign" class="btn btn-warning">Confirmar</button>
                <button id="btn_cancel_assign" class="btn btn-secondary">Cancelar</button>
            </div>
        </div>
    </div>
</div>
{/if}
{if $act_drop}
<div id="modal_delete" class="d-none">
    <div class="bd-0 tx-14">
        <div class="card bd-0">
            <div class="card-body bd bd-t-0 rounded-bottom-0">
                <br/>
                <p class="mg-b-0">Seguro que deseas eliminar este cambio de turno?</p>
                <br/>
            </div>
            <div class="card-footer bd bd-t-0 d-flex justify-content-between">
                <button id="btn_confirm_delete" class="btn btn-danger">Confirmar</button>
                <button id="btn_cancel_delete" class="btn btn-secondary">Cancelar</button>
            </div>
        </div>
    </div>
</div>
{/if}
{if $act_trace}
<div id="modal_trace" class="d-none">
    <div class="bd-0 tx-14">
        <div class="card bd-0">
            <div class="card-body bd bd-t-0 rounded-bottom-0">
                <h6 class="text-center">Trazabilidad</h6>
                <div class="table-wrapper">
                    <table id="row_trace" class="table table-hover tb-responsive wd-100p-force"></table>
                </div>
                <hr>
                <h6 id="text_global_trace" class="text-center">Ediciones anteriores</h6>
                <div class="table-wrapper">
                    <table id="global_trace" class="table table-hover tb-responsive wd-100p-force"></table>
                </div>
            </div>
            <div class="card-footer bd bd-t-0 d-flex justify-content-between">
                <button id="btn_close_trace" class="btn btn-secondary">Cerrar</button>
            </div>
        </div>
    </div>
</div>
{/if}
{if $act_add}
<div id="modal_signature" class="d-none">
    <div class="bd-0 tx-14">
        <div class="card bd-0">
            <div class="card-body bd bd-t-0 rounded-bottom-0">
                <br/>
                <p class="mg-b-0">Seguro que deseas solicitar este cambio de turno?</p>
                <br/>
            </div>
            <div class="card-footer bd bd-t-0 d-flex justify-content-between">
                <button id="btn_confirm_sign" class="btn btn-primary">Si</button>
                <button id="btn_cancel_sign" class="btn btn-secondary">No</button>
            </div>
        </div>
    </div>
</div>
{/if}
{/block}
{block name='scripts'}
    <script type="text/javascript">
        var $path_view                                                          =   '{$path_view}';
        var $path_detail                                                        =   '{$path_detail}';
        var $path_signature                                                     =   '{$path_signature}';
        var $path_assign                                                        =   '{$path_assign}';
        var $path_mail_replacement                                              =   '{$path_mail_replacement}';
        var $path_drop                                                          =   '{$path_drop}';
        var $path_trace                                                         =   '{$path_trace}';
        var $path_export_xlsx                                                   =   '{$path_export_xlsx}';

        var $path_workers                                                       =   '{$path_workers}';
        var $path_coordinators                                                  =   '{$path_coordinators}';

        var $path_application                                                   =   '{$path_application}';

        var act_detail                                                          =   '{$act_detail}';
        var act_assign                                                          =   '{$act_assign}';
        var act_drop                                                            =   '{$act_drop}';
        var act_trace                                                           =   '{$act_trace}';

        var day_now                                                             =   '{$day_now}';
        var month                                                               =   '{$month}';
        var flag_worker                                                         =   '{$flag_worker}';

        var $sigdiv                                                             =   $("#signature");
    </script>
    <script src="{$RESOURCES}lib/spectrum/spectrum.js"></script>
    <script src="{$RESOURCES}lib/signature/flashcanvas.js"></script>
    <script src="{$RESOURCES}lib/signature/jSignature.min.js"></script>
    {if $mobile}
        <script src="{$RESOURCES}js/mobile/shiftchange.js"></script>
    {else}
        <script src="{$RESOURCES}js/desktop/shiftchange.js"></script>
    {/if}
{/block}