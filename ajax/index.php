<?php

    require_once "../class/core_class.php";

    $core = new Core();
    $info = $core->get_info_despacho();
    echo json_encode($info);

?>