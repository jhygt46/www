// IMPRIME CATEGORIAS Y PAGINAS EN HOME //
function html_home_categorias(obj, num){

    var Div = document.createElement('div');
    Div.className = 'botones_principales';
    
    if(obj.image === null){
        Div.style.backgroundImage = 'url("'+dominio+'/images/base/cat_'+num+'.jpg")';
    }else{
        Div.style.backgroundImage = 'url("'+dominio+'/images/categorias/'+obj.image+'")';
    }

    Div.onclick = function(){ open_categoria(obj.id_cae) };
    var Divbp = document.createElement('div');

    if(obj.degradado == 0){
        Divbp.className = 'cont_bp';
    }else{
        Divbp.className = 'cont_bp prin_alpha_'+obj.degradado;
    }

    var Divnombre = document.createElement('div');
    Divnombre.innerHTML = obj.nombre;
    Divnombre.className = 'nombre';
    Divbp.appendChild(Divnombre);
    
    if(obj.descripcion){
        var Divdescripcion = document.createElement('div');
        Divdescripcion.innerHTML = obj.descripcion;
        Divdescripcion.className = 'descripcion';
        Divbp.appendChild(Divdescripcion);
    }
    if(obj.precio > 0){
        var Divprecio = document.createElement('div');
        Divprecio.innerHTML = formatNumber.new(parseInt(obj.precio), "$");
        Divprecio.className = 'precio';
        Divbp.appendChild(Divprecio);
    }

    Div.appendChild(Divbp);
    return Div;

}
function html_paginas(pagina){
    
    li = document.createElement('LI');
    li.onclick = function(){ ver_pagina(pagina.id_pag) };
    li.innerHTML = pagina.nombre;
    return li;
    
}



