<?php

    require_once "../class/core_class.php";

    $core = new Core();
    $info = $core->ver_pedido();
    echo "<pre>";
    print_r($info);
    echo "</pre>";

?>