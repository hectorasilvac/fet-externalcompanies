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
                <button type="button" id="panel-fullscreen" class="btn btn-primary ft-right ml-1" data-toggle="tooltip" data-placement="top" title="Expandir">
                    <i class="fas fa-expand-arrows-alt"></i>
                </button>
            </div>
            <div id="view_table">
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
                {if $mobile}<br/><br/><br/>{/if}
                <div class="table-wrapper">
                    {if $act_view}
                    <table id="default_table" class="table table-hover tb-responsive wd-100p-force">
                        <thead>
                            <tr>
                                {if !$mobile}<th class="wd-10p-force p-2">No</th>{/if}
                                <th class="wd-15p-force p-2">Módulo</th>
                                <th class="wd-25p-force p-2">Significado</th>
                                <th class="wd-20p-force p-2">URL</th>
                                <th class="wd-20p-force p-2">Icono</th>
                                {if $act_trace}
                                <th class="wd-10p-force p-2"></th>
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
            <div id="view_form_add" class="mg-t-60 d-none">
                <div id="loading" class="form_loading d-none"></div>
                <div class="form-layout">
                    <form id="form_add" method="post" action="{$path_add}">
                        <div class="row mg-b-25">
                            <div class="col-lg-12">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Módulo:</label>
                                    <input class="form-control" type="text" id="name_module" name="name_module" placeholder="Ingresa módulo" autocomplete="false" autofocus="true">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Significado:</label>
                                    <input class="form-control" type="text" id="name_es_module" name="name_es_module" placeholder="Ingresa significado del módulo" autocomplete="false">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">URL:</label>
                                    <input class="form-control" type="text" id="url_module" name="url_module" placeholder="Ingresa URL" autocomplete="false">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Icono:</label>
                                    <input class="form-control" type="text" id="icon_module" name="icon_module" placeholder="Ingresa icono" autocomplete="false">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div>
                        <button id="btn_confirm_add" class="btn btn-info">Agregar</button>
                        <button id="btn_cancel_add" class="btn btn-secondary">Cancelar</button>
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
        var $path_edit                                                          =   '{$path_edit}';
        var $path_trace                                                         =   '{$path_trace}';
        var $path_export_xlsx                                                   =   '{$path_export_xlsx}';
        var $path_users_permission                                              =   '{$path_users_permission}';

        var act_edit                                                            =   '{$act_edit}';
        var act_trace                                                           =   '{$act_trace}';
    </script>
    {if $mobile}
        <script src="{$RESOURCES}js/mobile/modules.js"></script>
    {else}
        <script src="{$RESOURCES}js/desktop/modules.js"></script>
    {/if}
{/block}