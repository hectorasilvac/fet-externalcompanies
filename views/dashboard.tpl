{extends file='../head.tpl'}
{block name='body'}
<div class="br-pagebody mg-t-5 pd-x-30">
    <div class="row mg-t-20">
        <div class="col-xl-4 col-sm-12 mg-t-20">
            <div class="card bd-0 mg-t-10">
                <div class="card-header tx-white bg-yellow">
                    <h6 class="tx-14 pd-t-8 tx-capitalize tx-semibold tx-spacing-1">Selecciona: {if $mobile}<br/>{/if}Grupo / Empresa / Proyecto</h6>
                </div>
                <div class="card-body bd bd-t-0 pd-t-30">
                    <form id="form_project_session" method="post" action="{$path_company_project}">
                        <div class="row mg-b-25">
                            <div class="col-lg-12">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Empresa:</label>
                                    <select class="form-control" id="id_company" name="id_company">
                                    </select>
                                    <input type="hidden" class="d-none" id="text_company" name="text_company">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group pos-relative">
                                    <label class="form-control-label">Proyecto:</label>
                                    <select class="form-control" id="id_project" name="id_project">
                                    </select>
                                    <input type="hidden" class="d-none" id="text_project" name="text_project">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary">Seleccionar</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-sm-12 mg-t-20">
            <div class="card bd-0 mg-t-10">
                <div id="carousel2" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carousel2" data-slide-to="0" class="c-pointer active"></li>
                        <li data-target="#carousel2" data-slide-to="1" class="c-pointer"></li>
                        <li data-target="#carousel2" data-slide-to="2" class="c-pointer"></li>
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <div class="bg-br-primary pd-30 ht-300 pos-relative d-flex align-items-center rounded">
                                <div class="pos-absolute t-15 r-25">
                                    <a href="" class="tx-white-5 hover-info"><i class="icon ion-edit tx-16"></i></a>
                                    <a href="" class="tx-white-5 hover-info mg-l-7"><i class="icon ion-stats-bars tx-20"></i></a>
                                    <a href="" class="tx-white-5 hover-info mg-l-7"><i class="icon ion-gear-a tx-20"></i></a>
                                    <a href="" class="tx-white-5 hover-info mg-l-7"><i class="icon ion-more tx-20"></i></a>
                                </div>
                                <div class="tx-white">
                                    <p class="tx-11 tx-medium tx-mont tx-spacing-2 tx-white-5">Nuevo Módulo: Hojas de Vida!</p>
                                    <h5 class="lh-5 mg-b-20">Ha llegado el momento!</h5>
                                    <h5 class="lh-5 mg-b-20">Con esta nueva implementación podras conocer de primera mano toda la información importante de cada aspirante, afiliado y trabajador.</h5>
                                    <nav class="nav flex-row tx-13">
                                        <a href="" class="tx-white-8 hover-white pd-l-0 pd-r-5">13/10/2020</a>
                                        <!-- <a href="" class="tx-white-8 hover-white pd-x-5">234 Likes</a>
                                        <a href="" class="tx-white-8 hover-white pd-x-5">43 Comments</a> -->
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="bg-info pd-30 ht-300 pos-relative d-flex align-items-center rounded">
                                <div class="pos-absolute t-15 r-25">
                                    <a href="" class="tx-white-5 hover-info"><i class="icon ion-edit tx-16"></i></a>
                                    <a href="" class="tx-white-5 hover-info mg-l-7"><i class="icon ion-stats-bars tx-20"></i></a>
                                    <a href="" class="tx-white-5 hover-info mg-l-7"><i class="icon ion-gear-a tx-20"></i></a>
                                    <a href="" class="tx-white-5 hover-info mg-l-7"><i class="icon ion-more tx-20"></i></a>
                                </div>
                                <div class="tx-white">
                                    <p class="tx-11 tx-medium tx-mont tx-spacing-2 tx-white-5">Nueva Actualización: Cambios de Turno!</p>
                                    <h5 class="lh-5 mg-b-20">Ya no necesitas firmar cada vez que necesites realizar el cambio de turno con algún compañero tuyo, lo puedes hacer de una manera más práctica con nuestro clic rápido.</h5>
                                    <nav class="nav flex-row tx-13">
                                        <a href="" class="tx-white-8 hover-white pd-l-0 pd-r-5">05/10/2020</a>
                                        <!-- <a href="" class="tx-white-8 hover-white pd-x-5">Unpublish</a>
                                        <a href="" class="tx-white-8 hover-white pd-x-5">Delete</a> -->
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="bg-purple pd-30 ht-300 d-flex pos-relative align-items-center rounded">
                                <div class="pos-absolute t-15 r-25">
                                    <a href="" class="tx-white-5 hover-info"><i class="icon ion-edit tx-16"></i></a>
                                    <a href="" class="tx-white-5 hover-info mg-l-7"><i class="icon ion-stats-bars tx-20"></i></a>
                                    <a href="" class="tx-white-5 hover-info mg-l-7"><i class="icon ion-gear-a tx-20"></i></a>
                                    <a href="" class="tx-white-5 hover-info mg-l-7"><i class="icon ion-more tx-20"></i></a>
                                </div>
                                <div class="tx-white">
                                    <p class="tx-11 tx-medium tx-mont tx-spacing-2 tx-white-5">Nuevo módulo en cocción</p>
                                    <h5 class="lh-5 mg-b-20">Carnets se avecina, esperanos muy pronto!</h5>
                                    <h5 class="lh-5 mg-b-20">Con este módulo ya no necesitaras solicitar presencialmente tu carnet ya sea por primera vez o por perdida, recuerda tener una foto de perfil adecuada para el mismo!</h5>
                                    <nav class="nav flex-row tx-13">
                                        <a href="" class="tx-white-8 hover-white pd-l-0 pd-r-5">14/09/2020</a>
                                        <!-- <a href="" class="tx-white-8 hover-white pd-x-5">Unpublish</a>
                                        <a href="" class="tx-white-8 hover-white pd-x-5">Delete</a> -->
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--
        <div class="col-xl-4 col-sm-12">
            <div class="row mg-t-20">
                <div class="col-sm-6 col-xl-12 mg-t-20 mg-sm-t-10">
                    <div class="bg-teal rounded overflow-hidden">
                        <div class="pd-25 d-flex align-items-center">
                            <i class="fas fa-life-ring tx-60 lh-0 tx-white op-7"></i>
                            <div class="mg-l-20">
                                <p class="tx-9 tx-spacing-1 tx-mont tx-medium tx-white-8 mg-b-10">Soporte</p>
                                <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1"># 0</p>
                                <span class="tx-11 tx-roboto tx-white-6">100%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-12 mg-t-20 mg-sm-t-15">
                    <div class="bg-danger rounded overflow-hidden">
                        <div class="pd-25 d-flex align-items-center">
                            <i class="fas fa-print tx-60 lh-0 tx-white op-7"></i>
                            <div class="mg-l-20">
                                <p class="tx-9 tx-spacing-1 tx-mont tx-medium tx-white-8 mg-b-10">Toner</p>
                                <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1"># 0</p>
                                <span class="tx-11 tx-roboto tx-white-6">100%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-12 mg-t-20 mg-sm-t-15">
                    <div class="bg-primary rounded overflow-hidden">
                        <div class="pd-25 d-flex align-items-center">
                            <i class="fas fa-paint-brush tx-60 lh-0 tx-white op-7"></i>
                            <div class="mg-l-20">
                                <p class="tx-9 tx-spacing-1 tx-mont tx-medium tx-white-8 mg-b-10">Diseño</p>
                                <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1"># 0</p>
                                <span class="tx-11 tx-roboto tx-white-6">100%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-12 mg-t-20 mg-sm-t-15">
                    <div class="bg-yellow rounded overflow-hidden">
                        <div class="pd-25 d-flex align-items-center">
                            <i class="fas fa-code tx-60 lh-0 tx-white op-7"></i>
                            <div class="mg-l-20">
                                <p class="tx-9 tx-spacing-1 tx-mont tx-medium tx-white-8 mg-b-10">Desarrollo</p>
                                <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1"># 0</p>
                                <span class="tx-11 tx-roboto tx-white-6">100%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
    </div>
