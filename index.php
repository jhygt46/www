<?php

    require_once "class/core_class.php";

    $core = new Core();
    $info = $core->get_data();
    
    /*
    echo "<pre>";
    print_r($info);
    echo "</pre>";
    exit;
    */

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
        
        <link rel='shortcut icon' type='image/x-icon' href='/images/favicon/<?php echo $info->{"favicon"}; ?>' />
        
        <script src="https://www.izusushi.cl/socket.io/socket.io.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/libphonenumber-js/1.4.2/libphonenumber-js.min.js" type="text/javascript"></script>
        <script src="/data/<?php echo $info->{"js_data"}; ?>" type="text/javascript"></script>
        <script src="/js/html_func.js" type="text/javascript"></script>
        <script src="/js/base.js" type="text/javascript"></script>
        <script src="/js/base_lista.js" type="text/javascript"></script>
        <script>
            var dominio = "<?php echo $info->{"dominio"}; ?>";
            var referer = "<?php echo $info->{"url"}; ?>";
            var host = "<?php echo $host; ?>";
            var estados = [ <?php for($i=0; $i<count($info->{'estados'}); $i++){ if($i>0){ echo ", "; } echo "'".$info->{'estados'}[$i]."'";  } ?> ];
        </script>
        <style>
            body{
                font-family: <?php echo $info->{"font"}->{'css'}; ?>;
            }
        </style>
    </head>
    <body>
        <div class="contenedor">
            <div class="menu_left">
                <div class="cont_menu_left">
                    <div class="btn_toogle material-icons" onclick="tooglemenu()">view_headline</div>
                    <div class="menu_info">
                        <h1>Men&uacute;</h1>
                        <ul class="lista_paginas">
                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="pagina">
                <div class="cont_pagina">
                    <div class="header <?php echo ($info->{"header_fixed"} == 1) ? 'fixed' : ''; ?>">
                        <div class="header_logo vhalign"><img src="/images/logos/<?php echo $info->{"logo"}; ?>" alt="" /></div>
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
                    <div class="modal vhalign hide modal_pagina">
                        <div class="cont_modal">
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info"></div>
                        </div>
                    </div>
                    <div class="modal vhalign hide modal_carta">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="buena"></h1><h2></h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal vhalign hide modal_productos_promo">
                        <div class="cont_modal">
                            
                            <div class="titulo"><div class="cont_titulo valign"><h1></h1><h2></h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal" style="padding-bottom: 57px"></div>
                            </div>
                            <div class="acciones">
                                <input class="confirmar" onclick="confirmar_productos_promo(this)" type="button" value="Confirmar" />
                            </div>
                        
                        </div>
                    </div>
                    <div class="modal vhalign hide modal_pregunta_productos">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1></h1><h2></h2></div></div>
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
                    <div class="modal vhalign hide modal_carro paso_01">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1><?php echo $info->{"pedido_01_titulo"}; ?></h1><h2><?php echo $info->{"pedido_01_subtitulo"}; ?></h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal" style="padding-bottom: 57px"></div>
                            </div>
                            <div class="sub_total">
                                <div class="cont_subtotal">
                                    <ul class="total_detalle valign">
                                        <li class="paso_01_sub_total"></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="acciones">
                                <input class="confirmar" onclick="paso_2()" type="button" value="Siguiente" />
                            </div>
                        </div>
                    </div>
                    
                    <?php if($info->{'retiro_local'} == 1 && $info->{'despacho_domicilio'} == 1){ ?>
                    <div class="modal vhalign hide modal_carro paso_02">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1><?php echo $info->{"pedido_02_titulo"}; ?></h1><h2><?php echo $info->{"pedido_02_subtitulo"}; ?></h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal">
                                    <div class="cont_direccion">
                                        <div class="direccion_opciones">
                                            <div class="rlocal dir_op" onclick="show_modal_locales()" style="background: url('/images/retiro_01.jpg')">
                                                <div class="codir prin_alpha_1">
                                                    <div class="cont_info_dir valign">
                                                        <div class="title">Retiro en Local</div>
                                                        <div class="stitle">Sin Costo</div>
                                                        <div class="alert"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="cdesp dir_op" onclick="show_despacho()" style="background: url('/images/despacho_01.jpg')">
                                                <div class="codir prin_alpha_1">
                                                    <div class="cont_info_dir valign">
                                                        <div class="title">Despacho a Domicilio</div>
                                                        <div class="stitle">Desde $<?php echo $info->{"desde"}; ?></div>
                                                        <div class="alert"></div>
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
                    <div class="modal vhalign hide modal_carro paso_02a">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1><?php echo $info->{"pedido_02_titulo"}; ?></h1><h2><?php echo $info->{"pedido_02_subtitulo"}; ?></h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal">
                                    <div class="cont_direccion">
                                        <div class="direccion_op1">
                                            <?php for($i=0; $i<count($locales); $i++){ ?>
                                            <div class="dir_locales" id="<?php echo $locales[$i]->{'id_loc'}; ?>">
                                                <div class="cont_local clearfix">
                                                    <div class="local_info" onclick="select_local(<?php echo $locales[$i]->{'id_loc'}; ?>, '<?php echo $locales[$i]->{'nombre'}; ?>', '<?php echo $locales[$i]->{'direccion'}; ?>')">
                                                        <div class="title"><?php echo $locales[$i]->{'nombre'}; ?></div>
                                                        <div class="stitle"><?php echo $locales[$i]->{'direccion'}; ?></div>
                                                        <div class="alert"></div>
                                                    </div>
                                                    <div class="local_mapa valign" onclick="map_local(<?php echo $locales[$i]->{'id_loc'}; ?>, <?php echo $locales[$i]->{'lat'}; ?>, <?php echo $locales[$i]->{'lng'}; ?>)">
                                                        <div class="icon_mapa" style="background: url('<?php echo $info->{"dominio"}; ?>/images/google-maps.png') no-repeat"></div>
                                                    </div>
                                                </div>
                                                <div id="lmap-<?php echo $locales[$i]->{'id_loc'}; ?>" class="lmap"></div>
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
                    <div class="modal vhalign hide modal_carro paso_02b">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1><?php echo $info->{"pedido_02_titulo"}; ?></h1><h2><?php echo $info->{"pedido_02_subtitulo"}; ?></h2></div></div>
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
                            <div class="acciones acc_paso2b">
                                <input class="confirmar" onclick="paso_3_despacho()" type="button" value="Siguiente" />
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <div class="modal vhalign hide modal_carro paso_03">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1><?php echo $info->{"pedido_03_titulo"}; ?></h1><h2><?php echo $info->{"pedido_03_subtitulo"}; ?></h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal" style="padding-bottom: 116px">
                                    
                                    <div class="cont_final">
                                        <ul class="block_direccion clearfix">
                                            <li class="item_direccion"><h1>Direccion: </h1><h2></h2></li>
                                            <li class="item_numero"><h1>Numero: </h1><h2></h2></li>
                                            <li class="item_depto"><h1>Depto: </h1><input type="text" id="pedido_depto" /></li>
                                        </ul>
                                        <ul class="block_nombre_telefono clearfix">
                                            <li class="item_nombre"><h1>Nombre: </h1><input type="text" id="pedido_nombre" /></li>
                                                <li class="item_telefono"><h1>Telefono: </h1><input type="text" id="pedido_telefono" value="+56 9 " /></li>
                                        </ul>
                                        <?php if($info->{'pedido_wasabi'} == 1 || $info->{'pedido_gengibre'} == 1 || $info->{'pedido_embarazadas'} == 1 || $info->{'pedido_palitos'} == 1){ ?>
                                        <div class="block_preguntas">
                                            <h1>Opciones</h1>
                                            <div class="preguntas">
                                                <?php if($info->{'pedido_wasabi'} == 1){ ?>
                                                <ul class="pregunta clearfix">
                                                    <li class="pre_nom">Wasabi</li>
                                                    <li class="pre_input"><input type="checkbox" id="pedido_wasabi" /></li>
                                                </ul>
                                                <?php } ?>
                                                <?php if($info->{'pedido_gengibre'} == 1){ ?>
                                                <ul class="pregunta clearfix">
                                                    <li class="pre_nom">Gengibre</li>
                                                    <li class="pre_input"><input type="checkbox" id="pedido_gengibre" /></li>
                                                </ul>
                                                <?php } ?>
                                                <?php if($info->{'pedido_embarazadas'} == 1){ ?>
                                                <ul class="pregunta clearfix">
                                                    <li class="pre_nom">Es para Embarazada?</li>
                                                    <li class="pre_input"><input type="checkbox" id="pedido_embarazadas" /></li>
                                                </ul>
                                                <?php } ?>
                                                <?php if($info->{'pedido_soya'} == 1){ ?>
                                                <ul class="pregunta clearfix">
                                                    <li class="pre_nom">Soya</li>
                                                    <li class="pre_input"><input type="checkbox" id="pedido_soya" /></li>
                                                </ul>
                                                <?php } ?>
                                                <?php if($info->{'pedido_teriyaki'} == 1){ ?>
                                                <ul class="pregunta clearfix">
                                                    <li class="pre_nom">Teriyaki</li>
                                                    <li class="pre_input"><input type="checkbox" id="pedido_teriyaki" /></li>
                                                </ul>
                                                <?php } ?>
                                                <?php if($info->{'pedido_palitos'} == 1){ ?>
                                                <ul class="pregunta clearfix">
                                                    <li class="pre_nom">Palitos</li>
                                                    <li class="pre_input"><select id="pedido_palitos"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option></select></li>
                                                </ul>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if($info->{'pedido_comentarios'} == 1){ ?>
                                        <div class="block_preguntas">
                                            <h1>Comentarios</h1>
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
                                        <li class="paso_03_costo"></li>
                                        <li class="paso_03_total"></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="acciones">
                                <input class="confirmar" id="enviar_cotizacion" onclick="paso_4()" type="button" value="Finalizar" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal vhalign hide modal_carro paso_04">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class=""></h1><h2 class=""></h2></div></div>
                            <div onclick="close_that(this)" class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal" style="padding-bottom: 57px">
                                    
                                    <div class="pedido_mensaje"></div>
                                    <div class="pedido_final">
                                        <div class="cont_pedido clearfix">
                                            <div class="estado"><h1>Estado</h1><h2>Enviado</h2></div>
                                            <div class="tiempo"><h1>Tiempo Restante</h1><h2>15 minutos aprox</h2></div>
                                        </div>
                                        <div class="posicion" id="mapa_posicion"></div>
                                        <div class="total"></div>
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
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $info->{'mapcode'}; ?>&libraries=places" async defer></script>
    </body>
</html>