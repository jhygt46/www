// IMPRIME CATEGORIAS Y PAGINAS EN HOME //
function html_home_categorias(obj, num){

    //console.log(obj);
    var Div = document.createElement('div');
    Div.className = 'botones_principales';
    
    if(obj.image == ""){
        Div.style.backgroundImage = 'url("/_images/cat_'+num+'.jpg")';
    }else{
        Div.style.backgroundImage = 'url("/data/'+code+'/'+obj.image+'")';
    }

    Div.onclick = function(){ open_categoria(obj.id_cae) };
    var Divbp = document.createElement('div');

    if(obj.degradado == 0){
        Divbp.className = 'cont_bp';
    }else{
        Divbp.className = 'cont_bp prin_alpha_'+obj.degradado;
    }

    var Divvalign = document.createElement('div');
    Divvalign.className = 'cont_bpi valign';

    var Divnombre = document.createElement('div');
    Divnombre.innerHTML = obj.nombre;
    Divnombre.className = 'nombre font1';
    Divvalign.appendChild(Divnombre);
    
    if(obj.descripcion){
        var Divdescripcion = document.createElement('div');
        Divdescripcion.innerHTML = obj.descripcion;
        Divdescripcion.className = 'descripcion font3';
        Divvalign.appendChild(Divdescripcion);
    }
    if(obj.precio > 0){
        var Divprecio = document.createElement('div');
        Divprecio.innerHTML = formatNumber.new(parseInt(obj.precio), "$");
        Divprecio.className = 'precio font3';
        Divvalign.appendChild(Divprecio);
    }

    Divbp.appendChild(Divvalign);
    Div.appendChild(Divbp);
    return Div;

}
function html_paginas(pagina){
    
    li = document.createElement('LI');
    li.onclick = function(){ ver_pagina(pagina.id_pag) };
    li.innerHTML = pagina.nombre;
    li.className = 'font4';
    return li;
    
}

