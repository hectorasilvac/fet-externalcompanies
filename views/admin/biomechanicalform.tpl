{extends file='../head.tpl'}
{block name='body'}
<div class="welcome-biomechanicalform1 d-flex justify-content-center" id="welcome_biomechanical">
    {if $mobile}
    <div class="text-center tx-22 pd-t-20">
        <div class="m-auto">
            <span class="font-weight-bold">Hola!</span><br>
            <span class="font-weight-bold font-italic tx-40" style="color: #2b60b0">{$worker_name}</span>
            <span class="font-weight-bold d-block mt-2">Bienvenid@</span>
            <span class="">A la encuesta de Riesgos Biomecánicos</span><br>
            <button id="btn_show_form" class="btn btn-primary mt-4 pd-x-20 font-weight-bold">Ingresar</button>
        </div>
    </div>
    {else}
    <div class="text-center w-40 ml-30p mt-13p">
        <div class="m-auto">
            <span class="tx-50 font-weight-bold">Hola!</span><br>
            <span class="tx-70 font-weight-bold font-italic" style="color: #2b60b0">{$worker_name}</span><br><br>
            <span class="tx-40 font-weight-bold">Bienvenid@</span><br>
            <span class="tx-30">A la encuesta de Riesgos Biomecánicos</span><br>
            
            <button id="btn_show_form" class="btn btn-primary mt-5 pd-x-30 font-weight-bold tx-20">Ingresar</button>
        </div>
    </div>
    {/if}
