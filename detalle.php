<?php

    require_once "class/core_class.php";

    $core = new Core();
    $info = $core->ver_pedido();
      
    if($info['op'] == 1){

        $pedido = $info['data']->{'pedido'};
        $puser = $info['data']->{'puser'};

        $nombre = ($puser->{'nombre'} == "") ? $pedido->{'nombre'} : $puser->{'nombre'} ;
        $telefono = ($puser->{'telefono'} == "") ? $pedido->{'telefono'} : $puser->{'telefono'} ;
        
        $carro = $info['data']->{'carro'};
        $promos = $info['data']->{'promos'};

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Detalle: <?php echo $pedido->{"num_ped"}; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="/data/<?php echo $info["code"]; ?>/index.js" type="text/javascript"></script>
        <script src="/js/detalle.js" type="text/javascript"></script>
        <script>
            var catalogo = 0;
            var carro = <?php echo json_encode($carro); ?>;
            var promos = <?php echo json_encode($promos); ?>;
            var costo = <?php echo $pedido->{'costo'}; ?>;
            var total = <?php echo $pedido->{'total'}; ?>;
            var t = <?php echo (isset($_GET["t"])) ? $_GET["t"] : 0 ; ?>;
        </script>
        <link rel="stylesheet" href="/css/detalle.css" media="all" />
        <link rel="stylesheet" href="/css/css_base.css" media="all" />
        <link rel="stylesheet" href="/css/css_detalle_01.css" media="all" />
    </head>
    <body>
        <div class="detalle">
            <?php /*
            <div class="verificar">
            <?php if($despacho == 1 && false){ ?>
                <?php if($info["verify_despacho"] == 0){ ?><div>ERROR: COSTO DESPACHO NO COINCIDE</div><?php } ?>
                <?php if($info["verify_direccion"] == 0){ ?><div>ERROR: DIRECCION NO CORRESPONDE A COORDENADAS</div><?php } ?>
            <?php } ?>
            </div>
            */ ?>
            <div class="titulo txtcen font_01 padding_01 borbottom">Pedido #<?php echo $pedido->{"num_ped"}; ?></div>
            <div class="lista_de_productos padding_01 borbottom"></div>
            <?php if($pedido->{'pre_wasabi'} == 1 || $pedido->{'pre_gengibre'} == 1 || $pedido->{'pre_embarazadas'} == 1 || $pedido->{'pre_palitos'} > 0 || $pedido->{'pre_soya'} == 1 || $pedido->{'pre_teriyaki'} == 1 || $pedido->{'comentarios'} != ""){ ?>
            <div class="contacto padding_01 borbottom">
                
                <?php if($pedido->{'pre_wasabi'} == 1){ ?><div class="txtcen font_04">Wasabi</div><?php } ?>
                <?php if($pedido->{'pre_gengibre'} == 1){ ?><div class="txtcen font_04">Gengibre</div><?php } ?>
                <?php if($pedido->{'pre_embarazadas'} == 1){ ?><div class="txtcen font_04">Embarazadas</div><?php } ?>
                <?php if($pedido->{'pre_palitos'} > 0){ ?><div class="txtcen font_04">Palitos: <?php echo $pedido->{'pre_palitos'}; ?></div><?php } ?>
                <?php if($pedido->{'pre_soya'} == 1){ ?><div class="txtcen font_04">Soya</div><?php } ?>
                <?php if($pedido->{'pre_teriyaki'} == 1){ ?><div class="txtcen font_04">Teriyaki</div><?php } ?>
                <?php if($pedido->{'comentarios'} != ""){ ?><div style="padding-top: 10px" class="txtcen font_02"><?php echo $pedido->{'comentarios'}; ?></div><?php } ?>
                
            </div>
            <?php } ?>            
            <div class="contacto padding_01 borbottom">
                <div class="txtcen font_02"><?php echo $nombre; ?></div>
                <div class="txtcen font_03"><?php echo $telefono; ?></div>
                <?php 
                    if($pedido->{'despacho'} == 0){
                ?>
                    <div class="txtcen font_03 strong pddtop_01">Retiro Local <?php echo $pedido->{'local'}; ?></div>
                <?php } ?>
                    
                <?php 
                    if($pedido->{'despacho'} == 1){
                ?>
                <div class="txtcen font_03 strong pddtop_01">Despacho a Domicilio</div>
                <div class="txtcen font_03"><?php echo $pedido->{'calle'}; ?> <?php echo $pedido->{'num'}; ?> <?php if($pedido->{'depto'} != ""){ ?>Depto: <?php echo $pedido->{'depto'}; ?><?php } ?></div>
                <div class="txtcen font_04"><?php echo $pedido->{'comuna'}; ?></div>
                <?php } ?>
            </div>
            <div class="total padding_01 borbottom">
                <?php if($pedido->{'costo'} > 0){ ?><div class="txtcen font_04">Costo Despacho: $<?php echo number_format($pedido->{'costo'}, 0, '', '.');; ?></div><?php } ?>
                <div class="txtcen font_06 strong">Total: $<?php echo number_format($pedido->{'total'}, 0, '', '.'); ?></div>
            </div>
        </div>
        
    </body>
</html>
<?php
    }else{
        echo "ERROR";
    }
?>