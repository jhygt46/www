$(document).ready(function(){
    
    borrar_carro();
    render_pagina();

    if(ver_inicio == 1){
        show_modal('modal_pagina_inicio');
    }

    if(paso == "1"){
        console.log("x paso_1");
    }else if(paso == "2"){
        console.log("x paso_2");
    }else if(paso == "2a"){
        console.log("x paso_2a");
    }else if(paso == "2b"){
        console.log("x paso_2b");
    }else if(paso == "3"){
        console.log("x paso_3");
    }else if(paso == "4"){
        console.log("x paso_4");
    }else{
        if(url_pag_id > 0){
            ver_pagina(url_pag_id, 0);
        }
        if(url_cat_ids.length > 0){
            open_categoria(url_cat_ids[url_cat_ids.length - 1].id, 0);
        }
    }
    
});

$(window).on('popstate', function(e){

    var back = window.location.href.split('/');
    if(back[back.length - 1] == ""){
        back.pop();
    }

    if(back[3] == "paso_1"){
        open_carro(0);
    }else if(back[3] == "paso_2"){
        console.log("y paso_2");
    }else if(back[3] == "paso_2a"){
        console.log("y paso_2a");
    }else if(back[3] == "paso_2b"){
        console.log("y paso_2b");
    }else if(back[3] == "paso_3"){
        console.log("y paso_3");
    }else if(back[3] == "paso_4"){
        console.log("y paso_4");
    }else{
        var rec_u = rec_url(0, back, 3);
        if(rec_u.op == 1){
            open_categoria(rec_u.id, 0);
        }
        if(rec_u.op == 2){
            var rec_p = rec_pagina(back[3]);
            if(rec_p.op == 1){
                ver_pagina(rec_p.id, 0);
            }
            if(rec_p.op == 2){
                $('.modals').hide();
            }
        }
    }

});

function rec_pagina(nombre){

    var res = new Object();
    var paginas = data.paginas;
    for(var j=0; j<paginas.length; j++){
        if(nombre == paginas[j].nombre){
            res.id = paginas[j].id_pag;
            res.op = 1;
            return res;
        }
    }
    res.op = 2;
    return res;

}
function rec_url(p_id, url, x){

    var categorias = data.catalogos[0].categorias;
    for(var j=0; j<categorias.length; j++){
        if(url[x] == categorias[j].nombre && p_id == categorias[j].parent_id){
            if(url.length == x + 1){
                var res = new Object();
                res.id = categorias[j].id_cae;
                res.op = 1;
                return res;
            }else{
                var i = x + 1;
                return rec_url(categorias[j].id_cae, url, i);
            }
        }
    }
    var res = new Object();
    res.op = 2;
    return res;
}

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
        history.pushState(null, '', '/');
    }
});