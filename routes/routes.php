<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// POR DEFECTO

$route['default_controller']                                                    =   'login_controller/view';

$route['404_override']                                                          =   '';

$route['translate_uri_dashes']                                                  =   FALSE;

// LOGIN

$route['login']                                                                 =   'login_controller/view';

$route['loginsss']                                                              =   'login_controller/view';

$route['login/admin']                                                           =   'login_controller/login_admin';

$route['login/forgotadmin']                                                     =   'login_controller/forgot_admin';

$route['login/forgotuser']                                                      =   'login_controller/forgot_user';

$route['login/forgotaspirant']                                                  =   'login_controller/forgot_aspirant';

$route['login/security/questions']                                              =   'login_controller/security_questions';

$route['login/security/questions/inputs']                                       =   'login_controller/security_questions_inputs';

$route['login/aspirants']                                                       =   'login_controller/login_aspirants';

$route['login/register/aspirants']                                              =   'login_controller/register_aspirants';

$route['login/security/aspirants']                                              =   'login_controller/security_aspirants';

$route['login/contributors']                                                    =   'login_controller/login_contributors';

$route['login/cities']                                                          =   'login_controller/cities';

$route['login/register/contributors']                                           =   'login_controller/register_contributors';

$route['login/security/contributors']                                           =   'login_controller/security_contributors';

$route['login/economicsector']                                                  =   'login_controller/economic_sector';

$route['login/workers']                                                         =   'login_controller/login_workers';


// EDITAR CLAVE Y CERRAR SESIÓN
$route['useredit']                                                              =   'trabajandofet_controller/user_edit';

$route['useredit/edit']                                                         =   'trabajandofet_controller/edit';

$route['logout']                                                                =   'trabajandofet_controller/logout';


// MENÚ
$route['dashboard']                                                             =   'dashboard_controller/view';

$route['dashboard/sessionphoto/(:any)']                                         =   'dashboard_controller/session_photo/$1';

$route['dashboard/profileupdate']                                               =   'dashboard_controller/profile_update';

$route['dashboard/supportscv']                                                  =   'dashboard_controller/supports_cv';

$route['dashboard/supportsfiles']                                               =   'dashboard_controller/supports_files';

$route['dashboard/news']                                                        =   'dashboard_controller/news';

$route['dashboard/requestcard']                                                 =   'dashboard_controller/request_card';

$route['dashboard/companies']                                                   =   'dashboard_controller/companies';

$route['dashboard/projects']                                                    =   'dashboard_controller/projects';

$route['dashboard/companyproject']                                              =   'dashboard_controller/company_project';


// ACCIONES
$route['actions']                                                               =   'actions_controller/view';

$route['actions/datatable']                                                     =   'actions_controller/datatable';

$route['actions/add']                                                           =   'actions_controller/add';

$route['actions/trace']                                                         =   'actions_controller/trace';

$route['actions/exportxlsx']                                                    =   'actions_controller/export_xlsx';


// MODULOS
$route['modules']                                                               =   'modules_controller/view';

$route['modules/datatable']                                                     =   'modules_controller/datatable';

$route['modules/add']                                                           =   'modules_controller/add';

$route['modules/edit']                                                          =   'modules_controller/edit';

$route['modules/userspermissions']                                              =   'modules_controller/users_permissions';

$route['modules/trace']                                                         =   'modules_controller/trace';

$route['modules/exportxlsx']                                                    =   'modules_controller/export_xlsx';


// SUBMODULOS
$route['submodules']                                                            =   'submodules_controller/view';

$route['submodules/datatable']                                                  =   'submodules_controller/datatable';

$route['submodules/add']                                                        =   'submodules_controller/add';

$route['submodules/updatestateaction']                                          =   'submodules_controller/update_state_action';

$route['submodules/updateactionsall']                                           =   'submodules_controller/update_actions_all';

$route['submodules/edit']                                                       =   'submodules_controller/edit';

$route['submodules/actions']                                                    =   'submodules_controller/actions';

$route['submodules/change']                                                     =   'submodules_controller/change';

$route['submodules/editchange']                                                 =   'submodules_controller/editchange';

$route['submodules/trace']                                                      =   'submodules_controller/trace';

$route['submodules/exportxlsx']                                                 =   'submodules_controller/export_xlsx';

$route['submodules/modules/select']                                             =   'submodules_controller/modules';


// ROLES
$route['roles']                                                                 =   'roles_controller/view';

$route['roles/datatable']                                                       =   'roles_controller/datatable';

$route['roles/add']                                                             =   'roles_controller/add';

$route['roles/edit']                                                            =   'roles_controller/edit';

$route['roles/udrop']                                                           =   'roles_controller/udrop';

$route['roles/trace']                                                           =   'roles_controller/trace';

$route['roles/exportxlsx']                                                      =   'roles_controller/export_xlsx';


// PERMISOS
$route['permissions']                                                           =   'permissions_controller/view';

$route['permissions/datatable']                                                 =   'permissions_controller/datatable';

$route['permissions/actions']                                                   =   'permissions_controller/actions';

$route['permissions/add']                                                       =   'permissions_controller/add';

$route['permissions/drop']                                                      =   'permissions_controller/drop';

$route['permissions/exportxlsx']                                                =   'permissions_controller/export_xlsx';

$route['permissions/submodules']                                                =   'permissions_controller/submodules';

