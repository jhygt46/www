$(document).ready(function(){
    borrar_carro();
    render_pagina();
    if(ver_inicio == 1){
        show_modal('modal_pagina_inicio');
    }
    console.log(url_pag_id);
    console.log(url_cat_id);
});

$(window).resize(function(){
    btn_prin();
});

var menu = 0;
var modal = 0;
var paso = 1;
var catalogo = 0;
var debug = 1;
var cantidad = 0;
var pre_promo = 0;
var map_init = 0;
var maps = [];
var mapsl = [];
var time_limit = 7200;
var timer = false;

// FIN BACK BUTTON //

// CLICK OUT //
$(document).click(function(e) {
    if($(e.target).hasClass('cont_modals')){
        hide_modal();
    }
});