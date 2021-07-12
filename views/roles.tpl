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
                                <th class="wd-80p-force p-2">Rol</th>
                                {if $act_drop or $act_trace}
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
                <div class="form-layout">
                    <form id="form_add" method="post" action="{$path_add}">
                        <div class="row mg-b-25">
                            <div class="col-lg-12">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Rol:</label>
                                    <input class="form-control" type="text" id="name_role" name="name_role" placeholder="Ingresa rol" autocomplete="false" autofocus="true">
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
{if $act_drop}
<div id="modal_delete" class="d-none">
    <div class="bd-0 tx-14">
        <div class="card bd-0">
            <div class="card-body bd bd-t-0 rounded-bottom-0">
                <br/>
                <p class="mg-b-0">Seguro que deseas eliminar este rol?</p>
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
{/block}
{block name='scripts'}
    <script type="text/javascript">
        var $path_view                                                          =   '{$path_view}';
        var $path_edit                                                          =   '{$path_edit}';
        var $path_drop                                                          =   '{$path_drop}';
        var $path_trace                                                         =   '{$path_trace}';
        var $path_export_xlsx                                                   =   '{$path_export_xlsx}';

        var act_edit                                                            =   '{$act_edit}';
        var act_drop                                                            =   '{$act_drop}';
        var act_trace                                                           =   '{$act_trace}';
    </script>
    {if $mobile}
        <script src="{$RESOURCES}js/mobile/roles.js"></script>
    {else}
        <script src="{$RESOURCES}js/desktop/roles.js"></script>
    {/if}
{/block}