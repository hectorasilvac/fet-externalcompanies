{extends file='../head.tpl'}
{block name='body'}
    <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
            <span class="breadcrumb-item active">{$module_layout}</span>
            <span class="breadcrumb-item active">{$submodule_layout}</span>
        </nav>
    </div>
    <div class="pos-relative">
        <div id="loading" class="form-loading d-none">
            <div class="gif-loading">
            </div>
        </div>
        <div class="br-pagebody mg-t-3 pd-x-30">
            <div class="br-section-wrapper mn-ht-120 panel">
                <h6 class="tx-gray-800 tx-bold tx-14 mg-b-10">{$submodule_layout}</h6>
                <div class="ft-right">
                    <button type="button" id="panel-fullscreen" class="btn btn-primary ft-right ml-1" data-toggle="tooltip"
                        data-placement="top" title="Expandir">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </button>
                </div>
                <div id="view_table">
                    <div class="ft-right">
                        {if $act_export_pdf}
                            <a id="btn_export_pdf" class="btn btn-primary ft-right ml-1" data-toggle="tooltip"
                                data-placement="top" title="Exportar PDF">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        {/if}
                        {if $act_export_xlsx}
                            <a id="btn_export_xlsx" class="btn btn-primary ft-right ml-1" data-toggle="tooltip"
                                data-placement="top" title="Exportar">
                                <i class="fas fa-file-excel"></i>
                            </a>
                        {/if}
                        {if $act_add}
                            <button type="button" id="btn_add" class="btn btn-primary ft-right ml-1" data-toggle="tooltip"
                                data-placement="top" title="Agregar">
                                <i class="fas fa-plus"></i>
                            </button>
                        {/if}
                    </div>
                    {if $mobile}<br /><br /><br />{/if}
                    <div class="table-wrapper">
                        {if $act_view}
                            <table id="default_table" class="table table-hover tb-responsive wd-100p-force text-center">
                                <thead>
                                    <tr>
                                        <th class="wd-25p-force p-3 text-center">Trabajador</th>
                                        <th class="wd-15p-force p-3 text-center">Correo corporativo</th>
                                        <th class="wd-5p-force p-3 text-center">Extensión Interna</th>
                                        <th class="wd-5p-force p-3 text-center">Extensión Externa</th>
                                        {if $act_edit or $act_drop or $act_trace}
                                            <th class="wd-20p-force p-3 text-center"></th>
                                        {else}
                                            <th class="d-none"></th>
                                        {/if}
                                    </tr>
                                </thead>
                            </table>
                        {/if}
                    </div>
                </div>
                {if $mobile}<br />{/if}
                {if $act_add}
                    <div id="view_form_add" class="mg-t-60 d-none">
                        <div class="form-layout">
                            <form id="form_add" method="post" action="{$path_add}">
                                <div class="row mg-b-25">
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Trabajador: </label>
                                            <select class="form-control" id="id_worker" name="id_worker">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Teléfono asignado:
                                                <span class="tx-primary tx-10">(Opcional)</span>
                                            </label>
                                            <select class="form-control" id="id_element1" name="id_element1">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Celular asignado:
                                                <span class="tx-primary tx-10">(Opcional)</span>
                                            </label>
                                            <select class="form-control" id="id_element2" name="id_element2">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Correo corporativo: </label>
                                            <input class="form-control" type="email" id="email_extension" name="email_extension"
                                                placeholder="Ingresa el correo corporativo" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Número de celular:
                                                <span class="tx-primary tx-10">(Opcional)</span>
                                            </label>
                                            <input class="form-control" type="tel" id="phone_extension" name="phone_extension"
                                                placeholder="Ingresa el número de celular" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Extensión interna:
                                                <span class="tx-primary tx-10">(Opcional)</span>
                                            </label>
                                            <input class="form-control" type="number" id="internal_extension"
                                                name="internal_extension" placeholder="Ingresa la extensión interna"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Extensión externa:
                                                <span class="tx-primary tx-10">(Opcional)</span>
                                            </label>
                                            <input class="form-control" type="number" id="external_extension"
                                                name="external_extension" placeholder="Ingresa la extensión externa"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Dirección IP:
                                                <span class="tx-primary tx-10">(Opcional)</span>
                                            </label>
                                            <input class="form-control" type="text" id="ip_extension" name="ip_extension"
                                                placeholder="Ingresa la dirección IP" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="ckbox">
                                                <input class="flags" type="checkbox" id="git_company" name="git_company"
                                                    value="A">
                                                <span>¿El registro es multiplataforma?</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="ckbox">
                                                <input class="flags" type="checkbox" id="flag_pdf" name="flag_pdf" value="0">
                                                <span>Mostrar en el directorio PDF</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <button id="btn_confirm_add" type="button" class="btn btn-info">Agregar</button>
                        <button id="btn_cancel_add" type="button" class="btn btn-secondary">Cancelar</button>
                        </form>
                    </div>
                {/if}
                {if $act_edit}
                    <div id="view_form_edit" class="mg-t-60 d-none">
                        <div class="form-layout">
                            <form id="form_edit" method="post" action="{$path_edit}">
                                <input class="form-control" type="hidden" id="id_extension_edit" name="pk">
                                <div class="row mg-b-25">
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Teléfono asignado:
                                                <span class="tx-primary tx-10">(Opcional)</span>
                                            </label>
                                            <select class="form-control" id="id_element1_edit" name="id_element1">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Celular asignado:
                                                <span class="tx-primary tx-10">(Opcional)</span>
                                            </label>
                                            <select class="form-control" id="id_element2_edit" name="id_element2">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Número de celular:
                                                <span class="tx-primary tx-10">(Opcional)</span>
                                            </label>
                                            <input class="form-control" type="tel" id="phone_extension_edit"
                                                name="phone_extension" placeholder="Ingresa el número de celular"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Dirección IP:
                                                <span class="tx-primary tx-10">(Opcional)</span>
                                            </label>
                                            <input class="form-control" type="text" id="ip_extension_edit" name="ip_extension"
                                                placeholder="Ingresa la dirección IP" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="ckbox">
                                                <input class="flags" type="checkbox" id="git_company_edit" name="git_company"
                                                    value="A">
                                                <span>¿El registro es multiplataforma?</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="ckbox">
                                                <input class="flags" type="checkbox" id="flag_pdf_edit" name="flag_pdf" value="0">
                                                <span>Mostrar en el directorio PDF</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div>
                                <button id="btn_confirm_edit" class="btn btn-info">Editar</button>
                                <button id="btn_cancel_edit" class="btn btn-secondary">Cancelar</button>
                            </div>
                        </div>
                    </div>
                    <div id="view_edit_flags" class="mg-t-60 d-none">
                        <div class="form-layout">
                            <input class="form-control" type="hidden" id="id_user_flags" name="id_user">
                            <div class="row mg-b-25">
                                {foreach from=$all_flags item=row}
                                    <div class="col-lg-6 mg-t-20">
                                        <label class="ckbox">
                                            <input class="flags_edit" id="flag{$row.id_flag}" name="{$row.name_flag}"
                                                type="checkbox" value="{$row.id_flag}">
                                            <span>{$row.name_es_flag}</span>
                                        </label>
                                    </div>
                                {/foreach}
                            </div>
                            <div>
                                <button id="btn_cancel_edit_flags" class="btn btn-secondary">Cerrar</button>
                            </div>
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
    </div>
    {if $act_drop}
        <div id="modal_delete" class="d-none">
            <div class="bd-0 tx-14">
                <div class="card bd-0">
                    <div class="card-body bd bd-t-0 rounded-bottom-0">
                        <br />
                        <p class="mg-b-0"> ¿Seguro que deseas eliminar esta extensión?</p>
                        <br />
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
{/block}
{block name='scripts'}
    <script type="text/javascript">
        var $path_view                                                          =   '{$path_view}';
        var $path_add                                                           =   '{$path_add}';
        var $path_edit                                                          =   '{$path_edit}';
        var $path_detail                                                        =   '{$path_detail}';
        var $path_drop                                                          =   '{$path_drop}';
        var $path_trace                                                         =   '{$path_trace}';
        var $path_workers                                                       =   '{$path_workers}';
        var $path_areas                                                         =   '{$path_areas}';
        var $path_telephones                                                    =   '{$path_telephones}';
        var $path_cellphones                                                    =   '{$path_cellphones}';
        var $path_export_xlsx                                                   =   '{$path_export_xlsx}';
        var $path_export_pdf                                                    =   '{$path_export_pdf}';

        var act_view                                                            =   '{$act_view}';
        var act_add                                                             =   '{$act_add}';
        var act_edit                                                            =   '{$act_edit}';
        var act_drop                                                            =   '{$act_drop}';
        var act_trace                                                           =   '{$act_trace}';
        var act_export_xlsx                                                     =   '{$act_export_xlsx}';
        var act_export_pdf                                                      =   '{$act_export_pdf}';
    </script>
    {if $mobile}
        <script src="{$RESOURCES}js/mobile/extensions.js"></script>
    {else}
        <script src="{$RESOURCES}js/desktop/extensions.js"></script>
    {/if}
{/block}