$(document).ready(function(){
    render_pagina();
    borrar_carro();
    cantidad = get_carro().length;
    $('.cantcart_num').html(cantidad);
    var init_puser = get_puser();
    $('#pedido_nombre').val(init_puser.nombre);
    $('#pedido_telefono').val(init_puser.telefono);
    if(inicio == 1){
        show_modal('modal_inicio');
    }
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
var time_limit = 7200;
var timer = false;

// FIN BACK BUTTON //

// CLICK OUT //
$(document).click(function (e) {
    if($(e.target).hasClass('cont_modals')){
        hide_modal();
    }
});