</div>

<div id="modal_companies" class="d-none bg-gray-200">
    <div class="card card-body bg-gray-200 pre-scrollable">
        <div class="row row-sm mg-t-10">
            <div class="col-sm-6 col-lg-4">
                <div class="bg-white rounded shadow-base overflow-hidden mg-b-25">
                    <div class="pd-x-10 pd-y-30 d-flex align-items-center">
                        <div class="mg-l-20">
                            <p class="tx-22 tx-inverse tx-lato tx-black mg-b-0 lh-1">Nombre de proyecto 1</p>
                            <span class="tx-12 tx-roboto tx-gray-600">24% higher than yesterday</span>
                            <div class="progress mg-t-20">
                                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100" style="width: 30%;"></div>
                                <div class="progress-bar bg-br-primary" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 20%;"></div>
                                <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 50%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="bg-white rounded shadow-base overflow-hidden mg-b-25">
                    <div class="pd-x-10 pd-y-30 d-flex align-items-center">
                        <div class="mg-l-20">
                            <p class="tx-22 tx-inverse tx-lato tx-black mg-b-0 lh-1">Nombre de proyecto 1</p>
                            <span class="tx-12 tx-roboto tx-gray-600">24% higher than yesterday</span>
                            <div class="progress mg-t-20">
                                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-br-primary" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="bg-white rounded shadow-base overflow-hidden mg-b-25">
                    <div class="pd-x-10 pd-y-30 d-flex align-items-center">
                        <div class="mg-l-20">
                            <p class="tx-22 tx-inverse tx-lato tx-black mg-b-0 lh-1">Nombre de proyecto 1</p>
                            <span class="tx-12 tx-roboto tx-gray-600">24% higher than yesterday</span>
                            <div class="progress mg-t-20">
                                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-br-primary" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="bg-white rounded shadow-base overflow-hidden mg-b-25">
                    <div class="pd-x-10 pd-y-30 d-flex align-items-center">
                        <div class="mg-l-20">
                            <p class="tx-22 tx-inverse tx-lato tx-black mg-b-0 lh-1">Nombre de proyecto 1</p>
                            <span class="tx-12 tx-roboto tx-gray-600">24% higher than yesterday</span>
                            <div class="progress mg-t-20">
                                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-br-primary" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="bg-white rounded shadow-base overflow-hidden mg-b-25">
                    <div class="pd-x-10 pd-y-30 d-flex align-items-center">
                        <div class="mg-l-20">
                            <p class="tx-22 tx-inverse tx-lato tx-black mg-b-0 lh-1">Nombre de proyecto 1</p>
                            <span class="tx-12 tx-roboto tx-gray-600">24% higher than yesterday</span>
                            <div class="progress mg-t-20">
                                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-br-primary" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="bg-white rounded shadow-base overflow-hidden mg-b-25">
                    <div class="pd-x-10 pd-y-30 d-flex align-items-center">
                        <div class="mg-l-20">
                            <p class="tx-22 tx-inverse tx-lato tx-black mg-b-0 lh-1">Nombre de proyecto 1</p>
                            <span class="tx-12 tx-roboto tx-gray-600">24% higher than yesterday</span>
                            <div class="progress mg-t-20">
                                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-br-primary" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="bg-white rounded shadow-base overflow-hidden mg-b-25">
                    <div class="pd-x-10 pd-y-30 d-flex align-items-center">
                        <div class="mg-l-20">
                            <p class="tx-22 tx-inverse tx-lato tx-black mg-b-0 lh-1">Nombre de proyecto 1</p>
                            <span class="tx-12 tx-roboto tx-gray-600">24% higher than yesterday</span>
                            <div class="progress mg-t-20">
                                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-br-primary" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="br-footer">
    <div class="footer-left">
        <div class="mg-b-2 tx-gray-800">{$COPYRIGHT}</div>
    </div>
</footer>
{/block}
{block name='scripts'}
    <script type="text/javascript">
        var $path_companies                                                     =   '{$path_companies}';
        var $path_projects                                                      =   '{$path_projects}';
        var user_company                                                        =   '{$user_company}' 
        var user_project                                                        =   '{$user_project}' 
    </script>
    <!-- {if $mobile}
        <script src="{$RESOURCES}js/mobile/dashboard.js"></script>
    {else} -->
        <script src="{$RESOURCES}js/desktop/dashboard.js"></script>
    <!-- {/if} -->
{/block}