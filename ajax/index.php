<?php

    require_once "../class/core_class.php";

    $core = new Core();
    $info = $core->get_info_despacho($_POST["lat"], $_POST["lng"]);
    echo json_encode($info);

?>