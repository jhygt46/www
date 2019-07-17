<?php

    if($_POST["accion"] == "despacho_domicilio"){
        require_once "../class/core_class.php";
        $core = new Core();
        $info = $core->get_info_despacho($_POST["lat"], $_POST["lng"]);
        echo json_encode($info);
    }
    if($_POST["accion"] == "enviar_pedido"){
        require_once "../class/core_class.php";
        $core = new Core();
        $info = $core->enviar_pedido();
        echo json_encode($info);
    }
    if($_POST["accion"] == "enviar_error"){
        require_once "../class/core_class.php";
        $core = new Core();
        $info = $core->enviar_error($_POST["error"]);
    }

?>