$route['permissions/roles']                                                     =   'permissions_controller/roles';

$route['permissions/trace']                                                     =   'permissions_controller/trace';


// USUARIOS
$route['users']                                                                 =   'users_controller/view';

$route['users/datatable']                                                       =   'users_controller/datatable';

$route['users/add']                                                             =   'users_controller/add';

$route['users/edit']                                                            =   'users_controller/edit';

$route['users/userflags']                                                       =   'users_controller/user_flags';

$route['users/editflags']                                                       =   'users_controller/edit_flags';

$route['users/udrop']                                                           =   'users_controller/udrop';

$route['users/display']                                                         =   'users_controller/display';

$route['users/trace']                                                           =   'users_controller/trace';

$route['users/roles']                                                           =   'users_controller/roles';

$route['users/aspirants']                                                       =   'users_controller/aspirants';

$route['users/exportxlsx']                                                      =   'users_controller/export_xlsx';


// CONTROL CAMBIOS DE TURNO
$route['shiftchange']                                                           =   'shiftchange_controller/view';

$route['shiftchange/datatable']                                                 =   'shiftchange_controller/datatable';

$route['shiftchange/workers']                                                	=   'shiftchange_controller/workers';

$route['shiftchange/coordinators']                                              =   'shiftchange_controller/coordinators';

$route['shiftchange/add']                                                       =   'shiftchange_controller/add';

$route['shiftchange/detail']                                                    =   'shiftchange_controller/detail';

$route['shiftchange/signature/(:any)']                                          =   'shiftchange_controller/file_signature/$1';

$route['shiftchange/assign']                                                    =   'shiftchange_controller/assign';

$route['shiftchange/mailreplacement']                                           =   'shiftchange_controller/mail_replacement';

$route['shiftchange/changecoordinator']                                         =   'shiftchange_controller/change_coordinator';

$route['shiftchange/udrop']                                                     =   'shiftchange_controller/udrop';

$route['shiftchange/trace']                                                     =   'shiftchange_controller/trace';

$route['shiftchange/exportxlsx']                                                =   'shiftchange_controller/export_xlsx';


// VENTANA DE ALERTA
$route['scblock']                                                               =   'scblock_controller/view';


//ENCUESTA RIESGO BIOMECANICO
$route['biomechanicalform']                                                     =   'biomechanicalform_controller/view';

$route['biomechanicalform/add']                                                 =   'biomechanicalform_controller/add';


// EMPRESAS EXTERNAS

$route['externalcompanies']                                                     =   'externalcompanies_controller/view';

$route['externalcompanies/datatable']                                           =   'externalcompanies_controller/datatable';

$route['externalcompanies/add']                                                 =   'externalcompanies_controller/add';

$route['externalcompanies/edit']                                                =   'externalcompanies_controller/edit';

$route['externalcompanies/udrop']                                               =   'externalcompanies_controller/udrop';

$route['externalcompanies/trace']                                               =   'externalcompanies_controller/trace';

$route['externalcompanies/detail']                                             =   'externalcompanies_controller/detail';

$route['externalcompanies/exportxlsx']                                          =   'externalcompanies_controller/export_xlsx';

$route['externalcompanies/countries']                                           =   'externalcompanies_controller/countries';

$route['externalcompanies/departments']                                         =   'externalcompanies_controller/departments';

$route['externalcompanies/cities']                                              =   'externalcompanies_controller/cities';

$route['externalcompanies/affiliatedworkers']                                   =   'externalcompanies_controller/affiliated_workers';


// ENTIDADES BANCARIAS

$route['bankentities']                                                          =   'bankentities_controller/view';

$route['bankentities/datatable']                                                =   'bankentities_controller/datatable';

$route['bankentities/add']                                                      =   'bankentities_controller/add';

$route['bankentities/edit']                                                     =   'bankentities_controller/edit';

$route['bankentities/affiliatedworkers']                                        =   'bankentities_controller/affiliated_workers';

$route['bankentities/udrop']                                                    =   'bankentities_controller/udrop';

$route['bankentities/trace']                                                    =   'bankentities_controller/trace';

$route['bankentities/detail']                                                   =   'bankentities_controller/detail';

$route['bankentities/exportxlsx']                                               =   'bankentities_controller/export_xlsx';

// EXTENSIONES

$route['extensions']                                                            =   'extensions_controller/view';

$route['extensions/datatable']                                                  =   'extensions_controller/datatable';

$route['extensions/add']                                                        =   'extensions_controller/add';

$route['extensions/edit']                                                       =   'extensions_controller/edit';

$route['extensions/detail']                                                     =   'extensions_controller/detail';

$route['extensions/userflags']                                                  =   'extensions_controller/user_flags';

$route['extensions/drop']                                                       =   'extensions_controller/drop';

$route['extensions/trace']                                                      =   'extensions_controller/trace';

$route['extensions/workers']                                                    =   'extensions_controller/workers';

$route['extensions/directory']                                                      =   'extensions_controller/directory';

$route['extensions/telephones']                                                 =   'extensions_controller/telephones';

$route['extensions/cellphones']                                                 =   'extensions_controller/cellphones';

$route['extensions/exportxlsx']                                                 =   'extensions_controller/export_xlsx';

$route['extensions/exportpdf']                                                  =   'extensions_controller/export_pdf';