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
                                <th class="wd-15p-force p-2">Nombre</th>
                                <th class="wd-10p-force p-2">Rol</th>
                                <th class="wd-10p-force p-2">Usuario</th>
                                <th class="wd-10p-force p-2">Correo</th>
                                <th class="wd-15p-force p-2">Último ingreso</th>
                                <th class="wd-15p-force p-2">Aspirante</th>
                                {if $act_display or $act_edit or $act_drop or $act_trace}
                                <th class="wd-15p-force p-2"></th>
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
                                    <label class="form-control-label">Nombres:</label>
                                    <input class="form-control" type="text" id="name_user" name="name_user" placeholder="Ingresa nombres" autocomplete="off"  autofocus="true">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Apellidos:</label>
                                    <input class="form-control" type="text" id="lastname_user" name="lastname_user" placeholder="Ingresa apellidos" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Rol:</label>
                                    <select class="form-control" id="role" name="id_role">
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Correo:</label>
                                    <input class="form-control" type="text" id="email_user" name="email_user" placeholder="Ingresa correo electrónico" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Usuario:</label>
                                    <input class="form-control" type="text" id="user" name="user" placeholder="Ingresa usuario" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Contraseña:</label>
                                    <input class="form-control" type="password" id="password_user" name="password_user" placeholder="Ingresa contraseña" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Aspirante vinculado:</label>
                                    <select class="form-control" id="aspirant" name="id_aspirant">
                                    </select>
                                </div>
                            </div>
                            {foreach from=$all_flags item=row}
                                <div class="col-lg-6 mg-t-20">
                                    <label class="ckbox">
                                        <input class="flags" id="{$row.name_flag}" name="flags[{$row.name_flag}]" type="checkbox" value="{$row.id_flag}">
                                        <span>{$row.name_es_flag}</span>
                                    </label>
                                </div>
                            {/foreach}
                        </div>
                    </form>
                    <div>
                        <button id="btn_confirm_add" class="btn btn-info">Agregar</button>
                        <button id="btn_cancel_add" class="btn btn-secondary">Cancelar</button>
                    </div>
                </div>
            </div>
            {/if}
            {if $act_edit}
            <div id="view_form_edit" class="mg-t-60 d-none">
                <div class="form-layout">
                    <form id="form_edit" method="post" action="{$path_edit}">
                        <input class="form-control" type="hidden" id="id_user" name="pk">
                        <input class="form-control" type="hidden" id="name_password" name="name" value="password_user" placeholder="Ingresa contraseña" autocomplete="off">
                        <label id="txt_name"></label>
                        <div class="row mg-b-25">
                            <div class="col-lg-12">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Contraseña: </label>   
                                    <input class="form-control" type="password" id="password_user_edit" name="value" placeholder="Ingresa contraseña" autocomplete="off">
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
                                <input class="flags_edit" id="flag{$row.id_flag}" name="{$row.name_flag}" type="checkbox" value="{$row.id_flag}">
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
{if $act_display}
<div id="modal_display" class="d-none">
    <div class="bd-0 tx-14">
        <div class="card bd-0">
            <div class="card-body bd bd-t-0 rounded-bottom-0">
                <br/>
                <p class="mg-b-0"> Seguro que deseas <span id="txt_display"></span> este usuario?</p>
                <br/>
            </div>
            <div class="card-footer bd bd-t-0 d-flex justify-content-between">
                <button id="btn_confirm_display" class="btn btn-primary">Confirmar</button>
                <button id="btn_cancel_display" class="btn btn-secondary">Cancelar</button>
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
                <p class="mg-b-0"> Seguro que deseas eliminar este usuario?</p>
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
        var $path_userflags                                                     =   '{$path_userflags}';
        var $path_editflags                                                     =   '{$path_editflags}';
        var $path_display                                                       =   '{$path_display}';
        var $path_drop                                                          =   '{$path_drop}';
        var $path_trace                                                         =   '{$path_trace}';
        var $path_export_xlsx                                                   =   '{$path_export_xlsx}';
        var $path_roles                                                         =   '{$path_roles}';
        var $path_aspirants                                                     =   '{$path_aspirants}';

        var act_edit                                                            =   '{$act_edit}';
        var act_display                                                         =   '{$act_display}';
        var act_drop                                                            =   '{$act_drop}';
        var act_trace                                                           =   '{$act_trace}';
    </script>
    {if $mobile}
        <script src="{$RESOURCES}js/mobile/users.js"></script>
    {else}
        <script src="{$RESOURCES}js/desktop/users.js"></script>
    {/if}
{/block}