// HTML MODALS //
function create_html_categorias(obj){


    var Div = document.createElement('div');
    Div.className = 'categoria';
    Div.style.backgroundImage = 'url("'+dominio+'/images/categorias/'+obj.image+'")';

    var Divm = document.createElement('div');

    if(obj.degradado == 0){
        Divm.className = 'cont_fondo';
    }else{
        Divm.className = 'cont_fondo prin_alpha_'+obj.degradado;
    }

    var Nombre = document.createElement('div');
    Nombre.className = 'nombre';
    Nombre.innerHTML = obj.nombre;
    if(obj.mostrar_prods == 0){
        Nombre.onclick = function(){ open_categoria(obj.id_cae) };
    }
    Divm.appendChild(Nombre);
    
    var Descripcion = document.createElement('div');
    Descripcion.className = 'descripcion';
    Descripcion.innerHTML = obj.descripcion;
    if(obj.mostrar_prods == 0){
        Descripcion.onclick = function(){ open_categoria(obj.id_cae) };
    }
    Divm.appendChild(Descripcion);
    
    if(obj.mostrar_prods == 1){
        
        Div.className = 'categoriaopen';
        var listado = document.createElement('div');
        listado.className = 'listado';
        
        if(obj.productos){
            
            var producto, p_nombre, p_precio;
            var aux;
            
            for(var i=0, ilen=obj.productos.length; i<ilen; i++){

                aux = get_producto(obj.productos[i]);

                producto = document.createElement('div');
                producto.className = 'prod_item';
                producto.setAttribute('id', aux.id_pro);
                producto.onclick = function(){ add_carro_producto_aux(this) };
                
                p_nombre = document.createElement('div');
                p_nombre.className = 'prod_i_nom';
                
                p_nombre_ttl = document.createElement('div');
                p_nombre_ttl.className = 'prod_i_ttl';
                p_nombre_ttl.innerHTML = aux.nombre;
                p_nombre.appendChild(p_nombre_ttl);

                p_nombre_sttl = document.createElement('div');
                p_nombre_sttl.className = 'prod_i_sttl';
                p_nombre_sttl.innerHTML = aux.descripcion;
                p_nombre.appendChild(p_nombre_sttl);

                producto.appendChild(p_nombre);
                
                p_precio = document.createElement('div');
                p_precio.className = 'prod_i_pre valign';
                p_precio.innerHTML = formatNumber.new(parseInt(aux.precio), "$");
                producto.appendChild(p_precio);

                listado.appendChild(producto);

            }
        }
        Divm.appendChild(listado);
    }
    Div.appendChild(Divm);
    return Div;
    
}
function create_html_producto(id, detalle){
    
    var aux = get_producto(id);
    if(detalle == 0){
        
        var Div = document.createElement('div');
        Div.className = 'producto';
        Div.onclick = function(){ add_carro_producto(aux.id_pro) };
        
        var nombre = document.createElement('div');
        nombre.className = 'nombre';
        if(aux.numero > 0){
            nombre.innerHTML = aux.numero + '.- ' + aux.nombre;
        }else{
            nombre.innerHTML = aux.nombre;
        }
        Div.appendChild(nombre);

        var descripcion = document.createElement('div');
        descripcion.className = 'descripcion';
        descripcion.innerHTML = aux.descripcion;
        Div.appendChild(descripcion);
        
        var precio = document.createElement('div');
        precio.className = 'precio valign';
        precio.innerHTML = formatNumber.new(parseInt(aux.precio), "$");
        Div.appendChild(precio);
        
    }
    /*
    if(detalle == 1){
        
        var Div = document.createElement('div');
        Div.className = 'categoria';
        
        var detalle = document.createElement('div');
        detalle.className = 'detalle_pro clearfix';
        detalle.onclick = function(){ ver_detalle_producto(this) }
        
        var nombre = document.createElement('div');
        nombre.className = 'nombre_pro';
        nombre.innerHTML = aux.numero + " " + aux.nombre;
        detalle.appendChild(nombre);
        
        var precio = document.createElement('div');
        precio.className = 'precio_pro';
        precio.innerHTML = aux.precio;
        detalle.appendChild(precio);
        
        Div.appendChild(detalle);

        
        var Info = create_element_class('info hide');
        
        var Info_cont = create_element_class('info_cont clearfix');
        var info_01 = create_element_class_inner('info_01', 'INFO 1');
        Info_cont.appendChild(info_01);
        var info_02 = create_element_class_inner('info_02 material-icons', 'shopping_cart');
        Info_cont.appendChild(info_02);
        
        Info.appendChild(Info_cont);
        Div.appendChild(Info);
        
    }
    */
    return Div;
    
}
function create_html_promocion(obj){
    
    var Div = document.createElement('div');
    Div.className = 'promocion';
    Div.onclick = function(){ add_carro_promocion(obj.id_cae) };    
    Div.style.backgroundImage = 'url("'+dominio+'/images/categorias/'+obj.image+'")';
    
    var Divm = document.createElement('div');

    if(obj.degradado == 0){
        Divm.className = 'cont_fondo';
    }else{
        Divm.className = 'cont_fondo prin_alpha_'+obj.degradado;
    }

    var Nombre = document.createElement('div');
    Nombre.className = 'nombre';
    Nombre.innerHTML = obj.nombre;
    Divm.appendChild(Nombre);
    
    var Descripcion = document.createElement('div');
    Descripcion.className = 'descripcion';
    Descripcion.innerHTML = obj.descripcion;
    Divm.appendChild(Descripcion);
    
    var Precio = document.createElement('div');
    Precio.className = 'precio';
    Precio.innerHTML = formatNumber.new(parseInt(obj.precio), "$");
    Divm.appendChild(Precio);
    
    if(obj.mostrar_prods == 1){
        
        var listado = document.createElement('div');
        listado.className = 'listado';
        var producto;
        var cat;
        var aux;
        if(obj.categorias){
            for(var i=0, ilen=obj.categorias.length; i<ilen; i++){
                aux = get_categoria(obj.categorias[i].id_cae);
                cat = document.createElement('div');
                cat.className = 'prod_item';
                cat.innerHTML = obj.categorias[i].cantidad + " " +aux.nombre;
                listado.appendChild(cat);
            }
        }
        if(obj.productos){
            for(var i=0, ilen=obj.productos.length; i<ilen; i++){
                aux = get_producto(obj.productos[i].id_pro);
                producto = document.createElement('div');
                producto.className = 'prod_item';
                producto.innerHTML = obj.productos[i].cantidad + " " +aux.nombre;
                listado.appendChild(producto);
            }
        }
        
        Divm.appendChild(listado);
        
    }
    Div.appendChild(Divm);

    return Div;
    
}


