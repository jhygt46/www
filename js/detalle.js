$(document).ready(function(){
    
    render_items(carro, promos);
    
    var total_process = 0;
    for(var i=0, ilen=carro.length; i<ilen; i++){
        if(!carro[i].hasOwnProperty('promo')){
            total_process = total_process + parseInt(get_producto(carro[i].id_pro).precio);
        }
    }
    for(var i=0, ilen=promos.length; i<ilen; i++){
        total_process = total_process + parseInt(get_categoria(promos[i].id_cae).precio);
    }
    var diff = costo + total_process - total; 
    if(diff > 0){
        $('.verificar').append('<div>ERROR: TOTAL ENVIADO ES MENOR: ($'+diff+')</div>')
    }
    if(tc == 1){
        window.print();
        window.close();
    }
});

function create_item(item){
    
    var producto = get_producto(item.id_pro);
    var Div = document.createElement('div');
    Div.className = 'producto';
    
    var Dnombre = document.createElement('div');
    Dnombre.className = 'nombre';

    if(producto.numero == 0){
        if(producto.nombre_carro == ""){
            Dnombre.innerHTML = producto.nombre;
        }else{
            Dnombre.innerHTML = producto.nombre_carro;
        }
    }
    if(producto.numero > 0){
        if(data.config.mostrar_numero == 1){
            Dnombre.innerHTML = producto.numero+".- ";
        }
        if(producto.nombre_carro == ""){
            Dnombre.innerHTML += producto.nombre;
        }else{
            Dnombre.innerHTML += producto.nombre_carro;
        }
    }
    
    Div.appendChild(Dnombre);
    
    if(item.preguntas){
        for(var j=0, jlen=item.preguntas.length; j<jlen; j++){
            for(var k=0, klen=item.preguntas[j].valores.length; k<klen; k++){
                var Dpregunta = document.createElement('div');
                Dpregunta.className = 'pregunta';
                Dpregunta.innerHTML = item.preguntas[j].valores[k].nombre+": ";
                if(item.preguntas[j].valores[k].seleccionados !== undefined){
                    Dpregunta.innerHTML += item.preguntas[j].valores[k].seleccionados.join('/');
                }else{
                    Dpregunta.innerHTML += '<b>No Definido</b>';
                }
                Div.appendChild(Dpregunta);
            }
        }
    }

    return Div;
    
}
function render_items(carro, promos){

    for(var i=0, ilen=promos.length; i<ilen; i++){
        $('.lista_de_productos').append("<div style='text-align: center; font-size: 26px; padding-top: 10px'>"+get_categoria(promos[i].id_cae).nombre+"</div>");
        for(var j=0, jlen=carro.length; j<jlen; j++){
            if(carro[j].hasOwnProperty('promo')){
                if(carro[j].promo == i){
                    $('.lista_de_productos').append(create_item(carro[j]));
                }
            }
        }
    }

    var x = 0;
    for(var i=0, ilen=carro.length; i<ilen; i++){
        if(!carro[i].hasOwnProperty('promo')){
            if(x == 0){
                x = 1;
                $('.lista_de_productos').append("<div style='padding-bottom: 10px; padding-top: 15px; display: block; font-size: 26px'>Lista de Productos</div>");
            }
            $('.lista_de_productos').append(create_item(carro[i]));
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
function get_categoria(id_cae){
    var categorias = data.catalogos[catalogo].categorias;
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].id_cae == id_cae){
            return categorias[i];
        }
    }
}