// HTML MODALS //
function create_html_categorias(obj){

    //console.log(obj);

    var Div = document.createElement('div');
    Div.className = 'categoria';
    if(obj.image != ""){
        Div.style.backgroundImage = 'url("/data/'+code+'/'+obj.image+'")';
    }
    var Divm = document.createElement('div');


    if(obj.degradado == 0){
        Divm.className = 'cont_fondo';
    }else{
        Divm.className = 'cont_fondo prin_alpha_'+obj.degradado;
    }

    if(obj.mostrar_prods == 0){

        Divm.onclick = function(){ open_categoria(obj.id_cae) };
        Divm.style.height = parseInt((widthpx - 20) * data.config.alto / 100)+"px";

        var Nombre = document.createElement('div');
        Nombre.className = 'nombre font1';
        Nombre.innerHTML = obj.nombre;
        
        var Descripcion = document.createElement('div');
        Descripcion.className = 'descripcion font4';
        Descripcion.innerHTML = obj.descripcion;

        Divm.appendChild(Nombre);
        Divm.appendChild(Descripcion);

    }

    if(obj.mostrar_prods == 1){
        
        var Nombre = document.createElement('div');
        Nombre.className = 'nombre font1';
        Nombre.innerHTML = obj.nombre;

        Divm.appendChild(Nombre);
        var Descripcion = document.createElement('div');
        Descripcion.className = 'descripcion font4';
        Descripcion.innerHTML = obj.descripcion;
        Divm.appendChild(Descripcion);

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
                p_nombre_ttl.className = 'prod_i_ttl font4';
                p_nombre_ttl.innerHTML = aux.nombre;
                p_nombre.appendChild(p_nombre_ttl);

                p_nombre_sttl = document.createElement('div');
                p_nombre_sttl.className = 'prod_i_sttl font5';
                p_nombre_sttl.innerHTML = aux.descripcion;
                p_nombre.appendChild(p_nombre_sttl);

                producto.appendChild(p_nombre);
                
                p_precio = document.createElement('div');
                p_precio.className = 'prod_i_pre valign font3';
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
function create_html_producto(aux){
    
    var Div = document.createElement('div');
    Div.className = 'producto';
    if(aux.disponible == 0){
        Div.onclick = function(){ add_carro_producto(aux.id_pro, 0) };
    }
    if(aux.disponible == 1){
        Div.onclick = function(){ alert("Producto no disponible") };
    }
    var nombre = document.createElement('div');
    nombre.className = 'nombre font2';
    if(aux.numero > 0){
        nombre.innerHTML = aux.numero + '.- ' + aux.nombre;
    }else{
        nombre.innerHTML = aux.nombre;
    }
    Div.appendChild(nombre);

    var descripcion = document.createElement('div');
    descripcion.className = 'descripcion font5';
    if(aux.disponible == 0){
        descripcion.innerHTML = aux.descripcion;
    }
    if(aux.disponible == 1){
        descripcion.innerHTML = aux.descripcion + '<br/><p style="color: #A33; font-size: 11px">Producto no disponible</p>';
    }
    Div.appendChild(descripcion);
    
    var precio = document.createElement('div');
    precio.className = 'precio valign font3';
    precio.innerHTML = formatNumber.new(parseInt(aux.precio), "$");
    Div.appendChild(precio);
    return Div;
    
}
function create_html_producto2(aux){
    
    var height = parseInt((widthpx - 20) * data.config.alto_pro / 100);
    var Div = document.createElement('div');
    Div.className = 'producto2';

    if(aux.disponible == 0){
        if(tipo_add_carro == 0){
            Div.onclick = function(){ add_carro_producto(aux.id_pro, 0) };
        }
        if(tipo_add_carro == 1){
            Div.onclick = function(){ mostrar_add_carro(this) };
        }
    }
    if(aux.disponible == 1){
        Div.onclick = function(){ mostrar_add_carro(this) };
    }

    var Divm = create_element_class('cont_pro clearfix');

    if(height > 100){
        Divm.style.minHeight = height+"px";
    }

    var cont_data = create_element_class('cont_data');
    cont_data.style.width = (widthpx - 20 - height) + "px";

    var nombre = create_element_class('nombre');
    if(aux.numero > 0){
        nombre.innerHTML = aux.numero + '.- ' + aux.nombre;
    }else{
        nombre.innerHTML = aux.nombre;
    }
    if(aux.disponible == 1){
        nombre.innerHTML += "<p style='color: #900; font-size: 10px; padding-left: 4px; display: inline'>No disponible</p>";
    }
    cont_data.appendChild(nombre);
    
    var descripcion = create_element_class('descripcion');
    descripcion.innerHTML = aux.descripcion;
    cont_data.appendChild(descripcion);

    var precio = create_element_class('precio');
    precio.innerHTML = formatNumber.new(parseInt(aux.precio), "$");
    cont_data.appendChild(precio);


    var foto = create_element_class('foto');
    foto.style.width = (height - 10) + "px";
    foto.style.height = (height - 10) + "px";

    if(aux.disponible == 0){
        // CARRO DE COMPRAS //
        var add_carro = create_element_class('carro clearfix');
        var add_carro_btn1 = create_element_class('add_carro_btn');
        add_carro_btn1.onclick = function(){ mostrar_mensaje(this); add_carro_producto(aux.id_pro, 1) };
        var add_carro_btn1a = create_element_class('add_info');
        var add_carro_btn1b = create_element_class('add_logo valign material-icons');
        add_carro_btn1a.innerHTML = 'Agregar a carro';
        add_carro_btn1b.innerHTML = 'shopping_cart';
        add_carro_btn1.appendChild(add_carro_btn1a);
        add_carro_btn1.appendChild(add_carro_btn1b);
        var add_carro_btn2 = create_element_class('add_carro_alert font5');
        add_carro.appendChild(add_carro_btn1);
        add_carro.appendChild(add_carro_btn2);
        // CARRO DE COMPRAS //
    }
    if(aux.disponible == 1){
        var add_carro = create_element_class('carro_no');
        add_carro.innerHTML = 'Producto no Disponible';
    }

    Div.appendChild(tabla(cont_data, foto, add_carro));
    return Div;
    
}
function create_html_producto3(aux){
    
    var height = parseInt((widthpx - 20) * data.config.alto_pro / 100);
    var Div = create_element_class('producto3');

    console.log(aux);

    /*
    if(obj.image == ""){
        Div.style.backgroundImage = 'url("/_images/cat_'+num+'.jpg")';
    }else{
        Div.style.backgroundImage = 'url("/data/'+code+'/'+obj.image+'")';
    }
    */

    if(aux.disponible == 0){
        Div.onclick = function(){ add_carro_producto(aux.id_pro, 0); };
    }
    if(aux.disponible == 1){
        Div.onclick = function(){ alert("Producto no disponible") };
    }

    var Divm = create_element_class('cont_pr prin_alpha_1');
    if(height > 80){
        Divm.style.minHeight = height+"px";
    }

    var Divc = create_element_class('cont_prod valign');
    var nombre = document.createElement('div');
    nombre.className = 'nombre';
    if(aux.numero > 0){
        nombre.innerHTML = aux.numero + '.- ' + aux.nombre;
    }else{
        nombre.innerHTML = aux.nombre;
    }
    if(aux.disponible == 1){
        nombre.innerHTML += "<p style='color: #900; font-size: 10px; padding-left: 4px; display: inline'>No disponible</p>";
    }
    Divc.appendChild(nombre);

    var descripcion = document.createElement('div');
    descripcion.className = 'descripcion';
    descripcion.innerHTML = aux.descripcion;
    Divc.appendChild(descripcion);
    
    var precio = document.createElement('div');
    precio.className = 'precio';
    precio.innerHTML = formatNumber.new(parseInt(aux.precio), "$");
    Divc.appendChild(precio);

    Divm.appendChild(Divc);

    if(aux.disponible == 0){
        // CARRO DE COMPRAS //
        var add_carro = create_element_class('carro clearfix');
        var add_carro_btn1 = create_element_class('add_carro_btn');
        add_carro_btn1.onclick = function(){ mostrar_mensaje(this); add_carro_producto(aux.id_pro, 1) };
        var add_carro_btn1a = create_element_class('add_info');
        var add_carro_btn1b = create_element_class('add_logo valign material-icons');
        add_carro_btn1a.innerHTML = 'Agregar a carro';
        add_carro_btn1b.innerHTML = 'shopping_cart';
        add_carro_btn1.appendChild(add_carro_btn1a);
        add_carro_btn1.appendChild(add_carro_btn1b);
        var add_carro_btn2 = create_element_class('add_carro_alert font5');
        add_carro.appendChild(add_carro_btn1);
        add_carro.appendChild(add_carro_btn2);
        // CARRO DE COMPRAS //
    }
    if(aux.disponible == 1){
        var add_carro = create_element_class('carro_no');
        add_carro.innerHTML = 'Producto no Disponible';
    }

    Div.appendChild(Divm);
    Div.appendChild(add_carro);
    return Div;
    
    
}

function tabla(cont_data, pro_foto, add_carro){

    var tbl = document.createElement('table');
    tbl.setAttribute('border', '0');
    tbl.setAttribute("cellspacing", 0);
    tbl.setAttribute("cellpadding", 0)
    var tbdy = document.createElement('tbody');
    var tr1 = document.createElement('tr');
    var td1 = document.createElement('td');
    td1.setAttribute("valign", "middle");
    var td2 = document.createElement('td');
    td2.setAttribute("valign", "middle");
    var tr2 = document.createElement('tr');
    var td3 = document.createElement('td');
    td3.setAttribute('colSpan', '2');
    td3.style.display = 'none';
    td3.className = 'mostrar_add_carro';
    td3.appendChild(add_carro);
    tr2.appendChild(td3);
    td1.appendChild(cont_data);
    td2.appendChild(pro_foto);
    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tbdy.appendChild(tr1);
    tbdy.appendChild(tr2);
    tbl.appendChild(tbdy);
    return tbl;

}
function create_html_promocion(obj){
    
    var Div = document.createElement('div');
    Div.className = 'promocion';
    Div.onclick = function(){ add_carro_promocion(obj.id_cae) };    
    Div.style.backgroundImage = 'url("/data/'+code+'/'+obj.image+'")';
    
    var Divm = document.createElement('div');
    Divm.style.minHeight = parseInt((widthpx - 20) * data.config.alto / 100)+"px";

    if(obj.degradado == 0){
        Divm.className = 'cont_fondo';
    }else{
        Divm.className = 'cont_fondo prin_alpha_'+obj.degradado;
    }

    var Nombre = document.createElement('div');
    Nombre.className = 'nombre font1';
    Nombre.innerHTML = obj.nombre;
    Divm.appendChild(Nombre);
    
    var Descripcion = document.createElement('div');
    Descripcion.className = 'descripcion font4';
    Descripcion.innerHTML = obj.descripcion;
    Divm.appendChild(Descripcion);
    
    var Precio = document.createElement('div');
    Precio.className = 'precio font1';
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
                cat.className = 'prod_item font4';
                cat.innerHTML = obj.categorias[i].cantidad + " " +aux.nombre;
                listado.appendChild(cat);
            }
        }
        if(obj.productos){
            for(var i=0, ilen=obj.productos.length; i<ilen; i++){
                aux = get_producto(obj.productos[i].id_pro);
                producto = document.createElement('div');
                producto.className = 'prod_item font4';
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
    Nombre.className = 'promo_detalle_nombre font4';


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
    Nombre.className = 'restantes_detalle_nombre font3';
    if(producto.nombre_carro == ""){
        Nombre.innerHTML = producto.nombre;
    }else{
        Nombre.innerHTML = producto.nombre_carro;
    }
    Div.appendChild(Nombre);
    
    var Acciones = document.createElement('div');
    Acciones.className = 'restantes_detalle_acciones clearfix';

    var Precio = document.createElement('div');
    Precio.className = 'precio font3';
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
            pro_cat_item_nombre.className = 'pro_cat_item_nombre font3';
            
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
                pro_cat_item_descripcion.className = 'pro_cat_item_descripcion font4';
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
        pregunta_titulo.className = 'pregunta_titulo font2';
        pregunta_titulo.innerHTML = carro[i].preguntas[k].nombre;
        e_pregunta.appendChild(pregunta_titulo);
        
        
        for(var m=0, mlen=carro[i].preguntas[k].valores.length; m<mlen; m++){
            
            var titulo_v_pregunta = document.createElement('div');
            titulo_v_pregunta.className = 'titulo_v_pregunta font4';
            titulo_v_pregunta.innerHTML = carro[i].preguntas[k].valores[m].nombre;
                        
            var v_pregunta = document.createElement('div');
            v_pregunta.className = 'v_pregunta';
            v_pregunta.setAttribute('data-pos', m);
            v_pregunta.setAttribute('data-cant', carro[i].preguntas[k].valores[m].cantidad);

            for(var n=0, nlen=carro[i].preguntas[k].valores[m].valores.length; n<nlen; n++){
                
                var n_pregunta = document.createElement('div');
                if(carro[i].preguntas[k].valores[m].seleccionados){
                    if(carro[i].preguntas[k].valores[m].seleccionados.indexOf(carro[i].preguntas[k].valores[m].valores[n].trim()) != -1){
                        n_pregunta.className = 'n_pregunta font3 selected';
                    }else{
                        n_pregunta.className = 'n_pregunta font3';
                    }
                }else{
                    n_pregunta.className = 'n_pregunta font3';
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
