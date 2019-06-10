<?php

    require_once "class/core_class.php";

    $core = new Core();
    $info = $core->ver_pedido();
    echo "<pre>";
    print_r($info);
    echo "</pre>";

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $info["titulo"]; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="<?php echo $info["js_data"]; ?>" type="text/javascript"></script>

        <script>
            var catalogo = 0;
            var carro = <?php echo $info['carro']; ?>;
            var promos = <?php echo $info['promos']; ?>;
            var costo = <?php echo $costo; ?>;
            var total = <?php echo $total; ?>;
            var t = <?php echo $_GET["t"]; ?>;
        </script>
        
        <link rel="stylesheet" href="<?php echo $info["css_detalle"]; ?>" media="all" />
        <link rel="stylesheet" href="<?php echo $info["css_base"]; ?>" media="all" />
        <script src="<?php echo $info["js_detalle"]; ?>" type="text/javascript"></script>

    </head>
    <body>
        <div class="detalle">
            
            <div class="verificar">
            <?php if($despacho == 1 && false){ ?>
                <?php if($info["verify_despacho"] == 0){ ?><div>ERROR: COSTO DESPACHO NO COINCIDE</div><?php } ?>
                <?php if($info["verify_direccion"] == 0){ ?><div>ERROR: DIRECCION NO CORRESPONDE A COORDENADAS</div><?php } ?>
            <?php } ?>
            </div>
            
            <div class="titulo txtcen font_01 padding_01 borbottom">Pedido #<?php echo $info["num_ped"]; ?></div>
            <div class="lista_de_productos padding_01 borbottom"></div>
            <?php if($info['pre_wasabi'] == 1 || $info['pre_gengibre'] == 1 || $info['pre_embarazadas'] == 1 || $info['pre_palitos'] > 0 || $info['pre_soya'] == 1 || $info['pre_teriyaki'] == 1 || $info['comentarios'] != ""){ ?>
            <div class="contacto padding_01 borbottom">
                
                <?php if($info['pre_wasabi'] == 1){ ?><div class="txtcen font_04">Wasabi</div><?php } ?>
                <?php if($info['pre_gengibre'] == 1){ ?><div class="txtcen font_04">Gengibre</div><?php } ?>
                <?php if($info['pre_embarazadas'] == 1){ ?><div class="txtcen font_04">Embarazadas</div><?php } ?>
                <?php if($info['pre_palitos'] > 0){ ?><div class="txtcen font_04">Palitos: <?php echo $info['pre_palitos']; ?></div><?php } ?>
                <?php if($info['pre_soya'] == 1){ ?><div class="txtcen font_04">Soya</div><?php } ?>
                <?php if($info['pre_teriyaki'] == 1){ ?><div class="txtcen font_04">Teriyaki</div><?php } ?>
                <?php if($info['comentarios'] != ""){ ?><div style="padding-top: 10px" class="txtcen font_02"><?php echo $info['comentarios']; ?></div><?php } ?>
                
            </div>
            <?php } ?>            
            <div class="contacto padding_01 borbottom">
                <div class="txtcen font_02"><?php echo $info['puser']['nombre']; ?></div>
                <div class="txtcen font_03"><?php echo $info['puser']['telefono']; ?></div>
                <?php 
                    if($despacho == 0){
                ?>
                    <div class="txtcen font_03 strong pddtop_01">Retiro Local <?php echo $info['local']; ?></div>
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