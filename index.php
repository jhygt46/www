<?php

    if(isset($_POST["test"]) && $_POST["test"] == "Dw7k2s_hKi5sqPs8"){
        die("hjS3r%mDs-5gYa6ib_5Ps");
    }

    if($_GET["mode"] != "developer" && $_POST["accion"] != "xS3w1Dm8Po87Wltd"){
        die("SITIO EN CONSTRUCCION");
    }

    require_once "class/core_class.php";
    $core = new Core();
    
    if(isset($_POST["accion"]) && $_POST["accion"] == "xS3w1Dm8Po87Wltd"){
        $core->actualizar();
    }
    /*
    if(isset($_POST["accion"]) && $_POST["accion"] == "uDr72rE2kIt9z5bF"){
        $core->volver();
    }
    */

    $info = $core->get_data();
    if($info === null){
        die("<table border='0' width='100%' height='100%'><tr><td align='center' valign='middle'>Sitio no existe</td></tr></table>");
    }else{
        if($info->{'item_pagina'} == 0){
            die("<table border='0' width='100%' height='100%'><tr><td align='center' valign='middle'>Sitio no disponible</td></tr></table>");
        }
    }

    if ((empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") && $info->{'ssl'} == 1) {
        $location = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $location);
        exit;
    }
    if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on") && $info->{'ssl'} == 0) {
        $location = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        header('HTTP/1.1 302 Moved Temporarily');
        header('Location: ' . $location);
        exit;
    }

    $horas = (isset($_GET['horas'])) ? $_GET['horas'] : 0 ;

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $info->{"titulo"}; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=<?php echo $info->{"font"}->{'family'}; ?>" rel="stylesheet">
        
        <link rel="stylesheet" href="/css/reset.css" media="all" />
        <link rel="stylesheet" href="/css/<?php echo $info->{"css_font_size"}; ?>" media="all" />
        <link rel="stylesheet" href="/css/<?php echo $info->{"css_color"}; ?>" media="all" />
        <link rel="stylesheet" href="/css/<?php echo $info->{"css_tipo"}; ?>" media="all" />
        <link rel="stylesheet" href="/css/css_base.css" media="all" />
        <?php if($info->{"favicon"} == "default.ico"){ ?>
            <link rel='shortcut icon' type='image/x-icon' href='default.ico' />
        <?php }else{ ?>
            <link rel='shortcut icon' type='image/x-icon' href='/data/<?php echo $info->{'code'}; ?>/<?php echo $info->{"favicon"}; ?>' />
        <?php } ?>
        
        
        <script src="https://www.izusushi.cl/socket.io/socket.io.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

        <script src="/data/<?php echo $info->{'code'}; ?>/index.js" type="text/javascript"></script>
        <script src="/js/html_func.js" type="text/javascript"></script>
        <script src="/js/base.js" type="text/javascript"></script>
        <script src="/js/base_lista.js" type="text/javascript"></script>
        <script>
            var inicio = <?php echo ($info->{"inicio_html"} == "") ? 0 : 1 ; ?>;
            var code = "<?php echo $info->{'code'}; ?>";
            var estados = [ <?php for($i=0; $i<count($info->{'estado'}); $i++){ if($i>0){ echo ", "; } echo "'".$info->{'estado'}[$i]."'";  } ?> ];
            var fecha_js = new Date(<?php echo (time() + $horas * 60 * 60); ?>000).getTime();
            var fecha_pc = new Date().getTime();
        </script>
        <style>
            body{
                font-family: "<?php echo $info->{"font"}->{"css"}; ?>";
            }
        </style>
    </head>
    <body>
        <div class="contenedor">
            <div class="menu_left">
                <div class="cont_menu_left">
                    <div class="btn_toogle material-icons" onclick="tooglemenu()">view_headline</div>
                    <div class="menu_info">
                        <h1 class="font1">Men&uacute;</h1>
                        <ul class="lista_paginas">
                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="pagina">
                <div class="cont_pagina">
                    <div class="header <?php echo ($info->{"header_fixed"} == 1) ? 'fixed' : ''; ?>">
                        <div class="header_logo vhalign"><img src="<?php if($info->{'logo'} == "sinlogo.png"){ echo "/_images/sinlogo.png"; }else{ echo "/data/".$info->{"code"}."/".$info->{"logo"}; } ?>" alt="" /></div>
                        <div class="menu_right valign" onclick="open_carro()"><div class="shop material-icons">shopping_cart</div><div class="cantcart"><div class="cantcart_num vhalign"></div></div></div>
                    </div>
                    <div class="contenido">
                        <div class="cont_contenido <?php echo ($info->{"footer_fixed"} == 1) ? 'padding_cont_f1' : 'padding_cont_f2'; ?>"></div>
                    </div>
                    <div class="footer <?php echo ($info->{"footer_fixed"} == 1) ? 'fixed' : ''; ?>"><?php echo $info->{"footer_html"}; ?></div>
                </div>
            </div>
            <div class="modals">
                <div class="cont_modals">
                    <?php echo $info->{"inicio_html"}; ?>
                    <div class="modal modal_dim1 vhalign hide modal_contacto">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="font1">Contacto</h1><h2 class="font3">Tienes alguna duda? contactate con nosotros</h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal">
                                    <div class="cont_final">
                                        <div class="input_contacto">
                                            <h1 class="font3">Nombre</h1>
                                            <div class="d_input">
                                                <input type="text" id="contacto_nombre" />
                                            </div>
                                        </div>
                                        <div class="input_contacto">
                                            <h1 class="font3">Telefono</h1>
                                            <div class="d_input">
                                                <input type="text" id="contacto_telefono" />
                                            </div>
                                        </div>
                                        <div class="input_contacto">
                                            <h1 class="font3">Correo</h1>
                                            <div class="d_input">
                                                <input type="text" id="contacto_correo" />
                                            </div>
                                        </div>
                                        <div class="input_contacto">
                                            <h1 class="font3">Comentario</h1>
                                            <div class="d_input">
                                                <Textarea id="contacto_comentario" style="width: 100%; height: 70px; border: 0px"></Textarea>
                                            </div>
                                        </div>
                                        <div class="input_enviar">
                                            <input class="confirmar font4" id="enviar_contacto" onclick="enviar_contacto()" type="button" value="Enviar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal modal_dim2 vhalign hide modal_error">
                        <div class="cont_modal">
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="cont_err">
                                    <div class="cont_ab_err valign">
                                        <div class="titulo_error font1">Lo sentimos, tu pedido no pudo ser enviado</div>
                                        <div class="btns_err clearfix">
                                            <a id="err_telefono" class="btn_err btn_err1 font5" href="">Llamar al local</a>
                                            <a id="err_correo" class="btn_err btn_err2 font5" href="">Enviar por mail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal modal_dim1 vhalign hide modal_error_locales">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="font1">Ocurrio un Error</h1><h2 class="font3">contactese directamente a nuestras sucursales</h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal">
                                    <div class="cont_direccion">
                                        <div class="direccion_op1">
                                            <?php
                                            $locales = json_decode($info->{'lista_locales'});
                                            for($i=0; $i<count($locales); $i++){ ?>
                                            <div class="parent_locales">
                                                <div class="dir_locales" id="<?php echo $locales[$i]->{'id_loc'}; ?>" <?php if($locales[$i]->{'image'} != ""){ ?>style="background-image: url('<?php echo "/data/".$info->{"code"}."/".$locales[$i]->{'image'}; ?>')" <?php } ?>>
                                                    <div class="cont_local prin_alpha_1">
                                                        <div class="left_acciones">
                                                            <div class="local_info valign">
                                                                <div class="title font1"><?php echo $locales[$i]->{'nombre'}; ?></div>
                                                                <div class="stitle font4"><?php echo $locales[$i]->{'direccion'}; ?></div>
                                                                <div class="alert font4"></div>
                                                            </div>
                                                        </div>
                                                        <div class="accioness valign">
                                                            <a class="accion ver_mapa" style="background-image: url('../_images/telefono.png')" href="tel:<?php echo $locales[$i]->{'telefono'}; ?>"></a>
                                                            <a class="accion ver_mapa" style="background-image: url('../_images/whatsapp.png')" href="https://api.whatsapp.com/send?phone=<?php echo $locales[$i]->{'whatsapp'}; ?>"></a>
                                                            <div class="accion ver_mapa" style="background-image: url('../_images/mapa.png')" onclick="local_mapa(<?php echo $locales[$i]->{'id_loc'}; ?>, <?php echo $locales[$i]->{'lat'}; ?>, <?php echo $locales[$i]->{'lng'}; ?>, 'r')"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="cont_map" id="contmapr-<?php echo $locales[$i]->{'id_loc'}; ?>">
                                                    <div class="dir_map" id="lmap-<?php echo $locales[$i]->{'id_loc'}; ?>"></div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal modal_dim1 vhalign hide modal_pagina">
                        <div class="cont_modal">
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info"></div>
                        </div>
                    </div>
                    <div class="modal modal_dim1 vhalign hide modal_carta">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="font1"></h1><h2 class="font3"></h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal modal_dim1 vhalign hide modal_productos_promo">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="font1"></h1><h2 class="font3"></h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal" style="padding-bottom: 57px"></div>
                            </div>
                            <div class="acciones">
                                <input class="confirmar" onclick="confirmar_productos_promo(this)" type="button" value="Confirmar" />
                            </div>
                        </div>
                    </div>
                    <div class="modal modal_dim1 vhalign hide modal_pregunta_productos">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="font1"></h1><h2 class="font3"></h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal" style="padding-bottom: 57px"></div>
                            </div>
                            <div class="acciones">
                                <input class="confirmar" onclick="confirmar_pregunta_productos(this)" type="button" value="Confirmar" />
                            </div>
                        </div>
                    </div>
                    <!-- MODAL CARRO 01 -->
                    <div class="modal modal_dim1 vhalign hide modal_carro paso_01">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="font1"><?php echo $info->{"pedido_01_titulo"}; ?></h1><h2 class="font3"><?php echo $info->{"pedido_01_subtitulo"}; ?></h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal" style="padding-bottom: 57px"></div>
                            </div>
                            <div class="sub_total">
                                <div class="cont_subtotal">
                                    <ul class="total_detalle valign">
                                        <li class="paso_01_sub_total font2"></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="acciones">
                                <input class="confirmar" onclick="paso_2()" type="button" value="Siguiente" />
                            </div>
                        </div>
                    </div>
                    
                    <?php if($info->{'retiro_local'} == 1 && $info->{'despacho_domicilio'} == 1){ ?>
                    <div class="modal modal_dim1 vhalign hide modal_carro paso_02">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="font1"><?php echo $info->{"pedido_02_titulo"}; ?></h1><h2 class="font3"><?php echo $info->{"pedido_02_subtitulo"}; ?></h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal">
                                    <div class="cont_direccion">
                                        <div class="direccion_opciones">
                                            <div class="rlocal dir_op" onclick="show_modal_locales()" style="background-image: url('<?php if($info->{'foto_retiro'} == ""){ echo "/_images/retiro.jpg"; }else{ echo "/data/".$info->{"code"}."/".$info->{"foto_retiro"}; } ?>')">
                                                <div class="codir prin_alpha_1">
                                                    <div class="cont_info_dir valign">
                                                        <div class="title font1">Retiro en Local</div>
                                                        <div class="stitle font4">Sin Costo</div>
                                                        <div class="alert font4">Locales Cerrados</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="cdesp dir_op" onclick="show_despacho()" style="background-image: url('<?php if($info->{'foto_despacho'} == ""){ echo "/_images/despacho.jpg"; }else{ echo "/data/".$info->{"code"}."/".$info->{"foto_despacho"}; } ?>')">
                                                <div class="codir prin_alpha_1">
                                                    <div class="cont_info_dir valign">
                                                        <div class="title font1">Despacho a Domicilio</div>
                                                        <div class="stitle font4">Desde $<?php echo $info->{"desde"}; ?></div>
                                                        <div class="alert font4"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="acciones acc_paso2 hide">
                                <input class="confirmar" onclick="paso_3()" type="button" value="Siguiente" />
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($info->{'retiro_local'} == 1){ ?>
                    <div class="modal modal_dim1 vhalign hide modal_carro paso_02a">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="font1"><?php echo $info->{"pedido_02_titulo"}; ?></h1><h2 class="font3"><?php echo $info->{"pedido_02_subtitulo"}; ?></h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal">
                                    <div class="cont_direccion">
                                        <div class="direccion_op1">
                                            <?php
                                            $locales = json_decode($info->{'lista_locales'});
                                            for($i=0; $i<count($locales); $i++){
                                            ?>
                                            <div class="parent_locales">
                                                <div class="dir_locales" id="<?php echo $locales[$i]->{'id_loc'}; ?>" <?php if($locales[$i]->{'image'} != ""){ ?>style="background-image: url('<?php echo "/data/".$info->{"code"}."/".$locales[$i]->{'image'}; ?>')" <?php } ?>>
                                                    <div class="cont_local prin_alpha_1">
                                                        <div class="left_acciones" onclick="select_local(<?php echo $locales[$i]->{'id_loc'}; ?>, '<?php echo $locales[$i]->{'nombre'}; ?>', '<?php echo $locales[$i]->{'direccion'}; ?>')">
                                                            <div class="local_info valign">
                                                                <div class="title font1"><?php echo $locales[$i]->{'nombre'}; ?></div>
                                                                <div class="stitle font4"><?php echo $locales[$i]->{'direccion'}; ?></div>
                                                                <div class="alert font5"></div>
                                                            </div>
                                                        </div>
                                                        <div class="accioness valign">
                                                            <a class="accion ver_mapa" style="background-image: url('../_images/telefono.png')" href="tel:<?php echo $locales[$i]->{'telefono'}; ?>"></a>
                                                            <a class="accion ver_mapa" style="background-image: url('../_images/whatsapp.png')" href="https://api.whatsapp.com/send?phone=<?php echo $locales[$i]->{'whatsapp'}; ?>"></a>
                                                            <div class="accion ver_mapa" style="background-image: url('../_images/mapa.png')" onclick="local_mapa(<?php echo $locales[$i]->{'id_loc'}; ?>, <?php echo $locales[$i]->{'lat'}; ?>, <?php echo $locales[$i]->{'lng'}; ?>, 's')"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="cont_map" id="contmaps-<?php echo $locales[$i]->{'id_loc'}; ?>">
                                                    <div class="dir_map" id="lmap-<?php echo $locales[$i]->{'id_loc'}; ?>"></div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($info->{'despacho_domicilio'} == 1){ ?>
                    <div class="modal modal_dim1 vhalign hide modal_carro paso_02b">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="font1"><?php echo $info->{"pedido_02_titulo"}; ?></h1><h2 class="font3"><?php echo $info->{"pedido_02_subtitulo"}; ?></h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal">
                                    <div class="cont_direccion">
                                        <div class="direccion_op2">
                                            <input type="text" id="pac-input" placeholder="Ingrese su direccion y numero" />
                                            <div id="map_direccion" style="height: 100%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="acciones" id="siguiente_map">
                                <input class="confirmar" onclick="paso_3()" type="button" value="Siguiente">
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="modal modal_dim1 vhalign hide modal_carro paso_03">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="font1"><?php echo $info->{"pedido_03_titulo"}; ?></h1><h2 class="font3"><?php echo $info->{"pedido_03_subtitulo"}; ?></h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal" style="padding-bottom: 116px">
                                    
                                    <div class="cont_final">
                                        <ul class="block_direccion clearfix">
                                            <li class="item_direccion"><h1 class="font3">Direccion: </h1><h2></h2></li>
                                            <li class="item_numero"><h1 class="font3">Numero: </h1><h2></h2></li>
                                            <li class="item_depto"><h1 class="font3">Depto: </h1><input type="text" id="pedido_depto" /></li>
                                        </ul>
                                        <ul class="block_nombre_telefono clearfix">
                                            <li class="item_nombre"><h1 class="font3">Nombre:</h1><input type="text" id="pedido_nombre" /></li>
                                                <li class="item_telefono"><h1 class="font3">Telefono:</h1><input type="text" id="pedido_telefono" value="+569" /></li>
                                        </ul>
                                        <?php if($info->{'pedido_wasabi'} == 1 || $info->{'pedido_gengibre'} == 1 || $info->{'pedido_embarazadas'} == 1 || $info->{'pedido_palitos'} == 1){ ?>
                                        <div class="block_preguntas">
                                            <h1 class="font4">Opciones</h1>
                                            <div class="preguntas">
                                                <?php if($info->{'pedido_wasabi'} == 1){ ?>
                                                <ul class="pregunta clearfix">
                                                    <li class="pre_nom font4">Wasabi</li>
                                                    <li class="pre_input"><input type="checkbox" id="pedido_wasabi" /></li>
                                                </ul>
                                                <?php } ?>
                                                <?php if($info->{'pedido_gengibre'} == 1){ ?>
                                                <ul class="pregunta clearfix">
                                                    <li class="pre_nom font4">Gengibre</li>
                                                    <li class="pre_input"><input type="checkbox" id="pedido_gengibre" /></li>
                                                </ul>
                                                <?php } ?>
                                                <?php if($info->{'pedido_embarazadas'} == 1){ ?>
                                                <ul class="pregunta clearfix">
                                                    <li class="pre_nom font4">Es para Embarazada?</li>
                                                    <li class="pre_input"><input type="checkbox" id="pedido_embarazadas" /></li>
                                                </ul>
                                                <?php } ?>
                                                <?php if($info->{'pedido_soya'} == 1){ ?>
                                                <ul class="pregunta clearfix">
                                                    <li class="pre_nom font4">Soya</li>
                                                    <li class="pre_input"><input type="checkbox" id="pedido_soya" /></li>
                                                </ul>
                                                <?php } ?>
                                                <?php if($info->{'pedido_teriyaki'} == 1){ ?>
                                                <ul class="pregunta clearfix">
                                                    <li class="pre_nom font4">Teriyaki</li>
                                                    <li class="pre_input"><input type="checkbox" id="pedido_teriyaki" /></li>
                                                </ul>
                                                <?php } ?>
                                                <?php if($info->{'pedido_palitos'} == 1){ ?>
                                                <ul class="pregunta clearfix">
                                                    <li class="pre_nom font4">Palitos</li>
                                                    <li class="pre_input"><select id="pedido_palitos"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option></select></li>
                                                </ul>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if($info->{'pedido_comentarios'} == 1){ ?>
                                        <div class="block_preguntas">
                                            <h1 class="font3">Comentarios</h1>
                                            <div class="preguntas">
                                                <Textarea id="pedido_comentarios" style="width: 100%; height: 70px; border: 0px"></Textarea>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="sub_total">
                                <div class="cont_subtotal">
                                    <ul class="total_detalle valign">
                                        <li class="paso_03_costo font2"></li>
                                        <li class="paso_03_total font2"></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="acciones">
                                <input class="confirmar" id="enviar_cotizacion" onclick="paso_4()" type="button" value="Finalizar" />
                            </div>
                        </div>
                    </div>
                    <div class="modal modal_dim1 vhalign hide modal_carro paso_04">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="font1"></h1><h2 class="font3"></h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal" style="padding-bottom: 57px">
                                    <div class="pedido_mensaje"></div>
                                    <div class="pedido_final">
                                        <div class="cont_pedido clearfix">
                                            <div class="estado"><h1 class="font1">Estado</h1><h2 class="font5"></h2></div>
                                            <div class="tiempo"><h1 class="font1">Tiempo Restante</h1><h2 class="font5"></h2></div>
                                        </div>
                                        <div class="posicion" id="mapa_posicion"></div>
                                        <div class="total font1"></div>
                                    </div>
                                    <div class="pedido_chat" style="display: none">
                                        <div class="mensajes">
                                            <div class="info_mensajes"></div>
                                        </div>
                                        <div class="entrada"><input type="text" id="texto_chat" /></div>
                                        <div class="enviar" onclick="send_chat()"><div class="enviar_txt vhalign">Enviar</div></div>
                                    </div>
                                </div>
                            </div>
                            <div class="acciones">
                                <input class="confirmar" onclick="aux_nuevo()" type="button" value="Hacer Nuevo Pedido" />
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <script src="https://www.google.com/recaptcha/api.js?render=6LdZp78UAAAAAK56zJAVEkaSupUdCrRhsd1wnKkO"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $info->{'mapcode'}; ?>&libraries=places" async defer></script>
    </body>
</html>