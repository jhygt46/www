<?php

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

    require_once "class/core_class.php";

    $core = new Core();
    echo json_encode($core->actualizar());

?>