// PROMOS //
function promo_carros(producto, j){
    
    var Div = document.createElement('div');
    Div.className = 'promo_detalle_item clearfix';
    
    var Nombre = document.createElement('div');
    Nombre.className = 'promo_detalle_nombre';


    if(producto.nombre_carro != ""){
        if(producto.numero > 0){
            Nombre.innerHTML = producto.numero + '.- ' + producto.nombre_carro;
        }else{
            Nombre.innerHTML = producto.nombre_carro;
        }
    }else{
        if(producto.numero > 0){
            Nombre.innerHTML = producto.numero + '.- ' + producto.nombre;
        }else{
            Nombre.innerHTML = producto.nombre;
        }
    }
    
    Div.appendChild(Nombre);
    
    var Acciones = document.createElement('div');
    Acciones.className = 'promo_detalle_acciones clearfix';
    
    var carros = get_carro();
    var carro = carros[j];
    
    if(carro.preguntas){
        
        var Accion = document.createElement('div');
        Accion.className = 'accion material-icons';
        Accion.onclick = function(){ mostrar_pregunta(j) };
        
        if(tiene_pregunta(carro)){
            Accion.innerHTML = 'help_outline';
        }else{
            Accion.innerHTML = 'more_horiz';
        }
        
        Acciones.appendChild(Accion);
        
    }
    
    Div.appendChild(Acciones);
    return Div;
    
}
function pregunta(carro){
    
    for(var i=0, ilen=carro.preguntas.length; i<ilen; i++){
        
    }
    
}
function promo_restantes(producto, j, tiene_pregunta){

    var Div = document.createElement('div');
    Div.className = 'restantes_detalle_item clearfix';
    
    var Nombre = document.createElement('div');
    Nombre.className = 'restantes_detalle_nombre';
    if(producto.nombre_carro == ""){
        Nombre.innerHTML = producto.nombre;
    }else{
        Nombre.innerHTML = producto.nombre_carro;
    }
    Div.appendChild(Nombre);
    
    var Acciones = document.createElement('div');
    Acciones.className = 'restantes_detalle_acciones clearfix';

    var Precio = document.createElement('div');
    Precio.className = 'precio';
    Precio.innerHTML = formatNumber.new(parseInt(producto.precio), "$");
    Acciones.appendChild(Precio);
    
    var carros = get_carro();
    var carro = carros[j];
    
    if(carro.preguntas){
    
        var Pregunta = document.createElement('div');
        Pregunta.className = 'pregunta material-icons';
        if(!tiene_pregunta){
            Pregunta.innerHTML = 'more_horiz';
        }else{
            Pregunta.innerHTML = 'help_outline';
        }
        Pregunta.onclick = function(){ mostrar_pregunta(j) };
        Acciones.appendChild(Pregunta);
    
    }else{
        
        var Espacio = document.createElement('div');
        Espacio.className = 'espacio';
        Acciones.appendChild(Espacio);
        
    }
        
    
    
    var Accion = document.createElement('div');
    Accion.className = 'accion material-icons';
    Accion.innerHTML = 'close';
    Accion.onclick = function(){ delete_pro_carro(j) };
    Acciones.appendChild(Accion);
    
    Div.appendChild(Acciones);
    return Div;

    
}
function promo_nombre(promocion){
    
    var Div = document.createElement('div');
    Div.className = 'process_carro_promo';
    
    var Nombre = document.createElement('div');
    Nombre.className = 'promo_info';
    Nombre.innerHTML = promocion.nombre;
    Div.appendChild(Nombre);            
    
    return Div;
    
}

