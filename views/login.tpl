<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="author" content="TRABAJANDOFET">
        <link rel="shortcut icon" type="image/png" href="{$RESOURCES}img/favicon.png">
        <title>.: TRABAJANDOFET :.</title>
        <link href="{$RESOURCES}lib/font-awesome/css/all.css" rel="stylesheet">
        <link href="{$RESOURCES}lib/izimodal/css/iziModal.css" rel="stylesheet">
        <link href="{$RESOURCES}lib/izitoast/css/iziToast.css" rel="stylesheet">
        <link href="{$RESOURCES}lib/select2/css/select2.css" rel="stylesheet">
        <link href="{$RESOURCES}lib/select2/css/select2-bootstrap4.css" rel="stylesheet">
        <link href="{$RESOURCES}lib/malihu-scrollbar/css/jquery.mCustomScrollbar.css" rel="stylesheet">
        <link href="{$RESOURCES}lib/datepicker/datepicker.css" rel="stylesheet">
        <link href="{$RESOURCES}css/trabajandofet.css" rel="stylesheet">
</head>

        {if $mobile}
            <body class="bg-mobile{$random}">
        {else}
            <body class="bg-desktop{$random}">
        {/if}
        <div class="container-fluid pd-0">
            <div class="btn-admin">
                <a href="#" class="tx-50" id="btn_modal_admin" data-toggle="tooltip" data-placement="button" title="Administrador">
                    {if $mobile}
                    <img src="{$RESOURCES}img/login/login_user.png" class="wd-25p ft-right">
                    {else}
                    <img src="{$RESOURCES}img/login/login_user.png" class="wd-60p ft-right">
                    {/if}
                </a>
            </div>
            {if $mobile}
                <div data-id="welcome_container" class="d-flex flex-column pd-sm-t-40 pd-t-55 align-items-center">
            <p class="mr-6 ml-4 mb-1">
                <span class="text-fet tx-ubuntu-bold-italic tx-sm-22">Evolucionando </span>
                <span class="text-white tx-ubuntu-italic tx-sm-22">para </span>
                <span class="text-fet tx-ubuntu-bold-italic tx-sm-22">brindarte </span>
                <span class="text-white tx-ubuntu-italic tx-sm-22">un </span>
            </p>
            <h2>
                <span class="text-fet tx-ubuntu-bold-italic d-block tx-sm-88 tx-32">Mejor</span>
                <span class="text-white tx-ubuntu-italic ml-4 tx-sm-88 tx-32 lh-sm-4r lh-2r">Servicio</span>
            </h2>
            <button type="button" id="btn_start"
                class="btn btn-login tx-ubuntu-italic font-weight-bold tx-sm-28 tx-20 mt-sm-5 mt-4 pd-y-7 wd-250">Empecemos</button>
            </div>
            {else}
                <div data-id="welcome_container" class="flex-column pd-xxl-x-160 pd-md-x-80 pd-t-120" >
                    <p class="mb-xxl-4 mb-md-2 ml-4">
                        <span class="text-fet tx-ubuntu-bold-italic tx-xxl-37 tx-md-24">Evolucionando  </span>
                        <span class="text-white tx-ubuntu-italic tx-xxl-37 tx-md-24">para  </span>
                        <span class="text-fet tx-ubuntu-bold-italic tx-xxl-37 tx-md-24">brindarte  </span>
                        <span class="text-white tx-ubuntu-italic tx-xxl-37 tx-md-24">un  </span>
                    </p>
                    <h2>
                        <span class="text-fet tx-ubuntu-bold-italic d-block tx-xxl-140 tx-md-108">Mejor</span>
                        <span class="text-white tx-ubuntu-italic ml-5 tx-xxl-140 tx-md-108 lh-md-6r">Servicio</span>
                    </h2>
                    <button type="button" id="btn_start" class="btn btn-login tx-xxl-45 tx-md-34 mt-xxl-128 mt-md-6 wd-xxl-420 wd-md-360">
                    Empecemos</button>
                </div>
            {/if}
            <div class="d-none" id="login_aspirants">
                <div class="btn-admin">
                    <a href="#" class="tx-40 btn-back tx-white pd-x-20" id="btn_back_login" data-toggle="tooltip" data-placement="button" title="Volver">
                        <i class="fas fa-angle-left"></i>
                    </a>
                </div>
                {if $mobile}
                <div class="d-flex align-items-center justify-content-center bg-br-primary ht-100v bg-mobile-login1">
                    <div class="login-wrapper pd-25 bg-white">
                        <div class="wd-250">
                {else}
                <div class="d-flex align-items-center justify-content-center bg-br-primary ht-100v bg-desktop-login1">
                    <div class="login-wrapper bg-white d-flex justify-content-between">
                        <div class="d-flex align-items-center justify-content-center wd-410">
                            <div class="wd-fill login-banner{$random}"></div>
                        </div>
                        <div class="wd-350 wd-xs-450 pd-25">
                {/if}
                            <div class="signin-logo tx-center tx-28 tx-bold tx-inverse mg-b-40 mg-t-40">
                                Afiliados
                            </div>
                            <form id="form_login_aspirants" method="post" action="{$path_aspirants}">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input name="user" type="text" class="form-control mg-x-auto" placeholder="Nombre de usuario" autocomplete="off" autofocus />
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group pos-relative">
                                            <input name="password" type="password" class="form-control" placeholder="Contraseña" autocomplete="off" />
                                        </div>
                                    </div>
                                    <button id="btn_login_aspirant" type="submit" class="btn btn-primary btn-block mg-t-10 mg-b-20 mg-x-50">Ingresar</button>
                                </div>
                            </form>
                            <div class="mg-t-20 tx-center">¿Aún no estas registrado? registrate <a href="#" id="btn_register_aspirants" class="tx-primary">aquí</a></div>
                            <div class="mg-t-20 tx-center">¿Haz olvidado tu usuario o contraseña? solicita el cambio <a href="#" id="btn_forget_aspirants" class="tx-primary">aquí</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-none" id="register_aspirants">
                <div class="btn-admin">
                    <a href="#" class="tx-40 btn-back tx-white pd-x-20" id="btn_back_register" data-toggle="tooltip" data-placement="button" title="Volver">
                        <i class="fas fa-angle-left"></i>
                    </a>
                </div>
                {if $mobile}
                <div class="d-flex align-items-center justify-content-center bg-br-primary ht-100v bg-mobile-login1">
                    <div class="login-wrapper pd-25 bg-white">
                        <div class="wd-250">
                            <div class="signin-logo tx-center tx-28 tx-bold tx-inverse mg-b-40">
                                <span class="tx-normal"></span> Afiliados <span class="tx-normal"></span><br>Registro
                            </div>
                {else}
                <div class="d-flex align-items-center justify-content-center bg-br-primary ht-100v bg-desktop-login1">
                    <div class="login-wrapper bg-white d-flex justify-content-between">
                        <div class="wd-350 wd-xs-450 pd-25">
                            <div class="signin-logo tx-center tx-28 tx-bold tx-inverse mg-b-40">
                                <span class="tx-normal"></span> Afiliados - Registro <span class="tx-normal"></span>
                            </div>
                {/if}
                            <form id="form_register_aspirants" method="post" action="{$path_register_aspirants}">
                                <div class="ht-250 overflow-y-auto">
                                    <div class="row mg-x-0 form-aspirant-1">
                                        <div class="col-md-12 form-aspirant-1">
                                            <div class="form-group">
                                                <input name="name_aspirant" type="text" class="form-control mg-x-auto" placeholder="Nombre" autocomplete="off" autofocus>  
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-aspirant-1">
                                            <div class="form-group">
                                                <input name="first_last_name_aspirant" type="text" class="form-control mg-x-auto" placeholder="Primer apellido" autocomplete="off">  
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-aspirant-1">
                                            <div class="form-group">
                                                <input name="second_last_name_aspirant" type="text" class="form-control mg-x-auto" placeholder="Segundo apellido" autocomplete="off">  
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-aspirant-1">
                                            <div class="form-group">
                                                <input name="email_aspirant" type="text" class="form-control mg-x-auto" placeholder="Correo electrónico" autocomplete="off">  
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-aspirant-1">
                                            <div class="form-group">
                                                <input name="phone_aspirant" type="number" min="1" class="form-control mg-x-auto" placeholder="Número de celular" autocomplete="off">  
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-aspirant-1">
                                            <div class="form-group">
                                                <input name="user_aspirant" type="text" class="form-control mg-x-auto" placeholder="Nombre de usuario" autocomplete="off">  
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-aspirant-1">
                                            <div class="form-group">
                                                <input name="password_aspirant" type="password" class="form-control mg-x-auto" placeholder="Contraseña" autocomplete="off"/>
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-aspirant-1">
                                            <div class="form-group pd-l-20">
                                                <input type="checkbox" class="form-check-input" name="terms_conditions">
                                                <label class="form-check-label" for="terms_conditions">Acepta los <a href="#" class="btn_terms_conditions">términos y condiciones</a></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mg-x-0 form-aspirant-2" id="form_aspirant_2">
                                    </div>
                                </div>
                                <div class="row form-aspirant-1">
                                    <button type="button" id="btn_next_aspirants" class="btn btn-primary btn-block mg-t-10 mg-x-50">Siguiente</button>                            
                                </div>
                                <div class="row d-none form-aspirant-2">
                                    <button type="submit" class="btn btn-primary btn-register btn-block mg-t-10 mg-x-50">Enviar</button>
                                </div>
                            </form>
                        </div>
                        {if !$mobile}
                            <div class="d-flex align-items-center justify-content-center wd-410">
                            <div class="wd-fill login-banner{$random_register}"></div>
                        </div>
                        {/if}  
                    </div>
                </div>
            </div>

        </div>

        <div id="modal_admin" class="d-none">
            <div class="bd-0 tx-14">
                <div class="card bd-0">
                    <div class="card-body bd bd-t-0 rounded-bottom-0 pd-x-30">
                        <form id="form_login_admin" method="post" action="{$path_admin}">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label tx-xs-12">Usuario:</label>  
                                        <input id="user_admin" name="user" class="form-control mg-x-auto" type="text" autocomplete="off" autofocus>
                                    </div>
                                </div>                                
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label tx-xs-12">Contraseña:</label>  
                                        <input id="password_admin" name="password" class="form-control mg-x-auto" type="password" autocomplete="off">  
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div id="btn_forgot_admin" class="tx-primary tx-12 d-block mg-t-10 c-pointer">
                                            <i class="fas fa-unlock"></i>
                                            Olvidaste tu contraseña?
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bd bd-t-0 d-flex justify-content-between">
                        <button id="btn_close_admin" class="btn btn-secondary">Cerrar</button>
                        <button id="btn_login_admin" class="btn btn-primary">Ingresar</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal_security_aspirant" class="d-none">
            <div class="bd-0 tx-14">
                <div class="card bd-0">
                    <div class="card-body bd bd-t-0 rounded-bottom-0">
                        <form id="form_security_aspirant" method="post" action="{$path_security_aspirants}">
                            <input type="hidden" id="id_security_aspirant" name="id_security_question">
                            <div class="form-group tx-center">
                                <label id="label_security_aspirant" class="form-control-label tx-center"></label>
                                <input name="value_security_question" class="form-control wd-70p wd-xs-60p mg-x-auto" type="text" autocomplete="off">  
                            </div>  
                        </form>
                    </div>
                    <div class="card-footer bd bd-t-0 d-flex justify-content-between">
                        <button id="btn_security_aspirant" type="button" class="btn btn-primary btn-block wd-40p wd-xs-30p mg-x-auto"> Enviar</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal_forgot_aspirant" class="d-none">
            <div class="bd-0 tx-14">
                <div class="card bd-0">
                    <div class="card-body bd bd-t-0 rounded-bottom-0">
                        <form id="form_forgot_aspirant" method="post" action="{$path_forgot_aspirant}">
                            <div class="form-group tx-center">
                                <label class="form-control-label tx-10 tx-xs-12">Correo:</label>
                                <input id="email_forgot_aspirant" name="email_forgot" class="form-control wd-70p wd-xs-60p mg-x-auto" type="text" autocomplete="off">  
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bd bd-t-0 d-flex justify-content-between">
                        <button id="btn_forgot_confirm_admin" type="button" class="btn btn-primary btn-block wd-40p wd-xs-30p mg-x-auto"> Enviar</button>  
                    </div>
                </div>
            </div>
        </div>

        <div id="modal_terms_conditions" class="d-none">
            <div class="bd-0 tx-14">
                <div class="card bd-0">
                    <div class="card-body bd bd-t-0 rounded-bottom-0 pd-x-30 text-justify">
                        <p>Con el fin de continuar con el diligenciamiento del formulario de hoja de vida debe dar su consentimiento a FET para que realice el tratamiento de sus datos personales, por lo tanto, para autorizar, ingrese al siguiente enlace para ver la <a href="https://www.fet.co/resources/autorizaciontratamientodatospersonales.pdf">Autorización del Tratamiento de Datos Personales</a>.</p>
                    </div>
                </div>
            </div>
        </div>

        <script src="{$RESOURCES}lib/jquery/jquery.js"></script>
        <script src="{$RESOURCES}lib/popperjs/popper.js"></script>
        <script src="{$RESOURCES}lib/bootstrap/js/bootstrap.js"></script>
        <script src="{$RESOURCES}lib/font-awesome/js/all.js"></script>
        <script src="{$RESOURCES}lib/izimodal/js/iziModal.js"></script>
        <script src="{$RESOURCES}lib/izitoast/js/iziToast.js"></script> 
        <script src="{$RESOURCES}lib/jquery-validation/jquery.validate.js"></script>
        <script src="{$RESOURCES}lib/jquery-validation/additional-methods.js"></script>
        <script src="{$RESOURCES}lib/jquery-validation/localization/messages_es.js"></script>        
        <script src="{$RESOURCES}lib/jquery-form/jquery.form.min.js"></script>
        <script src="{$RESOURCES}lib/moment/moment.js"></script>
        <script src="{$RESOURCES}lib/moment/moment-locales.js"></script>
        <script src="{$RESOURCES}lib/malihu-scrollbar/js/jquery.mCustomScrollbar.js"></script>
        <script src="{$RESOURCES}lib/select2/js/select2.full.js"></script>
        <script type="text/javascript">
            var $path_cities_contributors                                       =   '{$path_cities_contributors}';
            var $path_economicsector                                            =   '{$path_economicsector}';
            var $path_biomechanical                                             =   '{$path_biomechanical}';
            var $path_dashboard                                                 =   '{$path_dashboard}';
            var $path_cv                                                        =   '{$path_cv}';
            var $path_security_questions                                        =   '{$path_security_questions}';
            var $path_draw_security_questions                                   =   '{$path_draw_security_questions}';
        </script>
        {if $mobile}
            <script src="{$RESOURCES}js/login/new/mobile.js"></script>
        {else}
            <script src="{$RESOURCES}js/login/new/desktop.js"></script>
        {/if}
    </body>
</html>