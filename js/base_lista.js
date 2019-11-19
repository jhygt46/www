var map_socket, markers, widthpx, heightpx;
function render_pagina(){
    
    // BOTONES PRINCIPALES
    var num = 0;
    if(data.catalogos[catalogo].categorias){
        var categorias = data.catalogos[catalogo].categorias;
        for(var i=0, ilen=categorias.length; i<ilen; i++){
            if(categorias[i].parent_id == 0 && categorias[i].ocultar == 0){
                $('.cont_contenido').append(html_home_categorias(categorias[i], num));
                num++;  
            }
        }
    }
    
    if(num == 0 && produccion == 0){
        alert("No hay categorias creadas!\nEn el administrador debe crearlas en la categoria 'Carta'");
    }

    // MENU LEFT PAGINAS
    if(data.paginas){
        var paginas = data.paginas;
        if(paginas !== null){
            for(var i=0, ilen=paginas.length; i<ilen; i++){
                $('.lista_paginas').append(html_paginas(paginas[i])); 
            }
        }
    }

    btn_prin();
    
}
function inclinacion(x1, y1, x2, y2, x){
    return parseInt((((y2 - y1)/(x2 - x1))*(x - x1) + y1) * x / 100);
}
function btn_prin(){

    var width = $(window).width();
    var height = $(window).height();

    $('.botones_principales').each(function(){
        var heightp = $(this).width() * data.config.alto / 100;
        $(this).css({ height: heightp+'px'});
    });
    
    if(width < 500){
        widthpx = inclinacion(320, 96, 500, 90, width)
    }
    if(width >= 500 && width < 743){
        widthpx = inclinacion(500, 90, 743, 70, width);
    }
    if(width >= 743){
        widthpx = 520;
    }
    
    if(height < 568){
        heightpx = inclinacion(320, 100, 568, 96, height)
    }
    if(height >= 568 && height < 768){
        heightpx = inclinacion(568, 96, 768, 92, height);
    }
    if(height >= 768 && height < 1024){
        heightpx = inclinacion(768, 92, 1024, 88, height);
    }
    if(height >= 1024 && height < 2048){
        heightpx = inclinacion(1024, 88, 2048, 84, height);
    }
    
    var dir_op = parseInt((widthpx - 20) * data.config.alto / 100);

    $('.dir_locales').css({ height: dir_op });
    $('.dir_op').css({ height: dir_op });
    $('.modal_dim1').css({ width: widthpx });
    $('.modal_dim1').css({ height: heightpx });

}
function close_that(that){
    $('.modals').hide();
    $(that).parents('.modal').hide();
    paso = 1;
}
function open_categoria(id){
    
    var tp = tiene_pedido();
    if(!tp){
        if(proceso(true, false)){
            show_modal('modal_carta');
            var categorias = data.catalogos[catalogo].categorias;
            var cats = [];
            for(var i=0, ilen=categorias.length; i<ilen; i++){
                if(categorias[i].id_cae == id){
                    $('.modal_carta .titulo h1').html(categorias[i].nombre);
                    $('.modal_carta .titulo h2').html(categorias[i].descripcion_sub);
                    for(var j=0, jlen=categorias.length; j<jlen; j++){
                        if(categorias[i].id_cae == categorias[j].parent_id){
                            cats.push(categorias[j]);
                        }
                    }
                    if(cats.length == 0){ 
                        imprimir_productos_modal(id);
                    }
                    if(cats.length > 0){ 
                        imprimir_categoria_modal(cats);
                    }
                }
            }
        }
    }
    if(tp){
        show_modal_4(get_pedido());
    }
    hidemenu();
    
}
function tiene_pedido(){
    
    var pedido = get_pedido();
    if(pedido.id_ped == 0){
        return false;
    }
    if(pedido.id_ped > 0){
        var limit = Math.round(new Date().getTime()/1000) - pedido.fecha;
        if(limit < time_limit){
            return true;
        }
        if(limit >= time_limit){
            aux_nuevo();
            return false;
        }
    }

}
function open_carro(){
    
    var tp = tiene_pedido();
    if(!tp){
        if(proceso(true, false)){
            process_carro();
            show_modal('paso_01');
        }
    }
    if(tp){
        show_modal('paso_04');
    }
    paso = 2;
    hidemenu();
    
}
function imprimir_productos_modal(id){
    
    var categoria = get_categoria(id);
    $('.modal_carta .info_modal').html('');

    if(categoria.productos && categoria.tipo == 0){
        var html = create_element_class('lista_productos');
        var productos = categoria.productos;
        for(var j=0, jlen=productos.length; j<jlen; j++){
            var producto = get_producto(productos[j]);
            if(producto.disponible != 2 && categoria.detalle_prods == 0){
                //html.append(create_html_producto(producto));
                html.append(create_html_producto2(producto));
                //html.append(create_html_producto3(producto));
            }
        }
    }
    if(categoria.tipo == 1){
        var html = imprimir_promo_modal(categoria);
    }

    $('.modal_carta .info_modal').append(html);
    
}
function imprimir_categoria_modal(categorias){
    
    $('.modal_carta .info_modal').html('');
    var html = create_element_class('lista_categorias');
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].tipo == 0 && categorias[i].ocultar == 0){
            html.appendChild(create_html_categorias(categorias[i]));
        }
        if(categorias[i].tipo == 1 && categorias[i].ocultar == 0){
            html.appendChild(create_html_promocion(categorias[i]));
        }
    }
    $('.modal_carta .info_modal').append(html);
    
}
function add_carro_producto_aux(that){
    var id = $(that).attr('id');
    add_carro_producto(id);
}
function add_carro_producto(id_pro){
    
    var producto = get_producto(id_pro);
    var carro = get_carro();
    var item_carro = { id_pro: parseInt(id_pro) };
    if(producto.preguntas){
        item_carro.preguntas = [];
        for(var k=0, klen=producto.preguntas.length; k<klen; k++){
            item_carro.preguntas.push(get_preguntas(producto.preguntas[k]));
        }
    }
    carro.push(item_carro);
    set_cantidad(1);
    localStorage.setItem("carro", JSON.stringify(carro));
    if(tiene_pregunta(item_carro)){ mostrar_pregunta(carro.length - 1) }else{ hide_modal() }
    
}
function add_carro_promocion(id_cae){

    var producto, item_carro;
    var carro = JSON.parse(localStorage.getItem("carro")) || [];
    var promo = get_categoria(id_cae);
    if(promo.categorias){
        for(var i=0, ilen=promo.categorias.length; i<ilen; i++){
            carro.push({id_cae: parseInt(promo.categorias[i].id_cae), cantidad: parseInt(promo.categorias[i].cantidad)});
        }
    }
    if(promo.productos){
        for(var i=0, ilen=promo.productos.length; i<ilen; i++){
            for(var j=0, jlen=promo.productos[i].cantidad; j<jlen; j++){
                producto = get_producto(promo.productos[i].id_pro);
                item_carro = { id_pro: parseInt(promo.productos[i].id_pro) };
                if(producto.preguntas){
                    item_carro.preguntas = [];
                    for(var k=0, klen=producto.preguntas.length; k<klen; k++){
                        item_carro.preguntas.push(get_preguntas(producto.preguntas[k]));
                    }
                }
                carro.push(item_carro);
            }
        }
    }
    set_cantidad(1);
    localStorage.setItem("carro", JSON.stringify(carro));
    if(proceso(true, true)){
        process_carro();
        show_modal('paso_01');
    }
    
}
function tiene_pregunta(carro){
    
    if(carro.preguntas){
        for(var k=0, klen=carro.preguntas.length; k<klen; k++){
            for(var j=0, jlen=carro.preguntas[k].valores.length; j<jlen; j++){
                var valores = carro.preguntas[k].valores[j];
                if(valores.seleccionados){
                    if(valores.seleccionados.length < valores.cantidad){
                        return true;
                    }
                }else{
                    return true;
                }
            }
        }
    }
    return false;
}
function confirmar_pregunta_productos(that){

    var carros = get_carro();
    var parent = $(that).parents('.modal_pregunta_productos');
    var pregunta = parent.find('.s_pregunta');
    var i = pregunta.attr('data-pos');
    var k = 0;
    var m = 0;
    var n = 0;
    var count = 0;
    var cant = 0;
    var valores = [];
    var diff = 0;
    
    var preguntas = pregunta.find('.e_pregunta');
    preguntas.each(function(){
        k = $(this).attr('data-pos');
        $(this).find('.v_pregunta').each(function(){
            m = $(this).attr('data-pos');
            cant = $(this).attr('data-cant');
            count = 0;
            valores = [];
            $(this).find('.n_pregunta').each(function(){
                if($(this).hasClass('selected')){
                    count++;
                    valores.push($(this).html().trim());
                }
            });
            diff = cant - count;
            if(diff < 0){
                alert("HA SELECCIONADO "+Math.abs(diff)+" OPCIONES MAS");
            }
            if(diff > 0){
                alert("FALTA SELECCIONAR "+diff+" OPCIONES");
            }
            if(diff == 0){
                carros[i].preguntas[k].valores[m].seleccionados = valores;
                localStorage.setItem("carro", JSON.stringify(carros));
                if(preguntas_promo(carros[i])){
                    if(paso == 2){
                        if(proceso(true, true)){
                            //show_modal('paso_02');
                            ver_paso_2();
                        }
                    }else{
                        hide_modal();
                    }
                }
            }
        });
    });
    
}
function preguntas_promo(pro){
    
    if(pro.promo !== null){
        var carros = get_carro();
        for(var i=0, ilen=carros.length; i<ilen; i++){
            if(carros[i].promo !== null && carros[i].promo == pro.promo && tiene_pregunta(carros[i])){
                mostrar_pregunta(i);
                return false;
            }
        }
    }
    return true;
    
}
function mostrar_pregunta(i){
    
    var carro = get_carro();
    var producto = get_producto(carro[i].id_pro);
    show_modal('modal_pregunta_productos');

    if(producto.nombre_carro != ""){
        if(producto.numero > 0){
            $('.modal_pregunta_productos .titulo h1').html(producto.numero + '.- ' + producto.nombre_carro);
        }else{
            $('.modal_pregunta_productos .titulo h1').html(producto.nombre_carro);
        }
    }else{
        if(producto.numero > 0){
            $('.modal_pregunta_productos .titulo h1').html(producto.numero + '.- ' + producto.nombre);
        }else{
            $('.modal_pregunta_productos .titulo h1').html(producto.nombre);
        }
    }

    $('.modal_pregunta_productos .info_modal').html('');
    $('.modal_pregunta_productos .info_modal').html(html_preguntas_producto(i));

}
function topscroll(){
    $('.cont_contenido').animate({ scrollTop: 0 }, 500);
}
function seleccionar_productos_categoria_promo(i){
    
    var carros = get_carro();
    var id_cae = carros[i].id_cae;
    var cantidad = carros[i].cantidad;
    var categoria = get_categoria(id_cae);
    
    show_modal('modal_productos_promo');
    $('.modal_productos_promo .titulo h1').html(categoria.nombre);
    $('.modal_productos_promo .titulo h2').html('Debe seleccionar '+cantidad+' productos');
    $('.modal_productos_promo .info_modal').html(html_seleccionar_productos_categoria_promo(categoria, i, cantidad));
    
}
function select_pregunta(that){
    
    var parent = $(that).parent();
    var cantidad = parent.attr('data-cant');
    var seleccionadas = parent.find('.selected').length;
    var diff = cantidad - seleccionadas;

    if($(that).hasClass('selected')){
        $(that).removeClass('selected');
    }
    if(cantidad == 1 && !$(that).hasClass('selected')){
        parent.find('.selected').eq(0).removeClass('selected');
        $(that).addClass('selected');
    }
    if(cantidad > 1 && !$(that).hasClass('selected') && diff > 0){
        $(that).addClass('selected');
    }

}
function process_carro(){

    var total = 0;
    var info = process_new_promos();
    var carro = info.carro;
    var carro_promos = info.carro_promos;
    var count = 0;
    var promocion, producto, promo_detalle, process_carro_promo, promo_info, promo_delete, promo_precio;

    var html = create_element_class('process_carro');

    for(var i=0, ilen=carro_promos.length; i<ilen; i++){

        promocion = get_categoria(carro_promos[i].id_cae);
        total = total + parseInt(promocion.precio);

        process_carro_promo = create_element_class('process_carro_promo');

        promo_detalle = create_element_class('promo_detalle');
        promo_info = create_element_class_inner('promo_info font1', promocion.nombre);
        promo_precio = create_element_class_inner('promo_precio font1', formatNumber.new(parseInt(promocion.precio), "$"));
        promo_delete = create_element_class_inner('promo_delete material-icons', 'close');
        promo_delete.setAttribute('promo-pos', i);
        promo_delete.onclick = function(){ delete_promo(this) };

        process_carro_promo.appendChild(promo_info);
        process_carro_promo.appendChild(promo_precio);
        process_carro_promo.appendChild(promo_delete);

        for(var j=0, jlen=carro.length; j<jlen; j++){
            if(carro[j].promo == i){
                count++;
                producto = get_producto(carro[j].id_pro);
                promo_detalle.appendChild(promo_carros(producto, j));
            }
        }

        process_carro_promo.appendChild(promo_detalle);
        html.appendChild(process_carro_promo);

    }

    var restantes = false;
    var process_carro_restantes = create_element_class('process_carro_restantes');

    for(var j=0, jlen=carro.length; j<jlen; j++){
        if(carro[j].promo === undefined){
            count++;
            producto = get_producto(carro[j].id_pro);
            process_carro_restantes.appendChild(promo_restantes(producto, j, tiene_pregunta(carro[j])));
            total = total + parseInt(producto.precio);
            restantes = true;
        }
    }

    if(restantes){ 
        html.appendChild(process_carro_restantes);
    }
    
    $('.paso_01 .info_modal').html(html);
    set_carro(info.carro, info.carro_promos);
    $('.paso_01_sub_total').html("Pedido: "+formatNumber.new(parseInt(total), "$"));
    
    var pedido = get_pedido();
    pedido.total = total;
    set_pedido(pedido);
    
}
function process_new_promos(){
    
    var carro = get_carro_limpio();
    var promos = process_promo();
    
    var carro_promos = [];
    var ocupados = [];
    var pos = 0;
    var repeat = true;
    var aux = {};

    for(var i=0, ilen=promos.length; i<ilen; i++){
        repeat = true;
        while(repeat){
            
            ocupados = [];
            for(var j=0, jlen=promos[i].productos.length; j<jlen; j++){
                for(var k=0, klen=carro.length; k<klen; k++){
                    if(promos[i].productos[j].id_pro.indexOf(carro[k].id_pro) != -1 && ocupados.indexOf(k) == -1){
                        if(carro[k].promo === undefined){
                            ocupados.push(k);
                            break;
                        }
                    }
                }
            }
            
            if(ocupados.length == promos[i].productos.length){
                pos = carro_promos.length;
                carro_promos.push({ id_cae: promos[i].id_cae });
                for(var k=0, klen=carro.length; k<klen; k++){
                    if(ocupados.indexOf(k) != -1){
                        carro[k].promo = pos;
                    }
                }
                repeat = true;
            }else{
                repeat = false;        
            }
        }
    }
    
    var aux = {};
    aux.carro = carro;
    aux.carro_promos = carro_promos;
    return aux;
    
}
function process_promo(){
    
    var promos = get_promociones();
    var productos = [];
    var aux = [];
    var cantidad = 0;
    
    for(var i=0, ilen=promos.length; i<ilen; i++){
        productos = [];
        cantidad = 0;
        if(promos[i].categorias){
            for(var j=0; j<promos[i].categorias.length; j++){
                for(var k=0, klen=promos[i].categorias[j].cantidad; k<klen; k++){
                    productos.push({id_pro: get_productos_categoria(promos[i].categorias[j].id_cae)});
                    cantidad++;
                }
            }
        }
        if(promos[i].productos){
            for(var j=0; j<promos[i].productos.length; j++){
                for(var k=0, klen=promos[i].productos[j].cantidad; k<klen; k++){
                    productos.push({id_pro: [parseInt(promos[i].productos[j].id_pro)]});
                    cantidad++;
                }
            }
        }
        if(productos.length > 0){
            aux.push({id_cae: promos[i].id_cae, nombre: promos[i].nombre, productos: productos, cantidad: cantidad});
        }
    }
    aux.sort(function (a, b){
        return (b.cantidad - a.cantidad)
    })
    return aux;
    
}
function delete_promo(that){
    
    var promos = get_promos();
    var j = that.getAttribute("promo-pos");
    for(var i=0, ilen=promos.length; i<ilen; i++){
        if(i == j){
            
            promos.splice(i, 1);
            set_promos(promos);
            
            var carro = get_carro();
            for(var m=0, mlen=carro.length; m<mlen; m++){
                if(carro[m].promo == i){
                    if(carro.splice(m, 1)){
                        m--;
                        mlen--;
                    }
                }
            }
            set_carro(carro);
            process_carro();
            
        }
    }
    set_cantidad(-1);
    
}
function delete_pro_carro(i){
    var carro = get_carro();
    carro.splice(i, 1);
    localStorage.setItem("carro", JSON.stringify(carro));
    process_carro();
    set_cantidad(-1);
}
function set_cantidad(n){
    cantidad = cantidad + n;
    $('.cantcart_num').html(cantidad);
}
function get_puser(){
    return JSON.parse(localStorage.getItem("p_user")) || { id_puser: 0, code: '', nombre: '', telefono: '+569' };
}
function set_puser(puser){
    localStorage.setItem("p_user", JSON.stringify(puser));
}
function get_carro(){
    return JSON.parse(localStorage.getItem("carro")) || [];
}
function get_pedido(){
    return JSON.parse(localStorage.getItem("pedido")) || obj_pedido();
}
function borrar_pedido(){
    localStorage.setItem("pedido", null);
}
function get_promos(){
    return JSON.parse(localStorage.getItem("carro_promos")) || [];
}
function get_categoria(id_cae){
    var categorias = data.catalogos[catalogo].categorias;
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].id_cae == id_cae){
            return categorias[i];
        }
    }
}
function get_producto(id_pro){
    var productos = data.catalogos[catalogo].productos;
    for(var i=0, ilen=productos.length; i<ilen; i++){
        if(productos[i].id_pro == id_pro){
            return productos[i];
        }
    }
}
function get_preguntas(id_pre){
    for(var i=0, ilen=data.catalogos[catalogo].preguntas.length; i<ilen; i++){
        if(id_pre == data.catalogos[catalogo].preguntas[i].id_pre){
            return data.catalogos[catalogo].preguntas[i];
        }
    }
    return null;
}
function get_carro_limpio(){
    var carro = JSON.parse(localStorage.getItem("carro")) || [];
    for(var i=0, ilen=carro.length; i<ilen; i++){
        delete carro[i].promo;
    }
    return carro;
}
function get_cats(tipo){
    var categorias = data.catalogos[catalogo].categorias;
    var aux = [];
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].tipo == tipo){
            aux.push(categorias[i]);
        }
    }
    return aux;
}
function get_categorias(){
    return get_cats(0);
}
function get_promociones(){
    return get_cats(1);
}
function set_carro(carro, carro_promos){
    localStorage.setItem("carro", JSON.stringify(carro));
    localStorage.setItem("carro_promos", JSON.stringify(carro_promos));
}
function set_promos(carro_promos){
    localStorage.setItem("carro_promos", JSON.stringify(carro_promos));
}
function set_pedido(pedido){
    localStorage.setItem("pedido", JSON.stringify(pedido));
}
function get_productos_categoria(id_cae){
    
    var categorias = get_categoria(id_cae);
    console.log(categorias);

    var productos = [];
    if(categorias.productos){
        for(var i=0, ilen=categorias.productos.length; i<ilen; i++){
            productos.push(parseInt(categorias.productos[i]));
        }
    }
    return productos;
    
}
function borrar_carro(){
    localStorage.setItem("carro", null);
    localStorage.setItem("carro_promos", null);
    borrar_pedido();
    $('.cantcart_num').html(0);
}
function ver_pagina(id){

    for(var i=0, ilen=data.paginas.length; i<ilen; i++){
        if(data.paginas[i].id_pag == id){

            if(data.paginas[i].tipo == 0){
                if(data.paginas[i].imagen != ""){
                    var html = data.paginas[i].html.replace("#FOTO#", data.paginas[i].imagen);
                }
                $('.modal_pagina .cont_info').html(html);
                show_modal('modal_pagina');
            }
            if(data.paginas[i].tipo == 1){
                show_locales('Locales', 'Listado de todos nuestros locales');
            }
            if(data.paginas[i].tipo == 2){
                show_modal('modal_contacto');
            }
            
        }
    }
    
}
function showmenu(){
    $('.menu_left').animate({
        left: "0px"
    }, 200, function(){
        menu = 1;
        if(data.paginas.length == 0 && produccion == 0){
            alert("No hay paginas creadas!\nEn el administrador debe crearlas en la categoria 'Contenido'");
        }
    });
    $('.btn_toogle').animate({
        right: "20px"
    }, 400);
}
function hidemenu(){
    $('.menu_left').animate({
        left: "-220px"
    }, 200, function(){
        menu = 0;
    });
    $('.btn_toogle').animate({
        right: "-50px"
    }, 400);
}
function tooglemenu(){
    if(menu == 0)
        showmenu();
    if(menu == 1)
        hidemenu();
}
function ver_paso_2(){
    
    var total = parseInt(get_pedido().total);
    var pedido_minimo = parseInt(data.config.pedido_minimo);
    var info_retiro = estado_locales(1);
    var info_desp = estado_locales(2);

    if(info_retiro){
        // RETIRO EN LOCAL NORMAL
        $('.paso_02').find('.rlocal').find('.alert').hide();
        $('.paso_02').find('.rlocal').find('.stitle').show();
    }else{
        // NO HAY RETIRO EN LOCAL
        $('.paso_02').find('.rlocal').find('.alert').show();
        $('.paso_02').find('.rlocal').find('.stitle').hide();
    }

    if(info_desp){
        if(total < pedido_minimo){
            // NO HAY DESPACHO X PEDIDO MINIMO
            $('.paso_02').find('.cdesp').find('.alert').show();
            $('.paso_02').find('.cdesp').find('.stitle').hide();
            $('.paso_02').find('.cdesp').find('.alert').html("Pedido minimo: "+formatNumber.new(parseInt(pedido_minimo), "$"));
        }else{
            // DESPACHO NORMAL
            $('.paso_02').find('.cdesp').find('.alert').hide();
            $('.paso_02').find('.cdesp').find('.stitle').show();
        }
    }else{
        // NO HAY DESPACHO X HORARIO
        $('.paso_02').find('.cdesp').find('.alert').show();
        $('.paso_02').find('.cdesp').find('.stitle').hide();
        $('.paso_02').find('.cdesp').find('.alert').html("No hay Despacho");
    }

    $('.modal').hide();
    $('.modals, .paso_02').show();

}
function estado_local(id_loc){

    var datetime_actual = fecha_js + new Date().getTime() - fecha_pc;
    var fecha_actual = new Date(datetime_actual);
    var fecha_ayer = new Date(datetime_actual - 86400000);

    var fecha_hoy_00 = new Date(fecha_actual.getFullYear(), fecha_actual.getMonth(), fecha_actual.getDate()).getTime();
    var fecha_ayer_00 = new Date(fecha_ayer.getFullYear(), fecha_ayer.getMonth(), fecha_ayer.getDate()).getTime();
    var res = { op: 2, mensaje: 'Local Cerrado', tiempo: 0 }

    if(data.locales !== null){
        for(var i=0, ilen=data.locales.length; i<ilen; i++){
            if(data.locales[i].id_loc == id_loc){
                for(var j=0, jlen=data.locales[i].horarios.length; j<jlen; j++){

                    if(data.locales[i].horarios[j].tipo == 1 || data.locales[i].horarios[j].tipo == 0){

                        var dia_ayer = fecha_ayer.getDay() > 0 ? fecha_ayer.getDay() : 7 ;
                        var dia_hoy = fecha_actual.getDay() > 0 ? fecha_actual.getDay() : 7 ;
                        var hora_ini = (data.locales[i].horarios[j].hora_ini * 3600 + data.locales[i].horarios[j].min_ini * 60) * 1000;
                        var hora_fin = (data.locales[i].horarios[j].hora_fin * 3600 + data.locales[i].horarios[j].min_fin * 60) * 1000;

                        if(dia_ayer >= data.locales[i].horarios[j].dia_ini && dia_ayer <= data.locales[i].horarios[j].dia_fin){
                            var time_ayer_ini = new Date(fecha_ayer_00 + hora_ini).getTime();
                            var time_ayer_fin = new Date(fecha_ayer_00 + hora_fin).getTime();
                            if(datetime_actual >= time_ayer_ini && datetime_actual <= time_ayer_fin){
                                res.op = 1;
                                res.tiempo = parseInt((time_ayer_fin - datetime_actual)/60000);
                                res.mensaje = "Local Abierto";
                            }
                        }
                        if(dia_hoy >= data.locales[i].horarios[j].dia_ini && dia_hoy <= data.locales[i].horarios[j].dia_fin){
                            var time_hoy_ini = new Date(fecha_hoy_00 + hora_ini).getTime();
                            var time_hoy_fin = new Date(fecha_hoy_00 + hora_fin).getTime();
                            if(datetime_actual >= time_hoy_ini && datetime_actual <= time_hoy_fin){
                                res.op = 1;
                                res.tiempo = parseInt((time_hoy_fin - datetime_actual)/60000);
                                res.mensaje = "Local Abierto";
                            }
                        }

                    }

                }
            }
        }
    }
    return res;

}
function estado_locales(tipo){

    var datetime_actual = fecha_js + new Date().getTime() - fecha_pc;
    var fecha_actual = new Date(datetime_actual);
    var fecha_ayer = new Date(datetime_actual - 86400000);

    var fecha_hoy_00 = new Date(fecha_actual.getFullYear(), fecha_actual.getMonth(), fecha_actual.getDate()).getTime();
    var fecha_ayer_00 = new Date(fecha_ayer.getFullYear(), fecha_ayer.getMonth(), fecha_ayer.getDate()).getTime();

    if(data.locales !== null){
        for(var i=0, ilen=data.locales.length; i<ilen; i++){
            for(var j=0, jlen=data.locales[i].horarios.length; j<jlen; j++){
                if(data.locales[i].horarios[j].tipo == tipo || data.locales[i].horarios[j].tipo == 0){

                    var dia_ayer = fecha_ayer.getDay() > 0 ? fecha_ayer.getDay() : 7 ;
                    var dia_hoy = fecha_actual.getDay() > 0 ? fecha_actual.getDay() : 7 ;
                    var hora_ini = (data.locales[i].horarios[j].hora_ini * 3600 + data.locales[i].horarios[j].min_ini * 60) * 1000;
                    var hora_fin = (data.locales[i].horarios[j].hora_fin * 3600 + data.locales[i].horarios[j].min_fin * 60) * 1000;

                    if(dia_ayer >= data.locales[i].horarios[j].dia_ini && dia_ayer <= data.locales[i].horarios[j].dia_fin){
                        var time_ayer_ini = new Date(fecha_ayer_00 + hora_ini).getTime();
                        var time_ayer_fin = new Date(fecha_ayer_00 + hora_fin).getTime();
                        if(datetime_actual >= time_ayer_ini && datetime_actual <= time_ayer_fin){
                            return true;
                        }
                    }

                    if(dia_hoy >= data.locales[i].horarios[j].dia_ini && dia_hoy <= data.locales[i].horarios[j].dia_fin){
                        var time_hoy_ini = new Date(fecha_hoy_00 + hora_ini).getTime();
                        var time_hoy_fin = new Date(fecha_hoy_00 + hora_fin).getTime();
                        if(datetime_actual >= time_hoy_ini && datetime_actual <= time_hoy_fin){
                            return true;
                        }
                    }

                }
            }
        }
    }
    return false;

}
function show_modal_locales(){

    var info_retiro = estado_locales(1);
    if(info_retiro){
        $('.paso_02a .direccion_op1').find('.dir_locales').each(function(){
            
            var id = $(this).attr('id');
            var local = estado_local(id);
            if(local.op){
                if(local.tiempo < data.config.tiempo_aviso){
                    $(this).find('.local_info').find('.alert').html("En "+local.tiempo+" minutos cierra este local");
                    $(this).find('.local_info').find('.alert').css({ display: 'block' });
                }else{
                    $(this).find('.local_info').find('.alert').css({ display: 'none' });
                }
            }else{
                $(this).find('.local_info').find('.alert').html("Local cerrado");
                $(this).find('.local_info').find('.alert').css({ display: 'block' });
            }
        });
        $('.modal').hide();
        $('.modals, .paso_02a').show();
        modal = 1;
    }

}
function show_modal(clase){
    $('.modal').hide();
    $('.modals, .'+clase).show();
    modal = 1;
}
function hide_modal(){
    modal = 0;
    paso = 1;
    $('.modals').hide();
    $('.modals .cont_modals').find('.modal').each(function(){
        $(this).hide();
        if($(this).hasClass('modal_carta') || $(this).hasClass('modal_productos_promo') || $(this).hasClass('modal_pregunta_productos') || $(this).hasClass('modal_pagina')){
            $(this).find('h1').html("");
            $(this).find('h2').html("");
            $(this).find('.info_modal').html("");
        }
        if($(this).hasClass('modal_carro')){
            //close_pedido();
        }
    });
}
function proceso(categorias, preguntas){

    var carro = get_carro();
    for(var i=0, ilen=carro.length; i<ilen; i++){
        if(!carro[i].id_pro && categorias){
            seleccionar_productos_categoria_promo(i);
            return false;
        }else{
            if(tiene_pregunta(carro[i]) && preguntas){
                mostrar_pregunta(i);
                return false;
            }
        }
    }
    return true;
    
}
function paso_2(){

    paso = 2;
    if(proceso(true, true) && cantidad > 0){
        if(data.config.retiro_local == 1 && data.config.despacho_domicilio == 1){
            ver_paso_2();
        }else{
            if(data.config.retiro_local == 1){
                show_modal_locales();
            }else{
                if(data.config.despacho_domicilio == 1){
                    show_despacho();
                }else{
                    if(produccion == 0){
                        alert("No hay locales creados!\nEn el administrador debe crearlas en la categoria 'Locales'");
                    }
                }
            }
        }
    }
    
}
function paso_3(){

    var init_puser = get_puser();
    $('#pedido_nombre').val(init_puser.nombre);
    $('#pedido_telefono').val(init_puser.telefono);
    paso = 3;
    show_modal('paso_03');

}
function show_modal_4(pedido){
    
    var punto = { lat: parseFloat(pedido.lat), lng: parseFloat(pedido.lng) };
    
    map_socket = new google.maps.Map(document.getElementById('mapa_posicion'), {
        center: punto,
        zoom: 17,
        mapTypeId: 'roadmap',
        disableDefaultUI: true
    });
    
    markers = new google.maps.Marker({
        map: map_socket,
        title: 'PEDIDO #'+pedido.num_ped,
        position: punto
    });
    
    $('.paso_04 .titulo h1').html("Pedido #"+pedido.num_ped);
    $('.pedido_final .estado h2').html(pedido.estado);
    $('.pedido_final .total').html("Total: "+formatNumber.new(parseInt(pedido.total + pedido.costo), "$"));
    show_modal('paso_04');
    open_socket(pedido.pedido_code);
    time();
    
}
function move_marker(lat, lng){
    
    markers.setPosition( new google.maps.LatLng( lat, lng ) );
    map_socket.panTo( new google.maps.LatLng( lat, lng ) );
    
}
function send_chat(){

    var pedido = get_pedido();
    var mensaje = $('#texto_chat').val();
    var send = { accion: 'enviar_chat', id_ped: pedido.id_ped, id_loc: pedido.id_loc, code: pedido.pedido_code, mensaje: mensaje };
    $.ajax({
        url: 'ajax/index.php',
        type: "POST",
        data: send,
        success: function(data){
            if(data.op == 1){
                $('#texto_chat').val("");
                $(".info_mensajes").append("<div class='chat_1'><div class='nom'>"+nombre+"</div><div class='msg'>"+mensaje+"</div></div>");
                $(".mensajes").scrollTop($(".info_mensajes").outerHeight());
            }
        }, error: function(e){}
    });

}
function paso_4(){
    
    var nombre = $('#pedido_nombre').val();
    var telefono = $('#pedido_telefono').val().split(" ").join("");
    if(nombre.length > 2){
        if(telefono.length >= 12 && telefono.length <= 14){
            
            grecaptcha.ready(function(){
                grecaptcha.execute('6LdZp78UAAAAAK56zJAVEkaSupUdCrRhsd1wnKkO', { action: 'contacto' }).then(function(token){
                    
                    var pedido = get_pedido();
                    pedido.nombre = nombre;
                    pedido.telefono = telefono;
                    pedido.depto = $('#pedido_depto').val();
                    pedido.pre_gengibre = ($('#pedido_gengibre').is(':checked') ? 1 : 0 );
                    pedido.pre_wasabi = ($('#pedido_wasabi').is(':checked') ? 1 : 0 );
                    pedido.pre_palitos = ($('#pedido_palitos').val()) ? $('#pedido_palitos').val() : 0 ;
                    pedido.pre_soya = ($('#pedido_soya').is(':checked') ? 1 : 0 );
                    pedido.pre_teriyaki = ($('#pedido_teriyaki').is(':checked') ? 1 : 0 );
                    pedido.comentarios = ($('#pedido_comentarios').val()) ? $('#pedido_comentarios').val() : '' ;
                    document.getElementById("enviar_cotizacion").disabled = true;
                    
                    var send = { accion: 'enviar_pedido', pedido: JSON.stringify(pedido), carro: JSON.stringify(get_carro()), promos: JSON.stringify(get_promos()), puser: JSON.stringify(get_puser()), token: token };

                    $.ajax({
                        url: 'ajax/index.php',
                        type: "POST",
                        data: send,
                        success: function(res){

                            if(res.op == 1){

                                $('#pedido_nombre').css({ border: '0px' });
                                $('#pedido_telefono').css({ border: '0px' });

                                if(res.activar_envio == 0){

                                    if(res.set_puser == 1){ set_puser({ id_puser: res.puser_id, code: res.puser_code, nombre: res.puser_nombre, telefono: res.puser_telefono }); }
                                    if(pedido.despacho == 0){ pedido.time = res.t_retiro; }
                                    if(pedido.despacho == 1){ pedido.time = res.t_despacho; } 
                                    pedido.id_ped = res.id_ped;
                                    pedido.num_ped = res.num_ped;
                                    pedido.pedido_code = res.pedido_code;
                                    pedido.fecha = res.fecha;
                                    pedido.lat = res.lat;
                                    pedido.lng = res.lng;
                                    pedido.estado = estados[0];
                                    show_modal_4(pedido);
                                    set_pedido(pedido);
                                    paso = 1;

                                }

                                if(res.activar_envio == 1){

                                    if(res.email == 1){
                                        if(res.set_puser == 1){ set_puser({ id_puser: res.puser_id, code: res.puser_code, nombre: res.puser_nombre, telefono: res.puser_telefono }); }
                                        if(pedido.despacho == 0){ pedido.time = res.t_retiro; }
                                        if(pedido.despacho == 1){ pedido.time = res.t_despacho; } 
                                        pedido.id_ped = res.id_ped;
                                        pedido.num_ped = res.num_ped;
                                        pedido.pedido_code = res.pedido_code;
                                        pedido.fecha = res.fecha;
                                        pedido.lat = res.lat;
                                        pedido.lng = res.lng;
                                        pedido.estado = estados[0];
                                        show_modal_4(pedido);
                                        set_pedido(pedido);
                                        paso = 1;
                                    }
                                    if(res.email == 2){
                                        $('#err_telefono').attr('href', 'tel:'+res.tel);
                                        $('#err_correo').attr('href', 'mailto:'+res.mailto+';misitiodelivery@gmail.com?subject=Envio%20Manual&body='+res.body);
                                        show_modal('modal_error');
                                    }

                                }

                            }else if(res.op == 2){
                                show_locales('Ocurrio un Error', 'Disculpe las Molestias');
                                send_error(16, " enviar_pedido() op!=2 ");
                            }else{
                                show_locales('Ocurrio un Error', 'Disculpe las Molestias');
                                send_error(16, " enviar_pedido() op!=1|2 ");
                            }
                            document.getElementById("enviar_cotizacion").disabled = false;
                            
                        },error: function(e, err){
                            show_locales('Ocurrio un Error', 'Disculpe las Molestias');
                            send_error(16, "enviar_pedido() " + err);
                            document.getElementById("enviar_cotizacion").disabled = false;
                        }

                    });
                });
            });
        }else{
            $('#pedido_telefono').css({ border: '1px solid #900' });
            alert("Debe ingresar numero de telefono valido");
        }  
    }else{
        $('#pedido_nombre').css({ border: '1px solid #900' });
        alert("Debe ingresar nombre valido");
    }
    
}
function enviar_contacto(){

    var nombre = $('#contacto_nombre').val();
    var telefono = $('#contacto_telefono').val().split(" ").join("");
    var correo = $('#contacto_correo').val();
    var comentario = $('#contacto_comentario').val();
    
    if(validar_email(correo)){
        if(nombre.length > 2){
            if(telefono.length >= 12 && telefono.length <= 14){
                if(comentario.length > 0){
                    document.getElementById("enviar_contacto").disabled = true;
                    grecaptcha.ready(function(){
                        grecaptcha.execute('6LdZp78UAAAAAK56zJAVEkaSupUdCrRhsd1wnKkO', { action: 'contacto' }).then(function(token){
                            var send = { accion: 'enviar_contacto', nombre: nombre, telefono: telefono, correo: correo, comentario: comentario, token: token };
                            $.ajax({
                                url: 'ajax/index.php',
                                type: "POST",
                                data: send,
                                success: function(res){
                                    
                                    console.log(res);
                                    $('#contacto_nombre').val("");
                                    $('#contacto_telefono').val("");
                                    $('#contacto_correo').val("");
                                    $('#contacto_comentario').val("");
                                    document.getElementById("enviar_contacto").disabled = false;

                                },
                                error: function(e, err){

                                    send_error(16, "enviar_contacto() " + err);
                                    document.getElementById("enviar_contacto").disabled = false;

                                }
                            });
                        });
                    });
                }else{ $('#contacto_comentario').css({ border: '1px solid #900' }); } 
            }else{ $('#contacto_telefono').css({ border: '1px solid #900' }); }  
        }else{ $('#contacto_nombre').css({ border: '1px solid #900' }); }
    }else{ $('#contacto_correo').css({ border: '1px solid #900' }); }
    
}
function time(){

    var pedido = get_pedido();    
    var fecha_1 = pedido.fecha * 1000;
    var fecha_2 = new Date().getTime();
    var diff = Math.round((fecha_1 + Math.round(pedido.time * 60000) - fecha_2)/60000);

    if(diff >= 2){
        $('.pedido_final .tiempo h2').html(diff+" minutos aprox");
    }
    if(diff == 1){
        $('.pedido_final .tiempo h2').html("1 minuto aprox");
    }
    if(diff <= 0){
        $('.pedido_final .tiempo h2').html("Cumplido");
    }
    setTimeout(time, 3000);

}
function open_socket(code){
    
    var socket = io.connect('https://www.izusushi.cl', { 'secure': true });
    socket.on('pedido-'+code, function(data){

        var pedido = get_pedido();
        var info = JSON.parse(data.estado);
        
        if(info.accion == 0){
            $('.pedido_final .estado h2').html(estados[parseInt(info.estado)]);
            pedido.estado = estados[parseInt(info.estado)];
            set_pedido(pedido);
        }
        if(info.accion == 1){
            pedido.fecha = info.fecha;
            set_pedido(pedido);
        }
        if(info.accion == 2){
            $('.paso_04 .pedido_final .total').html("Total: "+formatNumber.new(parseInt(info.total), "$"));
            pedido.total = info.total;
            set_pedido(pedido);
        }
        if(info.accion == 3){
            chat_local(info.mensaje);
        }
        if(info.accion == 4){
            move_marker(info.lat, info.lng);
        }
        
    });
    
}
function chat_local(mensaje){
    $(".info_mensajes").append("<div class='chat_2'><div class='nom'>Local: </div><div class='msg'>"+mensaje+"</div></div>");
    $(".mensajes").scrollTop($(".info_mensajes").outerHeight());
}
function show_despacho(){

    var pedido = get_pedido();
    var total = parseInt(pedido.total);
    var pedido_minimo = parseInt(data.config.pedido_minimo);
    var info_desp = estado_locales(2);

    if(info_desp){
        if(total >= pedido_minimo){
            if(map_init == 0){
                initMap();
            }
            if(pedido.lat != 0 && pedido.lng != 0){
                $('#siguiente_map').show();
            }else{
                $('#siguiente_map').hide();
            }
            show_modal('paso_02b');
        }
    }
    
}
function validar_email(email){
    var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email) ? true : false;
}
function show_locales(titulo, subtitulo){

    $('.modal_error_locales .cont_titulo h1').html(titulo);
    $('.modal_error_locales .cont_titulo h2').html(subtitulo);
    show_modal('modal_error_locales');

}
function initMap(){
    
    map_init = 1;
    map = new google.maps.Map(document.getElementById('map_direccion'), {
        center: {lat: -33.428066, lng: -70.616695},
        zoom: 13,
        mapTypeId: 'roadmap',
        disableDefaultUI: true
    });
    
    // Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
      searchBox.setBounds(map.getBounds());
    });
    
    var markers = [];
    searchBox.addListener('places_changed', function(){
        
        var places = searchBox.getPlaces();
        if(places.length == 0){
            return;
        }
        if(places.length == 1){
            
            var num = 0;
            var calle = "";
            var comuna = "";
            
            for(var i=0; i<places[0].address_components.length; i++){
                if(places[0].address_components[i].types[0] == "street_number"){
                    num = places[0].address_components[i].long_name;
                }
                if(places[0].address_components[i].types[0] == "route"){
                    calle = places[0].address_components[i].long_name;
                }
                if(places[0].address_components[i].types[0] == "locality"){
                    comuna = places[0].address_components[i].long_name;
                }
            }
            
            if(num != 0){
                
                var send = { accion: 'despacho_domicilio', lat: places[0].geometry.location.lat(), lng: places[0].geometry.location.lng()};
                $.ajax({
                    url: 'ajax/index.php',
                    type: "POST",
                    data: send,
                    success: function(data){

                        if(data.op == 1){

                            var pedido = get_pedido();
                            pedido.despacho = 1;
                            pedido.lat = places[0].geometry.location.lat();
                            pedido.lng = places[0].geometry.location.lng();
                            pedido.direccion = places[0].formatted_address; 
                            pedido.calle = calle;
                            pedido.num = num;
                            pedido.comuna = comuna;     
                            pedido.id_loc = data.id_loc;
                            pedido.costo = data.precio;
                            
                            set_pedido(pedido);
                            paso_3_despacho();
                            
                        }else if(data.op == 2){
                            alert("Su domicilio no se encuentra en la zona de reparto, disculpe las molestias");
                        }else if(data.op == 3){
                            alert("Error: no hay tramos definidos");
                        }else{
                            show_locales('Ocurrio un Error', 'Disculpe las Molestias');
                            send_error(16, " despacho_domicilio() op!=1|2|3 ");
                        }
                        
                    }, error: function(e, err){
                        show_locales('Ocurrio un Error', 'Disculpe las Molestias');
                        send_error(16, " despacho_domicilio() " + err);
                    }
                });
                
            }else{
                alert("DEBE INGRESAR DIRECCION EXACTA");
            }

        }
        markers.forEach(function(marker){
            marker.setMap(null);
        });
        markers = [];
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place){
            if(!place.geometry) {
                return;
            }
            var icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };
            markers.push(new google.maps.Marker({
                map: map,
                icon: icon,
                title: place.name,
                position: place.geometry.location
            }));

            if(place.geometry.viewport) {
                bounds.union(place.geometry.viewport);
            }else{
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });

}
function send_error(code, error){
    
    var send = { accion: 'enviar_error', codes: code, error: error };
    $.ajax({
        url: 'ajax/index.php',
        type: "POST",
        data: send
    });

}
function local_mapa(id, lat, lng, n){

    $('#contmap'+n+'-'+id).toggle();
    if(n == "s"){
        if(maps.indexOf(id) == -1){
            init_map_local(id, lat, lng);
            maps.push(id);
        }
    }
    if(n == "r"){
        if(mapsl.indexOf(id) == -1){
            init_map_local(id, lat, lng);
            mapsl.push(id);
        }
    }

}
function init_map_local(id, lat, lng){
    
    var map_local = new google.maps.Map(document.getElementById('lmap-'+id), {
        center: {lat: lat, lng: lng},
        zoom: 15,
        mapTypeId: 'roadmap',
        disableDefaultUI: true
    });
    var myLatLng = { lat: lat, lng: lng };
    new google.maps.Marker({
        position: myLatLng,
        map: map_local
    });
    
}
function select_local(id, nombre, direccion){
    
    var pedido = get_pedido();
    pedido.despacho = 0;
    pedido.id_loc = id;
    set_pedido(pedido);
    $('.cont_map').hide();
    
    selecciono_retiro(nombre, direccion, pedido.total);
    paso_3();
    
}
function paso_3_despacho(){
    
    var pedido = get_pedido();
    
    if(pedido.lat != 0 && pedido.lng != 0){
        
        pedido.despacho = 1;
        set_pedido(pedido);
        var total = pedido.costo + pedido.total;
        selecciono_despacho(pedido.calle, pedido.num, pedido.costo, total);
        $('.block_direccion .item_direccion h2').html(pedido.calle);
        $('.block_direccion .item_numero h2').html(pedido.num);
        paso_3();
        
    }
    
}
function volver_tipo_despacho(){
    
    var dir_ops = $('.direccion_opciones').find('.dir_op');
    
    dir_ops.eq(0).removeClass('selected');
    dir_ops.eq(0).find('.stitle').html("Sin Costo");
    
    dir_ops.eq(1).removeClass('selected');
    dir_ops.eq(1).find('.stitle').html("Desde "+formatNumber.new(parseInt(data.config.desde), "$"));
    
}
function mostrar_add_carro(that){
    console.log(that);
}
function selecciono_retiro(nombre, direccion, total){
    
    var dir_ops = $('.direccion_opciones').find('.dir_op');
        
    dir_ops.eq(0).addClass('selected');
    dir_ops.eq(0).find('.stitle').html(nombre+": "+direccion);

    dir_ops.eq(1).removeClass('selected');
    dir_ops.eq(1).find('.stitle').html("Desde "+formatNumber.new(parseInt(data.config.desde), "$"));
    
    $('.acc_paso2').show();
    $('.paso_03_costo').html('');
    $('.paso_03_total').html('Total: '+formatNumber.new(parseInt(total), "$"));
    $('.block_direccion').hide();
    
}
function selecciono_despacho(calle, num, costo, total){
    
    var dir_ops = $('.direccion_opciones').find('.dir_op');

    dir_ops.eq(0).removeClass('selected');
    dir_ops.eq(0).find('.stitle').html("Sin Costo");

    dir_ops.eq(1).addClass('selected');
    dir_ops.eq(1).find('.stitle').html(calle+" "+num+": "+formatNumber.new(parseInt(costo), "$"));
    
    $('.acc_paso2').show();
    $('.paso_03_costo').html('Despacho: '+formatNumber.new(parseInt(costo), "$"));
    $('.paso_03_total').html('Total: '+formatNumber.new(parseInt(total), "$"));
    $('.block_direccion').show();
    
}
function obj_pedido(){
        
    var pedido = { 
        id_ped: 0,
        pedido_code: '',
        fecha: null, 
        despacho: null,
        nombre: '',
        telefono: '',
        id_loc: 0,
        calle: '', 
        num: 0, 
        depto: '',
        estado: '',
        comuna: '', 
        direccion: '', 
        lat: 0, 
        lng: 0, 
        costo: 0,
        pre_gengibre: 0,
        pre_wasabi: 0,
        pre_embarazadas: 0,
        pre_palitos: 0,
        pre_teriyaki: 0,
        pre_soya: 0,
        comentarios: '',
        total: 0
    }
    return pedido;
    
}
function confirmar_productos_promo(that){
    
    var count = 0;
    var arr = [];
    var parent = $(that).parents('.modal_productos_promo');
    var cantidad = parent.find('.pro_cat_promo').attr('data-cantidad');
    var carro_pos = parent.find('.pro_cat_promo').attr('data-pos');
    var producto;
    var item_carro;
    
    parent.find('.pro_cat_item').each(function(){
        count = count + parseInt($(this).find('.select_promo').val());
        arr.push({id_pro: parseInt($(this).find('.select_promo').attr('id')), cantidad: parseInt($(this).find('.select_promo').val())});
    });
    
    if(count == cantidad){
        var carro = get_carro();
        carro.splice(carro_pos, 1);
        for(var i=0, ilen=arr.length; i<ilen; i++){
            for(var j=0, jlen=arr[i].cantidad; j<jlen; j++){
                
                producto = get_producto(arr[i].id_pro);
                item_carro = { id_pro: parseInt(arr[i].id_pro) };
                if(producto.preguntas){
                    item_carro.preguntas = [];
                    for(var k=0, klen=producto.preguntas.length; k<klen; k++){
                        item_carro.preguntas.push(get_preguntas(producto.preguntas[k]));
                    }
                }
                carro.push(item_carro);
                
            }
        }
        localStorage.setItem("carro", JSON.stringify(carro));
        // IMPORTANTE
        if(proceso(true, false)){
            process_carro();
        }
        if(proceso(true, true)){
            hide_modal();
        }
        
    }else{
        
        var diff = cantidad - count;
        if(diff == 1){
            alert("FALTA 1 PRODUCTO");
        }
        if(diff > 1){
            alert("FALTA "+diff+" PRODUCTOS");
        }
        if(diff == -1){
            alert("SOBRA 1 PRODUCTO");
        }
        if(diff < -1){
            alert("SOBRA "+Math.abs(diff)+" PRODUCTOS");
        }
        
    }
     
}
function aux_nuevo(){
    borrar_carro();
    set_pedido(null);
    $('.cantcart_num').html(0);
    hide_modal();
    volver_tipo_despacho();
    cantidad = 0;
    $('.cantcart_num').html(cantidad);
    
    $('#pedido_wasabi').attr('checked', false);
    $('#pedido_gengibre').attr('checked', false);
    $('#pedido_embarazadas').attr('checked', false);
    $('#pedido_soya').attr('checked', false);
    $('#pedido_teriyaki').attr('checked', false);
    $('#pedido_palitos option[value=0]').attr('selected', 'selected');
    $('#pedido_comentarios').val('');
    $('#pac-input').val("");
    // VOLVER A LA NORMALIDAD
}
var formatNumber = {
    separador: ".", // separador para los miles
    sepDecimal: ',', // separador para los decimales
    formatear: function(num){
        num +='';
        var splitStr = num.split('.');
        var splitLeft = splitStr[0];
        var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
        var regx = /(\d+)(\d{3})/;
        while (regx.test(splitLeft)) {
            splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
        }
        return this.simbol + splitLeft +splitRight;
    },
    new: function(num, simbol){
        this.simbol = simbol ||'';
        return this.formatear(num);
    }
}