// PROMO DETALLE //
function imprimir_promo_modal(categoria){
    
    var html = document.createElement('div');
    html.className = 'lista_promociones';
    html.onclick = function(){ add_carro_promocion(categoria.id_cae) };
            
    if(categoria.categorias){
        
        var catDiv = document.createElement('div');
        catDiv.className = 'promocion_categoria';
        
        var cattitDiv = document.createElement('div');
        cattitDiv.className = 'pro_titulo';
        cattitDiv.innerHTML = 'Elije:';
        
        catDiv.appendChild(cattitDiv);
        var itemDiv;

        for(var j=0, jlen=categoria.categorias.length; j<jlen; j++){
            
            itemDiv = document.createElement('div');
            itemDiv.className = 'item_pro_cat clearfix';
            
            var cantDiv = document.createElement('div');
            cantDiv.className = 'item_pro_cat_cant';
            cantDiv.innerHTML = categoria.categorias[j].cantidad;
            itemDiv.appendChild(cantDiv);
            
            var nomDiv = document.createElement('div');
            nomDiv.className = 'item_pro_cat_nom';
            nomDiv.innerHTML = get_categoria(categoria.categorias[j].id_cae).nombre;
            itemDiv.appendChild(nomDiv);
            catDiv.appendChild(itemDiv);
            
        }
        
        html.appendChild(catDiv);
    }
    if(categoria.productos){
        
        var proDiv = document.createElement('div');
        proDiv.className = 'promocion_producto';
        
        var protitDiv = document.createElement('div');
        protitDiv.className = 'pro_titulo';
        protitDiv.innerHTML = 'Productos';
        
        proDiv.appendChild(protitDiv);
        var itemDiv;
        
        for(var j=0, jlen=categoria.productos.length; j<jlen; j++){
            
            itemDiv = document.createElement('div');
            itemDiv.className = 'item_pro_pro';
            itemDiv.innerHTML = get_producto(categoria.productos[j].id_pro).nombre;
            proDiv.appendChild(itemDiv);
            
        }
        
        html.appendChild(proDiv);
    }

    //html += '<div class="promo_precio">$9.990</div></div>';
    return html;
    
}

// PREGUNTAS PRODUCTOS //
function html_seleccionar_productos_categoria_promo(categoria, i, cantidad){
    
    var producto;
    var pro_cat_item, pro_cat_item_select, pro_cat_item_nombre, select, option;
    console.log(categoria);
    
    if(categoria.productos){
        
        var html = document.createElement('div');
        html.className = 'pro_cat_promo';
        html.setAttribute('data-pos', i);
        html.setAttribute('data-cantidad', cantidad);
        
        for(var i=0, ilen=categoria.productos.length; i<ilen; i++){
            
            producto = get_producto(categoria.productos[i]);
            
            pro_cat_item = document.createElement('div');
            pro_cat_item.className = 'pro_cat_item';
            
            pro_cat_item_select = document.createElement('div');
            pro_cat_item_select.className = 'pro_cat_item_select valign';
            
            select = document.createElement("select");
            select.id = categoria.productos[i];
            select.className = 'select_promo';
            
            for(var j=0; j<=cantidad; j++){
                option = document.createElement("option");
                option.value = j;
                option.text = j;
                select.appendChild(option);
            }
            
            pro_cat_item_select.appendChild(select);
            pro_cat_item.appendChild(pro_cat_item_select);
            
            pro_cat_item_nombre = document.createElement('div');
            pro_cat_item_nombre.className = 'pro_cat_item_nombre';
            
            if(producto.nombre_carro != ""){
                if(producto.numero > 0){
                    pro_cat_item_nombre.innerHTML = producto.numero + '.- ' + producto.nombre_carro;
                }else{
                    pro_cat_item_nombre.innerHTML = producto.nombre_carro;
                }
                pro_cat_item.appendChild(pro_cat_item_nombre);
            }else{
                if(producto.numero > 0){
                    pro_cat_item_nombre.innerHTML = producto.numero + '.- ' + producto.nombre;
                }else{
                    pro_cat_item_nombre.innerHTML = producto.nombre;
                }
                pro_cat_item.appendChild(pro_cat_item_nombre);
                pro_cat_item_descripcion = document.createElement('div');
                pro_cat_item_descripcion.className = 'pro_cat_item_descripcion';
                pro_cat_item_descripcion.innerHTML = producto.descripcion;
                pro_cat_item.appendChild(pro_cat_item_descripcion);
            }            

            html.appendChild(pro_cat_item);
            
        }
        
    }
    return html;
    
}