</div>
<div class="d-none" id="biomechanical">
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
            <div class="mn-ht-120 panel">
                <h6 class="tx-gray-800 tx-bold tx-14 mg-b-25">{$submodule_layout}</h6>
                {if $act_view}
                <form id="form_add" method="post" action="{$path_add}">
                    <div class="row mg-b-25">
                        <div class="col-lg-12">
                            <div class="form-layout">
                                <div class="section-bio br-section-wrapper">
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label mg-b-15">Área de Trabajo:</label>
                                            <select class="form-control" id="axa_3" name="axa_3">
                                                <option value="01">ADMINISTRATIVO</option>
                                                <option value="02">ASISTENCIAL</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-b-25">
                        <div class="col-lg-12">
                            <div class="form-layout">
                                <div class="section-bio br-section-wrapper">
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label tx-bold">¿Hace cuanto tiempo trabaja para esta compañía?</label>
                                            <div class="row mg-t-10">
                                                <div class="col-lg-2">
                                                    <label class="rdiobox">
                                                        <input name="axa_4" value="A" type="radio">
                                                        <span>Menos de 2 años</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_4" value="B" type="radio">
                                                        <span>Entre 2 y 5 años</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_4" value="C" type="radio">
                                                        <span>Entre 6 y 10 años</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_4" value="D" type="radio">
                                                        <span>Entre 11 y 15 años</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_4" value="E" type="radio">
                                                        <span>Mas de 15 años</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-b-25">
                        <div class="col-lg-12">
                            <div class="form-layout">
                                <div class="section-bio br-section-wrapper">
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label tx-bold">Género:</label>
                                            <select class="form-control" id="axa_5" name="axa_5">
                                                <option value="A">MASCULINO</option>
                                                <option value="B">FEMENINO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-b-25">
                        <div class="col-lg-12">
                            <div class="form-layout">
                                <div class="section-bio br-section-wrapper">
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label tx-bold">Edad</label>
                                            <div class="row mg-t-10">
                                                <div class="col-lg-2">
                                                    <label class="rdiobox">
                                                        <input name="axa_6" value="A" type="radio">
                                                        <span>Menor de 25 años</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_6" value="B" type="radio">
                                                        <span>Entre 25 y 35 años</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_6" value="C" type="radio">
                                                        <span>Entre 36 y 45 años</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_6" value="D" type="radio">
                                                        <span>Entre 46 y 55 años</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_6" value="E" type="radio">
                                                        <span>Mayor de 55 años</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-b-25">
                        <div class="col-lg-12">
                            <div class="form-layout">
                                <div class="section-bio br-section-wrapper">
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label tx-bold">En esta Empresa ha presentado algún accidente de trabajo?</label>
                                            <div class="row mg-t-10">
                                                <div class="col-lg-2">
                                                    <label class="rdiobox">
                                                        <input name="axa_7" value="A" type="radio">
                                                        <span>SI</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_7" value="B" type="radio">
                                                        <span>NO</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-b-25">
                        <div class="col-lg-12">
                            <div class="form-layout">
                                <div class="section-bio br-section-wrapper disabled-biomechanical" id="axa_8">
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label tx-bold">Cual fué la parte afectada?</label>
                                            <div class="row mg-t-10">
                                                <div class="col-lg-3">
                                                    <label class="ckbox">
                                                        <input type="checkbox" class="axa_8" name="axa_8[]" value="A" disabled><span>Cabeza</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                                    <label class="ckbox">
                                                        <input type="checkbox" class="axa_8" name="axa_8[]" value="B" disabled><span>Brazos</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                                    <label class="ckbox">
                                                       <input type="checkbox" class="axa_8" name="axa_8[]" value="C" disabled><span>Columna</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                                    <label class="ckbox">
                                                        <input type="checkbox" class="axa_8" name="axa_8[]" value="D" disabled><span>Piernas</span>
                                                    </label>
                                                </div>
                                            </div>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-b-25">
                        <div class="col-lg-12">
                            <div class="form-layout">
                                <div class="section-bio br-section-wrapper">
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label tx-bold">Qué mano utiliza con frecuencia para realizar su trabajo?</label>
                                            <div class="row mg-t-10">
                                                <div class="col-lg-2">
                                                    <label class="rdiobox">
                                                        <input name="axa_9" value="A" type="radio">
                                                        <span>Derecha</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_9" value="B" type="radio">
                                                        <span>Izquierda</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-b-25">
                        <div class="col-lg-12">
                            <div class="form-layout">
                                <div class="section-bio br-section-wrapper">
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label mg-b-15">Cúal es su peso actual? (Klgs):</label>
                                            <input class="form-control" type="number" min="1" id="axa_10" name="axa_10" placeholder="Ingresa tu peso actual" autocomplete="false">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label mg-b-15">Cúal es su estatura? (cms):</label>
                                            <input class="form-control" type="number" min="1" id="axa_11" name="axa_11" placeholder="Ingresa tu estatura actual" autocomplete="false">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-b-25">
                        <div class="col-lg-12">
                            <div class="form-layout">
                                <div class="section-bio br-section-wrapper">
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label tx-bold">Le han diagnosticado alguna enfermedad laboral?</label>
                                            <div class="row mg-t-10">
                                                <div class="col-lg-2">
                                                    <label class="rdiobox">
                                                        <input name="axa_12" value="A" type="radio">
                                                        <span>SI</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_12" value="B" type="radio">
                                                        <span>NO</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label">Cúal:</label>
                                            <input class="form-control" type="text" id="axa_13" name="axa_13" placeholder="Enfermedad actual" autocomplete="false" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-b-25">
                        <div class="col-lg-12">
                            <div class="form-layout">
                                <div class="section-bio br-section-wrapper">
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <label class="form-control-label tx-danger text-justify tx-bold">Realiza ejercicios de gimnasia laboral durante sus pausas activas?</label>
                                    </div>
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label tx-bold">Existe un programa de gimnasia laboral establecido por la compañía?</label>
                                            <div class="row mg-t-10">
                                                <div class="col-lg-2">
                                                    <label class="rdiobox">
                                                        <input name="axa_14" value="A" type="radio">
                                                        <span>SI</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_14" value="B" type="radio">
                                                        <span>NO</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label tx-bold">Practica alguna rutina de gimnasia laboral en sus descansos?</label>
                                            <div class="row mg-t-10">
                                                <div class="col-lg-2">
                                                    <label class="rdiobox">
                                                        <input name="axa_15" value="A" type="radio">
                                                        <span>SI</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_15" value="B" type="radio">
                                                        <span>NO</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative disabled-biomechanical" id="axa_16">
                                            <label class="form-control-label tx-bold">En que jornada?</label>
                                            <div class="row mg-t-10">
                                                <div class="col-lg-2">
                                                    <label class="rdiobox">
                                                        <input name="axa_16" value="A" type="radio" disabled>
                                                        <span>AM</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_16" value="B" type="radio" disabled>
                                                        <span>PM</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-b-25">
                        <div class="col-lg-12">
                            <div class="form-layout">
                                <div class="section-bio br-section-wrapper">
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <label class="form-control-label tx-danger text-justify tx-bold">Los descansos referidos, deben ser diferentes a la hora del almuerzo, como por ejemplo: tomar un break, pausas activas, tomar onces o meriendas entre otros.</label>
                                    </div>
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label tx-bold">Cuántos descansos tiene durante su jornada laboral?</label>
                                            <select class="form-control" id="axa_17" name="axa_17">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-b-25">
                        <div class="col-lg-12">
                            <div class="form-layout">
                                <div class="section-bio br-section-wrapper">
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <label class="form-control-label tx-bold">Alguna vez un Médico o un Profesional de la Salud la ha diagnosticado alguna de las siguientes enfermedades?</label>
                                    </div>
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <th class="tx-center">
                                                        <span class="tx-14">ENFERMEDADES</span>
                                                    </th>
                                                    <th>SI</th>
                                                    <th>NO</th>
                                                </tr>
                                                <tr>
                                                    <td>Espasmos musculares</td>
                                                    <td class="tx-center">
                                                        <label class="rdiobox">
                                                            <input name="axa_18" value="A" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_18" value="B" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Artritis, osteoporosis, osteoartritis o gota</td>
                                                    <td class="tx-center">
                                                        <label class="rdiobox">
                                                            <input name="axa_19" value="A" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_19" value="B" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Enfermedad del túnel del carpo</td>
                                                    <td class="tx-center">
                                                        <label class="rdiobox">
                                                            <input name="axa_20" value="A" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_20" value="B" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Epicondilitis</td>
                                                    <td class="tx-center">
                                                        <label class="rdiobox">
                                                            <input name="axa_21" value="A" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_21" value="B" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Síndrome manguito rotador o tendinitis de hombro</td>
                                                    <td class="tx-center">
                                                        <label class="rdiobox">
                                                            <input name="axa_22" value="A" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_22" value="B" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Escoliosis o deformidades de columna</td>
                                                    <td class="tx-center">
                                                        <label class="rdiobox">
                                                            <input name="axa_23" value="A" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_23" value="B" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Lumbalgia</td>
                                                    <td class="tx-center">
                                                        <label class="rdiobox">
                                                            <input name="axa_24" value="A" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_24" value="B" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Hernia discal</td>
                                                    <td class="tx-center">
                                                        <label class="rdiobox">
                                                            <input name="axa_25" value="A" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_25" value="B" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tendinitis</td>
                                                    <td class="tx-center">
                                                        <label class="rdiobox">
                                                            <input name="axa_26" value="A" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_26" value="B" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-b-25">
                        <div class="col-lg-12">
                            <div class="form-layout">
                                <div class="section-bio br-section-wrapper">
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <label class="form-control-label tx-bold">Usted practica alguna de las siguientes actividades extralaborales por lo menos tres (3) veces a la semana?</label>
                                    </div>
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <th class="tx-center">
                                                        <span class="tx-14">ACTIVIDADES EXTRALABORALES</span>
                                                    </th>
                                                    <th>SI</th>
                                                    <th>NO</th>
                                                </tr>
                                                <tr>
                                                    <td>Oficios domésticos</td>
                                                    <td class="tx-center">
                                                        <label class="rdiobox">
                                                            <input name="axa_27" value="A" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_27" value="B" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Elaboración artesanías, interpretación de instrumentos musicales</td>
                                                    <td class="tx-center">
                                                        <label class="rdiobox">
                                                            <input name="axa_28" value="A" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_28" value="B" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Actividades deportivas</td>
                                                    <td class="tx-center">
                                                        <label class="rdiobox">
                                                            <input name="axa_29" value="A" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_29" value="B" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Digitación en computador o utilización de dispositivos móviles</td>
                                                    <td class="tx-center">
                                                        <label class="rdiobox">
                                                            <input name="axa_30" value="A" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_30" value="B" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Actividades que le generen un ingreso adicional</td>
                                                    <td class="tx-center">
                                                        <label class="rdiobox">
                                                            <input name="axa_31" value="A" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_31" value="B" type="radio">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-b-25">
                        <div class="col-lg-12">
                            <div class="form-layout">
                                <div class="section-bio br-section-wrapper">
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <label class="form-control-label tx-bold">Seleccione el segmento corporal en el cual ha presentado molestias como dolor, sensación de adormecimiento, corrientazo o quemadura.</label>
                                    </div>
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <img src="{$RESOURCES}img/biomechanicalform/body_biomechanical.png" class="wd-100p">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="form-control-label tx-bold">En cúal parte de cuerpo ha presentado algún tipo de molestia?</label>
                                                <br/>
                                                <br/>
                                                <label class="ckbox">
                                                    <input type="checkbox" class="axa_32" name="axa_32[]" value="A"><span>1. Columna cervical</span>
                                                </label>
                                                <br/>
                                                <label class="ckbox">
                                                    <input type="checkbox" class="axa_32" name="axa_32[]" value="B"><span>2. -Columna dorsal</span>
                                                </label>
                                                <br/>
                                                <label class="ckbox">
                                                    <input type="checkbox" class="axa_32" name="axa_32[]" value="C"><span>3. Hombro derecho</span>
                                                </label>
                                                <br/>
                                                <label class="ckbox">
                                                    <input type="checkbox" class="axa_32" name="axa_32[]" value="D"><span>4. Hombro izquierdo</span>
                                                </label>
                                                <br/>
                                                <label class="ckbox">
                                                    <input type="checkbox" class="axa_32" name="axa_32[]" value="E"><span>5. Brazo derecho</span>
                                                </label>
                                                <br/>
                                                <label class="ckbox">
                                                    <input type="checkbox" class="axa_32" name="axa_32[]" value="F"><span>6. Brazo izquierdo</span>
                                                </label>
                                                <br/>
                                                <label class="ckbox">
                                                    <input type="checkbox" class="axa_32" name="axa_32[]" value="G"><span>7. Codo derecho</span>
                                                </label>
                                                <br/>
                                                <label class="ckbox">
                                                    <input type="checkbox" class="axa_32" name="axa_32[]" value="H"><span>8. Codo Izquierdo</span>
                                                </label>
                                                <br/>
                                                <label class="ckbox">
                                                    <input type="checkbox" class="axa_32" name="axa_32[]" value="I"><span>9. Antebrazo derecho</span>
                                                </label>
                                                <br/>
                                                <label class="ckbox">
                                                    <input type="checkbox" class="axa_32" name="axa_32[]" value="J"><span>10. Antebrazo izquierdo</span>
                                                </label>
                                                <br/>
                                                <label class="ckbox">
                                                    <input type="checkbox" class="axa_32" name="axa_32[]" value="K"><span>11. Muñeca y mano derecha</span>
                                                </label>
                                                <br/>
                                                <label class="ckbox">
                                                    <input type="checkbox" class="axa_32" name="axa_32[]" value="L"><span>12. Muñeca y mano izquierda</span>
                                                </label>
                                                <br/>
                                                <label class="ckbox">
                                                    <input type="checkbox" class="axa_32" name="axa_32[]" value="M"><span>13. Columna lumbar</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <label class="form-control-label tx-danger text-justify tx-bold">Si no ha presentado síntomas en ninguna de las partes indicadas en el gráfico anterior, vaya al final de la encuesta para diligenciar los datos personales, de lo contrario continúe con las siguientes preguntas.</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-b-25">
                        <div class="col-lg-12">
                            <div class="form-layout">
                                <div class="section-bio br-section-wrapper disabled-biomechanical" id="axa_33">
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label tx-bold">Cuándo fué la primera vez que aparecieron los síntomas?</label>
                                            <div class="row mg-t-10">
                                                <div class="col-lg-2">
                                                    <label class="rdiobox">
                                                        <input name="axa_33" value="A" type="radio" disabled>
                                                        <span>Menos de 1 mes</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_33" value="B" type="radio" disabled>
                                                        <span>Entre 1 y 6 meses</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_33" value="C" type="radio" disabled>
                                                        <span>Entre 6 meses y 1 año</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_33" value="D" type="radio" disabled>
                                                        <span>Mas de 1 año</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-b-25">
                        <div class="col-lg-12">
                            <div class="form-layout">
                                <div class="section-bio br-section-wrapper disabled-biomechanical" id="axa_34_35">
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <th class="tx-center">
                                                        <span class="tx-14">PREGUNTA</span>
                                                    </th>
                                                    <th>SI</th>
                                                    <th>NO</th>
                                                </tr>
                                                <tr>
                                                    <td>Ha necesitado cambiar de puesto de trabajo o la forma de realizar su trabajo por estos síntomas?</td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_34" value="A" type="radio" disabled>
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_34" value="B" type="radio" disabled>
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Ha tenido molestias en los últimos doce (12) meses?</td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_35" value="A" type="radio" disabled>
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label class="rdiobox">
                                                            <input name="axa_35" value="B" type="radio" disabled>
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <label class="form-control-label tx-danger text-justify tx-bold">SI respondió NO a la pregunta anterior no debe contestar mas, vaya al final de la encuesta y oprima Enviar Encuesta, de lo contrario continúe con las siguientes preguntas</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-b-25">
                        <div class="col-lg-12">
                            <div class="form-layout">
                                <div class="section-bio br-section-wrapper disabled-biomechanical" id="axa_final">
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label tx-bold">Califique las molestias entre 1 (Suave) y 5 (Muy fuerte)</label>
                                            <div class="row mg-t-10">
                                                <div class="col-lg-2">
                                                    <label class="rdiobox">
                                                        <input name="axa_36" value="A" type="radio" disabled>
                                                        <span>1</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_36" value="B" type="radio" disabled>
                                                        <span>2</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_36" value="C" type="radio" disabled>
                                                        <span>3</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_36" value="D" type="radio" disabled>
                                                        <span>4</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_36" value="E" type="radio" disabled>
                                                        <span>5</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label tx-bold">Estas molestias le han impedido o dificultado realizar su trabajo?</label>
                                            <div class="row mg-t-10">
                                                <div class="col-lg-2">
                                                    <label class="rdiobox">
                                                        <input name="axa_37" value="A" type="radio" disabled>
                                                        <span>SI</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_37" value="B" type="radio" disabled>
                                                        <span>NO</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label tx-bold">A qué atribuye estas molestias?</label>
                                            <div class="row mg-t-10">
                                                <div class="col-lg-3">
                                                    <label class="ckbox">
                                                        <input type="checkbox" class="axa_38" name="axa_38[]" value="A" disabled><span>Edad</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                                    <label class="ckbox">
                                                        <input type="checkbox" class="axa_38" name="axa_38[]" value="B" disabled><span>Actividad deportiva</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                                    <label class="ckbox">
                                                       <input type="checkbox" class="axa_38" name="axa_38[]" value="C" disabled><span>Enfermedad previa</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                                    <label class="ckbox">
                                                        <input type="checkbox" class="axa_38" name="axa_38[]" value="D" disabled><span>Trabajo</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                                    <label class="ckbox">
                                                        <input type="checkbox" class="axa_38" name="axa_38[]" value="E" disabled><span>Accidente previo</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                                    <label class="ckbox">
                                                        <input type="checkbox" class="axa_38" name="axa_38[]" value="F" disabled><span>Oficios domésticos</span>
                                                    </label>
                                                </div>
                                            </div>
                                       </div>
                                    </div>
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative">
                                            <label class="form-control-label tx-bold">Ha estado incapacitado por estas molestias?</label>
                                            <div class="row mg-t-10">
                                                <div class="col-lg-2">
                                                    <label class="rdiobox">
                                                        <input name="axa_39" value="A" type="radio" disabled>
                                                        <span>SI</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_39" value="B" type="radio" disabled>
                                                        <span>NO</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 pd-t-20 pd-b-20">
                                        <div class="form-group pos-relative disabled-biomechanical" id="axa_40">
                                            <label class="form-control-label tx-bold">El tiempo por el cual ha estado incapacitado por estas molestias ha sido:</label>
                                            <div class="row mg-t-10">
                                                <div class="col-lg-2">
                                                    <label class="rdiobox">
                                                        <input name="axa_40" value="A" type="radio" disabled>
                                                        <span>Entre 1 y 7 días</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_40" value="B" type="radio" disabled>
                                                        <span>Entre 8 y 30 días</span>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                                    <label class="rdiobox">
                                                        <input name="axa_40" value="C" type="radio" disabled>
                                                        <span>Mas de 30 días</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                {if $act_add}
                <div class="row">
                    <div class="col-lg-12 pd-t-20 pd-b-20">
                        <div class="ft-right">
                            <button id="btn_confirm_add" class="btn btn-warning">Enviar Encuesta</button>
                        </div>
                    </div>
                </div>
                {/if}
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
{/block}
{block name='scripts'}
    <script type="text/javascript">
        var $path_finish                                                        =   '{$path_finish}';

        var participate                                                         =   '{$participate}';
    </script>
    {if $mobile}
        <script src="{$RESOURCES}js/mobile/biomechanicalform.js"></script>
    {else}
        <script src="{$RESOURCES}js/desktop/biomechanicalform.js"></script>
    {/if}
{/block}