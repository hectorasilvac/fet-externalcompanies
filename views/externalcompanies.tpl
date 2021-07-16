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
                                        <th class="wd-25p-force p-3 text-center">Nombre</th>
                                        <th class="wd-10p-force p-3 text-center">NIT</th>
                                        <th class="wd-10p-force p-3 text-center">Tipo</th>
                                        <th class="wd-15p-force p-3 text-center">Correo</th>
                                        <th class="wd-15p-force p-3 text-center">Teléfono</th>
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
                            <form id="form_add" method="post">
                                <div class="row mg-b-25">
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Nombre de la empresa: </label>
                                            <input class="form-control" type="text" id="name_cv_ec" name="name_cv_ec"
                                                placeholder="Ingresa el nombre de la empresa" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">NIT de la empresa: <span
                                                    class="tx-primary tx-10">(Opcional)</span></label>
                                            <input class="form-control" type="text" id="nit_cv_ec" name="nit_cv_ec"
                                                placeholder="Ingresa el NIT de la empresa" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Tipo de empresa: </label>
                                            <select class="form-control" id="type_cv_ec" name="type_cv_ec">
                                                <option value="" disabled selected>Selecciona tipo de empresa</option>
                                                <option value="PUBLICA">Pública</option>
                                                <option value="PRIVADA">Privada</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Correo electrónico: <span
                                                    class="tx-primary tx-10">(Opcional)</span></label>
                                            <input class="form-control" type="text" id="email_cv_ec" name="email_cv_ec"
                                                placeholder="Ingresa el correo electrónico" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Teléfono: <span
                                                    class="tx-primary tx-10">(Opcional)</span></label>
                                            <input class="form-control" type="text" id="phone_cv_ec" name="phone_cv_ec"
                                                placeholder="Ingresa el teléfono" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Dirección: <span
                                                    class="tx-primary tx-10">(Opcional)</span></label>
                                            <input class="form-control" type="text" id="add_address_cv_ec" name="address_cv_ec"
                                                placeholder="Ingresa la dirección" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                    <div class="form-group pos-relative">
                                        <label class="form-control-label">País: </label>
                                        <select class="form-control" id="country_cv_ec" name="country_cv_ec">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group pos-relative">
                                        <label class="form-control-label">Departamento: </label>
                                        <select class="form-control" id="department_cv_ec" name="department_cv_ec">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group pos-relative">
                                        <label class="form-control-label">Ciudad: </label>
                                        <select class="form-control" id="city_cv_ec" name="city_cv_ec">
                                        </select>
                                    </div>
                                </div>
                                </div>
                                <button id="btn_confirm_add" class="btn btn-info">Agregar</button>
                                <button id="btn_cancel_add" type="button" class="btn btn-secondary">Cancelar</button>
                            </form>
                        </div>
                    </div>
                {/if}
                {if $act_edit}
                    <div id="view_form_edit" class="mg-t-60 d-none">
                        <div class="form-layout">
                            <form id="form_edit" method="post" action="{$path_edit}">
                                <div class="row mg-b-25">
                                    <input class="form-control" type="hidden" name="pk">
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Dirección: <span
                                                    class="tx-primary tx-10">(Opcional)</span></label>
                                            <input class="form-control" type="text"
                                                name="address_cv_ec" placeholder="Ingresa la dirección"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">País: </label>
                                            <select class="form-control" name="country_cv_ec">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Departamento: </label>
                                            <select class="form-control" name="department_cv_ec">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Ciudad: </label>
                                            <select class="form-control" name="city_cv_ec">
                                            </select>
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
                {if $act_details}
                    <div id="view_details" class="mg-t-60 d-none">
                        <table class="table table-bordered border">
                            <tbody>
                                <tr>
                                    <th scope="row" class="col-2 bg-light">Nombre</th>
                                    <td class="col-10" data-name="name_cv_ec"></td>
                                </tr>
                                <tr>
                                    <th scope="row" class="col-2 bg-light">NIT</th>
                                    <td class="col-10" data-name="nit_cv_ec"></td>
                                </tr>
                                <tr>
                                    <th scope="row" class="col-2 bg-light">Tipo</th>
                                    <td class="col-10" data-name="type_cv_ec"></td>
                                </tr>
                                <tr>
                                    <th scope="row" class="col-2 bg-light">Teléfono</th>
                                    <td class="col-10" data-name="phone_cv_ec"></td>
                                </tr>
                                <tr>
                                    <th scope="row" class="col-2 bg-light">Correo</th>
                                    <td class="col-10" data-name="email_cv_ec">grupoaw@gmail.com</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="col-2 bg-light">Dirección</th>
                                    <td class="col-10" data-name="address_cv_ec">Calle 45 #34-56</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="col-2 bg-light">País</th>
                                    <td class="col-10" data-name="country_cv_ec"></td>
                                </tr>
                                <tr>
                                    <th scope="row" class="col-2 bg-light">Departamento</th>
                                    <td class="col-10" data-name="department_cv_ec"></td>
                                </tr>
                                <tr>
                                    <th scope="row" class="col-2 bg-light">Ciudad</th>
                                    <td class="col-10" data-name="city_cv_ec"></td>
                                </tr>
                            </tbody>
                        </table>
                        <button id="btn_cancel_details" class="btn btn-secondary">Cerrar</button>
                    </div>
                {/if}
                {if $act_assign}
                    <div id="view_assign" class="mg-t-60 d-none">
                    <table class="table table-hover table-bordered border">
                            <thead id="assign_head">
                                <tr>
                                    <th scope="col">Nombre del Aspirante</th>
                                    <th scope="col">Número del Documento</th>
                                </tr>
                            </thead>
                            <tbody id="assign_content">
                            </tbody>
                        </table>
                        <button id="btn_cancel_assign" class="btn btn-secondary">Cerrar</button>
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
                        <br />
                        <p class="mg-b-0"> Seguro que deseas eliminar esta empresa?</p>
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
        var $path_add                                                           =   '{$path_add}';
        var $path_view                                                          =   '{$path_view}';
        var $path_edit                                                          =   '{$path_edit}';
        var $path_details                                                       =   '{$path_details}';
        var $path_editflags                                                     =   '{$path_editflags}';
        var $path_display                                                       =   '{$path_display}';
        var $path_drop                                                          =   '{$path_drop}';
        var $path_trace                                                         =   '{$path_trace}';
        var $path_export_xlsx                                                   =   '{$path_export_xlsx}';
        var $path_roles                                                         =   '{$path_roles}';
        var $path_roles                                                         =   '{$path_roles}';
        var $path_find                                                          =   '{$path_find}';

        var $path_location                                                      =   '{$path_location}';

        var act_view                                                            =   '{$act_view}';
        var act_edit                                                            =   '{$act_edit}';
        var act_details                                                         =   '{$act_details}';
        var act_assign                                                          =   '{$act_assign}';
        var act_drop                                                            =   '{$act_drop}';
        var act_trace                                                           =   '{$act_trace}';
        var act_export_xlsx                                                     =   '{$act_export_xlsx}';
    </script>
    {if $mobile}
        <script src="{$RESOURCES}js/mobile/externalcompanies.js"></script>
    {else}
        <script src="{$RESOURCES}js/desktop/externalcompanies.js"></script>
    {/if}
{/block}