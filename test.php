<?php

    require_once "class/core_class.php";

    $core = new Core();
    $info = $core->curlTest();

    echo "<pre>";
    print_r($info);
    echo "</pre>";

?>