function html_preguntas_producto(i){
    
    var carro = get_carro();
    var html = document.createElement('div');
    html.className = 's_pregunta';
    html.setAttribute('data-pos', i);
    
    for(var k=0, klen=carro[i].preguntas.length; k<klen; k++){
        
        var e_pregunta = document.createElement('div');
        e_pregunta.className = 'e_pregunta';
        e_pregunta.setAttribute('data-pos', k);
        
        var pregunta_titulo = document.createElement('div');
        pregunta_titulo.className = 'pregunta_titulo';
        pregunta_titulo.innerHTML = carro[i].preguntas[k].nombre;
        e_pregunta.appendChild(pregunta_titulo);
        
        
        for(var m=0, mlen=carro[i].preguntas[k].valores.length; m<mlen; m++){
            
            var titulo_v_pregunta = document.createElement('div');
            titulo_v_pregunta.className = 'titulo_v_pregunta';
            titulo_v_pregunta.innerHTML = carro[i].preguntas[k].valores[m].nombre;
                        
            var v_pregunta = document.createElement('div');
            v_pregunta.className = 'v_pregunta';
            v_pregunta.setAttribute('data-pos', m);
            v_pregunta.setAttribute('data-cant', carro[i].preguntas[k].valores[m].cantidad);

            for(var n=0, nlen=carro[i].preguntas[k].valores[m].valores.length; n<nlen; n++){
                
                var n_pregunta = document.createElement('div');
                if(carro[i].preguntas[k].valores[m].seleccionados){
                    /*
                    console.log(carro[i].preguntas[k].valores[m].seleccionados);
                    console.log(carro[i].preguntas[k].valores[m].valores[n]);
                    */
                    if(carro[i].preguntas[k].valores[m].seleccionados.indexOf(carro[i].preguntas[k].valores[m].valores[n].trim()) != -1){
                        n_pregunta.className = 'n_pregunta selected';
                    }else{
                        n_pregunta.className = 'n_pregunta';
                    }

                }else{
                    n_pregunta.className = 'n_pregunta';
                }

                n_pregunta.innerHTML = carro[i].preguntas[k].valores[m].valores[n];
                n_pregunta.onclick = function(){ select_pregunta(this) };
                v_pregunta.appendChild(n_pregunta);
                
            }
            
            e_pregunta.appendChild(titulo_v_pregunta);
            e_pregunta.appendChild(v_pregunta);
            
        }
        html.appendChild(e_pregunta);
        
    }
    return html;
    
}

// VER DETALLE PRODUCTO//
function ver_detalle_producto(that){
    if(that.parentElement.childNodes[1].style.display == 'block'){
        that.parentElement.childNodes[1].style.display = 'none';
    }else{
        that.parentElement.childNodes[1].style.display = 'block';
    }
}



// AUX CREAR ELEMENTOS // 
function create_element_class(clase){
    var Div = document.createElement('div');
    Div.className = clase;
    return Div;
}
function create_element_class_inner(clase, value){
    var Div = document.createElement('div');
    Div.className = clase;
    Div.innerHTML = value;
    return Div;
}
