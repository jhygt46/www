<?php

    require_once "class/core_class.php";

    $core = new Core();
    $info = $core->ver_pedido();
    
    echo "<pre>";
    print_r($info['data']);
    echo "</pre>";
    exit;
    

    if($info['op'] == 1){

        $pedido = $info['data']->{'pedido'};
        $puser = $info['data']->{'puser'};
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
        <script src="/data/<?php echo $info["js_data"]; ?>" type="text/javascript"></script>
        <script src="/js/detalle.js" type="text/javascript"></script>
        <script>
            var catalogo = 0;
            var carro = <?php echo json_encode($carro); ?>;
            var promos = <?php echo json_encode($promos); ?>;
            var costo = <?php echo $pedido->{'costo'}; ?>;
            var total = <?php echo $pedido->{'total'}; ?>;
            var t = <?php echo $_GET["t"]; ?>;
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
                <div class="txtcen font_02"><?php echo $puser->{'nombre'}; ?></div>
                <div class="txtcen font_03"><?php echo $puser->{'telefono'}; ?></div>
                <?php 
                    if($despacho == 0){
                ?>
                    <div class="txtcen font_03 strong pddtop_01">Retiro Local <?php echo $pedido->{'local'}; ?></div>
                <?php } ?>
                    
                <?php 
                    if($despacho == 1){
                    
                ?>
                <div class="txtcen font_03 strong pddtop_01">Despacho a Domicilio</div>
                <div class="txtcen font_03"><?php echo $info['pdir']['calle']; ?> <?php echo $info['pdir']['num']; ?> <?php if($info['pdir']['depto'] != ""){ ?>Depto: <?php echo $info['pdir']['depto']; ?><?php } ?></div>
                <div class="txtcen font_04"><?php echo $info['pdir']['comuna']; ?></div>
                <?php } ?>
            </div>
            <div class="total padding_01 borbottom">
                <?php if($costo > 0){ ?><div class="txtcen font_04">Costo Despacho: $<?php echo number_format($costo, 0, '', '.');; ?></div><?php } ?>
                <div class="txtcen font_06 strong">Total: $<?php echo number_format($total, 0, '', '.'); ?></div>
            </div>
        </div>
        
    </body>
</html>
<?php
    }else{
        echo "ERROR";
    }
?>