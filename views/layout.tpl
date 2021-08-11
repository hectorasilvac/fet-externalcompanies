<!DOCTYPE html>
<html lang="es">
    <head>
        {block name=head}{/block}
    </head>
    <body class="collapsed-menu">
        <div class="br-logo">
            <a href="#">
                <img src="{$RESOURCES}img/logo-dashboard.png" class="wd-150">
            </a>
        </div>
        <div class="br-sideleft overflow-y-auto">
            <div class="br-sideleft-menu">
                <br/>
                {$find = [{$submodule_active|cat:" nav-link"}, {$module_active|cat:" br-menu-link"}]}
                {$repl = ['"nav-link active', '"br-menu-link active']}
                {$menu|replace:$find:$repl}
            </div>
        </div>
        <div class="br-header">
            <div class="br-header-left">
                <div class="navicon-left hidden-md-down">
                    <a id="btnLeftMenu" href="">
                        <i class="fas fa-bars"></i>
                    </a>
                </div>
                <div class="navicon-left hidden-lg-up">
                    <a id="btnLeftMenuMobile" href="">
                        <i class="fas fa-bars"></i>
                    </a>
                </div>
                {if $affiliate eq false}
                <div class="input-group hidden-xs-down wd-280 transition">
                    <input id="searchbox" type="text" class="form-control" placeholder="Grupo/Empresa/Proyecto">
                    <span class="input-group-btn">
                        <button class="btn btn-secondary" type="button"><i class="fa fa-search"></i></button>
                    </span>
                </div>
                {/if}
            </div>
            <div class="br-header-right">
                <nav class="nav">
                    <div class="dropdown">
                        <a href="" class="nav-link pd-x-7 pos-relative" data-toggle="dropdown">
                            <i class="far fa-bell tx-24"></i>
                            <span class="notifications square-8 bg-danger pos-absolute t-15 r-5 rounded-circle d-none-force"></span>
                        </a>
                        <div class="notifications dropdown-menu dropdown-menu-header wd-300 pd-0-force">
                            <div class="d-flex align-items-center justify-content-between pd-y-10 pd-x-20 bd-b bd-gray-200">
                                <label class="tx-12 tx-info tx-uppercase tx-semibold tx-spacing-2 mg-b-0">Notificaciones</label>
                            </div>
                            <div class="media-list" id="notifications_list"></div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="" class="nav-link nav-link-profile" data-toggle="dropdown">
                            <span class="logged-name hidden-md-down">{$name_user}</span>
                            <i class="fas fa-user user-gray"></i>
                            <span class="square-10 bg-success"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-header wd-200">
                            <ul class="list-unstyled user-profile-nav">
                                {if isset($smarty.session.id_user) || isset($smarty.session.id_aspirant) || isset($smarty.session.id_contributor)}
                                <li><a href="{$path_user_edit}"><i class="fas fa-user-edit"></i> Editar Usuario</a></li>
                                {/if}
                                <li><a href="{$path_logout}"><i class="fas fa-power-off"></i> Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
                {if $affiliate eq false}
                <div class="navicon-right">
                    <a id="btnRightMenu" href="" class="pos-relative">
                        <i class="fas fa-cogs user-gray"></i>
                        <span class="square-6 bg-primary rounded-circle"></span>
                    </a>
                </div>
                {/if}
            </div>
        </div>
        {if $affiliate eq false}
        <div class="br-sideright">
            <ul class="nav nav-tabs sidebar-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" role="tab" href="#affiliates"><i class="far fa-user tx-white-20"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" role="tab" href="#attachments"><i class="far fa-folder tx-white-20"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" role="tab" href="#calendar"><i class="far fa-calendar tx-white-20"></i></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" role="tab" href="#settings"><i class="fas fa-user-cog tx-white-20"></i></a>
                </li>
            </ul>
            <div class="tab-content">
                <!--
                <div class="tab-pane pos-absolute a-0 mg-t-60 overflow-y-auto active" id="affiliates" role="tabpanel">
                    <label class="sidebar-label pd-x-25 mg-t-25">Afiliados</label>
                    <div class="contact-list pd-x-10">
                        <a href="" class="contact-list-link new">
                            <div class="d-flex">
                                <div class="pos-relative">
                                    <img src="https://via.placeholder.com/280x280" class="wd-40 rounded-circle" alt="">
                                </div>
                                <div class="contact-person">
                                    <p class="mg-b-0">Sandra Ximena Amaya Pallares</p>
                                    <span class="tx-12 op-5 d-inline-block">Desarrolladora FullStack</span>
                                </div>
                                <span class="tx-info tx-12">1 Medio</span>
                            </div>
                        </a>
                    </div>
                    <div class="contact-list pd-x-10">
                        <a href="" class="contact-list-link new">
                            <div class="d-flex">
                                <div class="pos-relative">
                                    <img src="https://via.placeholder.com/280x280" class="wd-40 rounded-circle" alt="">
                                </div>
                                <div class="contact-person">
                                    <p class="mg-b-0">Angel David Hurtado Franco</p>
                                    <span class="tx-12 op-5 d-inline-block">Desarrolladora FullStack</span>
                                </div>
                                <span class="tx-info tx-12">1 Medio</span>
                            </div>
                        </a>
                    </div>
                    <div class="contact-list pd-x-10">
                        <a href="" class="contact-list-link new">
                            <div class="d-flex">
                                <div class="pos-relative">
                                    <img src="https://via.placeholder.com/280x280" class="wd-40 rounded-circle" alt="">
                                </div>
                                <div class="contact-person">
                                    <p class="mg-b-0">Luis Carlos Muñoz Diaz</p>
                                    <span class="tx-12 op-5 d-inline-block">Desarrolladora FullStack</span>
                                </div>
                                <span class="tx-info tx-12">1 Medio</span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="tab-pane pos-absolute a-0 mg-t-60 overflow-y-auto" id="attachments" role="tabpanel">
                    <label class="sidebar-label pd-x-25 mg-t-25">Archivos Adjuntos Recientes</label>
                    <div class="media-file-list">
                        <div class="media">
                            <div class="pd-10 bg-primary wd-50 ht-60 tx-center d-flex align-items-center justify-content-center">
                                <i class="far fa-file-word tx-white-30"></i>
                            </div>
                            <div class="media-body">
                                <p class="mg-b-0 tx-13">XXXX</p>
                                <p class="mg-b-0 tx-12 op-5">Documento Word</p>
                                <p class="mg-b-0 tx-12 op-5">1.2 MB</p>
                            </div>
                            <a href="" class="more"><i class="fas fa-ellipsis-v tx-12"></i></a>
                        </div>
                        <div class="media mg-t-20">
                            <div class="pd-10 bg-success wd-50 ht-60 tx-center d-flex align-items-center justify-content-center">
                                <i class="far fa-file-excel tx-white-30"></i>
                            </div>
                            <div class="media-body">
                                <p class="mg-b-0 tx-13">XXXX</p>
                                <p class="mg-b-0 tx-12 op-5">Documento Excel</p>
                                <p class="mg-b-0 tx-12 op-5">5.4 MB</p>
                            </div>
                            <a href="" class="more"><i class="fas fa-ellipsis-v tx-12"></i></a>
                        </div>
                        <div class="media mg-t-20">
                            <div class="pd-10 bg-warning wd-50 ht-60 tx-center d-flex align-items-center justify-content-center">
                                <i class="far fa-file-powerpoint tx-white-30"></i>
                            </div>
                            <div class="media-body">
                                <p class="mg-b-0 tx-13">XXXX</p>
                                <p class="mg-b-0 tx-12 op-5">Documento PowerPoint</p>
                                <p class="mg-b-0 tx-12 op-5">3.2 MB</p>
                            </div>
                            <a href="" class="more"><i class="fas fa-ellipsis-v tx-12"></i></a>
                        </div>
                        <div class="media mg-t-20">
                            <div class="pd-10 bg-danger wd-50 ht-60 tx-center d-flex align-items-center justify-content-center">
                                <i class="far fa-file-pdf tx-white-30"></i>
                            </div>
                            <div class="media-body">
                                <p class="mg-b-0 tx-13">XXXX</p>
                                <p class="mg-b-0 tx-12 op-5">Documento PDF</p>
                                <p class="mg-b-0 tx-12 op-5">7.5 MB</p>
                            </div>
                            <a href="" class="more"><i class="fas fa-ellipsis-v tx-12"></i></a>
                        </div>
                        <div class="media mg-t-20">
                            <div class="pd-10 bg-purple wd-50 ht-60 tx-center d-flex align-items-center justify-content-center">
                                <i class="far fa-file-image tx-white-30"></i>
                            </div>
                            <div class="media-body">
                                <p class="mg-b-0 tx-13">XXXX</p>
                                <p class="mg-b-0 tx-12 op-5">JPG Imagen</p>
                                <p class="mg-b-0 tx-12 op-5">4.7 MB</p>
                            </div>
                            <a href="" class="more"><i class="fas fa-ellipsis-v tx-12"></i></a>
                        </div>
                        <div class="media mg-t-20">
                            <div class="pd-10 bg-pink wd-50 ht-60 tx-center d-flex align-items-center justify-content-center">
                                <i class="far fa-file-video tx-white-30"></i>
                            </div>
                            <div class="media-body">
                                <p class="mg-b-0 tx-13">XXXX</p>
                                <p class="mg-b-0 tx-12 op-5">AVI Video</p>
                                <p class="mg-b-0 tx-12 op-5">8.2 MB</p>
                            </div>
                            <a href="" class="more"><i class="fas fa-ellipsis-v tx-12"></i></a>
                        </div>
                    </div>
                </div>
                <div class="tab-pane pos-absolute a-0 mg-t-60 overflow-y-auto" id="calendar" role="tabpanel">
                    <label class="sidebar-label pd-x-25 mg-t-25">Time &amp; Date</label>
                    <div class="pd-x-25">
                        <h2 id="brTime" class="tx-white tx-lato mg-b-5"></h2>
                        <h6 id="brDate" class="tx-white tx-light op-3"></h6>
                    </div>
                    <label class="sidebar-label pd-x-25 mg-t-25">Events Calendar</label>
                    <div class="datepicker sidebar-datepicker"></div>
                    <label class="sidebar-label pd-x-25 mg-t-25">Event Today</label>
                    <div class="pd-x-25">
                        <div class="list-group sidebar-event-list mg-b-20">
                            <div class="list-group-item">
                                <div>
                                    <h6 class="tx-white tx-13 mg-b-5 tx-normal">Roven's 32th Birthday</h6>
                                    <p class="mg-b-0 tx-white tx-12 op-2">2:30PM</p>
                                </div>
                                <a href="" class="more"><i class="icon ion-android-more-vertical tx-18"></i></a>
                            </div>
                            <div class="list-group-item">
                                <div>
                                    <h6 class="tx-white tx-13 mg-b-5 tx-normal">Regular Workout Schedule</h6>
                                    <p class="mg-b-0 tx-white tx-12 op-2">7:30PM</p>
                                </div>
                                <a href="" class="more"><i class="icon ion-android-more-vertical tx-18"></i></a>
                            </div>
                        </div>
                        <a href="" class="btn btn-block btn-outline-secondary tx-uppercase tx-11 tx-spacing-2">+ Add Event</a>
                        <br/>
                    </div>
                </div>
                <div class="tab-pane pos-absolute a-0 mg-t-60 overflow-y-auto" id="settings" role="tabpanel">
                    <label class="sidebar-label pd-x-25 mg-t-25">Quick Settings</label>
                    <div class="pd-y-20 pd-x-25 tx-white">
                        <h6 class="tx-13 tx-normal">Sound Notification</h6>
                        <p class="op-5 tx-13">Play an alert sound everytime there is a new notification.</p>
                        <div class="pos-relative">
                            <input type="checkbox" name="checkbox" class="switch-button" checked>
                        </div>
                    </div>
                    <div class="pd-y-20 pd-x-25 tx-white">
                        <h6 class="tx-13 tx-normal">2 Steps Verification</h6>
                        <p class="op-5 tx-13">Sign in using a two step verification by sending a verification code to your phone.</p>
                        <div class="pos-relative">
                            <input type="checkbox" name="checkbox2" class="switch-button">
                        </div>
                    </div>
                    <div class="pd-y-20 pd-x-25 tx-white">
                        <h6 class="tx-13 tx-normal">Location Services</h6>
                        <p class="op-5 tx-13">Allowing us to access your location</p>
                        <div class="pos-relative">
                            <input type="checkbox" name="checkbox3" class="switch-button">
                        </div>
                    </div>
                    <div class="pd-y-20 pd-x-25 tx-white">
                        <h6 class="tx-13 tx-normal">Newsletter Subscription</h6>
                        <p class="op-5 tx-13">Enables you to send us news and updates send straight to your email.</p>
                        <div class="pos-relative">
                            <input type="checkbox" name="checkbox4" class="switch-button" checked>
                        </div>
                    </div>
                    <div class="pd-y-20 pd-x-25 tx-white">
                        <h6 class="tx-13 tx-normal">Your email</h6>
                        <div class="pos-relative">
                            <input type="email" name="email" class="form-control form-control-inverse transition pd-y-10" value="janedoe@domain.com">
                        </div>
                    </div>
                    <div class="pd-y-20 pd-x-25">
                        <h6 class="tx-13 tx-normal tx-white mg-b-20">More Settings</h6>
                        <a href="" class="btn btn-block btn-outline-secondary tx-uppercase tx-11 tx-spacing-2">Account Settings</a>
                        <a href="" class="btn btn-block btn-outline-secondary tx-uppercase tx-11 tx-spacing-2">Privacy Settings</a>
                    </div>
                </div>-->
            </div>
        </div>
        {/if}
        <div class="br-mainpanel">
            {block name=body}{/block}
        </div>
        <script src="{$RESOURCES}lib/jquery/jquery.js"></script>
        <script src="{$RESOURCES}lib/jquery-ui/js/jquery-ui.js"></script>
        <script src="{$RESOURCES}lib/jquery-form/jquery.form.min.js"></script>
        <script src="{$RESOURCES}lib/popperjs/popper.js"></script>
        <script src="{$RESOURCES}lib/bootstrap/js/bootstrap.js"></script>
        <script src="{$RESOURCES}lib/datatables/js/datatables.min.js"></script>
        <script src="{$RESOURCES}lib/x-editable/js/bootstrap-editable.js"></script>
        <script src="{$RESOURCES}lib/datatables/js/dataTables.scroller.min.js"></script>
        <script src="{$RESOURCES}lib/font-awesome/js/all.js"></script>
        <script src="{$RESOURCES}lib/moment/moment.js"></script>
        <script src="{$RESOURCES}lib/moment/moment-locales.js"></script>
        <script src="{$RESOURCES}lib/switchbutton/js/switchButton.js"></script>
        <script src="{$RESOURCES}lib/malihu-scrollbar/js/jquery.mCustomScrollbar.js"></script>
        <script src="{$RESOURCES}lib/izimodal/js/iziModal.js"></script>
        <script src="{$RESOURCES}lib/izitoast/js/iziToast.js"></script>
        <script src="{$RESOURCES}lib/select2/js/select2.full.js"></script>
        <script src="{$RESOURCES}lib/select2/js/i18n/es.js"></script>   
        <script src="{$RESOURCES}lib/amcharts/core.js"></script>
        <script src="{$RESOURCES}lib/amcharts/charts.js"></script>
        <script src="{$RESOURCES}lib/amcharts/material.js"></script>
        <script src="{$RESOURCES}lib/jquery-validation/jquery.validate.js"></script>
        <script src="{$RESOURCES}lib/jquery-validation/additional-methods.js"></script>
        <script src="{$RESOURCES}lib/jquery-validation/localization/messages_es.js"></script>
        <script src="{$RESOURCES}lib/smart-wizard/js/jquery.smartWizard.js"></script>
        <script src="{$RESOURCES}lib/datepicker/datepicker.js"></script>
        <script src="{$RESOURCES}lib/datepicker/datepicker.es-ES.js"></script>
        <script src="{$RESOURCES}lib/timepicker/dist/wickedpicker.min.js"></script>

        <script type="text/javascript">
            var $path_notifications                                             =   '{$path_notifications}';
        </script>

        <script src="{$RESOURCES}js/trabajandofet.js"></script>
        {block name=scripts}{/block}
    </body>
</html>