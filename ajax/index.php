<?php

    require_once "../class/core_class.php";

    if($_POST["accion"] == "despacho_domicilio"){
        $core = new Core();
        $info = $core->get_info_despacho($_POST["lat"], $_POST["lng"]);
    }
    if($_POST["accion"] == "enviar_pedido"){
        $core = new Core();
        $info = $core->enviar_pedido();
    }
    echo json_encode($info);

?>