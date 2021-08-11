{extends file='../head.tpl'}
{block name='body'}
<div class="br-pageheader pd-y-15 pd-l-20">
</div>
<div class="pos-relative">
    <a href="https://api.whatsapp.com/send?phone=573102125468&text=Buen%20d%C3%ADa%2C%20mi%20nombre%20es%20{$name_user_whatsapp}%20y%20quisiera%20solicitar%20un%20cambio%20de%20turno%20" class="btn-flotante" data-toggle="tooltip" data-placement="top" title="Tienes dudas o inconvenientes, contactanos por este medio!">
        <img src="{$RESOURCES}img/wsp.png" class="wd-50">
    </a>
    <div class="br-pagebody mg-t-3 pd-x-30">
        <div class="br-section-wrapper mn-ht-120 panel pd-y-0 pd-x-0">
            <div class="row">
                {if $mobile}
                <div class="col-md-12 tx-center pos-relative">
                <a
                href="https://api.whatsapp.com/send?phone=573102125468&text=Buen%20d%C3%ADa%2C%20mi%20nombre%20es%20{$name_user_whatsapp}%20y%20quisiera%20solicitar%20un%20cambio%20de%20turno%20">
                <img src="{$RESOURCES}img/login/button_sc.png"
                    class="btn-mobile-shiftchange pos-absolute wd-64p">
            </a>
            <img class="wd-fill" src="{$RESOURCES}img/login/cambio_turno_movil_1.png">
                </div>
                {else}
                <div class="col-md-12 tx-center pos-relative">
                    <a
                        href="https://api.whatsapp.com/send?phone=573102125468&text=Buen%20d%C3%ADa%2C%20mi%20nombre%20es%20{$name_user_whatsapp}%20y%20quisiera%20solicitar%20un%20cambio%20de%20turno%20">
                        <img src="{$RESOURCES}img/login/button_sc.png"
                            class="btn-shiftchange pos-absolute wd-27p">
                    </a>
                    <img class="wd-fill" src="{$RESOURCES}img/login/cambio_turno_1.png">
                </div>
                {/if}
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