<?php

    $file_01 = '/var/data/'.$_SERVER["HTTP_HOST"].'.json';
    $data = json_decode(file_get_contents($file_01));

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $info["titulo"]; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=<?php echo $info["font"]['family']; ?>" rel="stylesheet">
        
        <link rel="stylesheet" href="<?php echo $info["path"]; ?>/css/reset.css" media="all" />
        <link rel="stylesheet" href="<?php echo $info["path"]; ?>/css/<?php echo $info["css_font_size"]; ?>" media="all" />
        <link rel="stylesheet" href="<?php echo $info["path"]; ?>/css/<?php echo $info["css_color"]; ?>" media="all" />
        <link rel="stylesheet" href="<?php echo $info["path"]; ?>/css/<?php echo $info["css_tipo"]; ?>" media="all" />
        <link rel="stylesheet" href="<?php echo $info["path"]; ?>/css/css_base.css" media="all" />
        
        <link rel='shortcut icon' type='image/x-icon' href='<?php echo $info["path"]; ?>/images/favicon/<?php echo $info["favicon"]; ?>' />
        
        <script src="https://www.izusushi.cl/socket.io/socket.io.js"></script>
        <script src="<?php echo $info["path"]; ?>/js/jquery-1.3.2.min.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/libphonenumber-js/1.4.2/libphonenumber-js.min.js" type="text/javascript"></script>
        <script src="<?php echo $info["path"]; ?>/js/data/<?php echo $info["js_data"]; ?>" type="text/javascript"></script>
        <script src="<?php echo $info["path"]; ?>/js/html_func.js" type="text/javascript"></script>
        <script src="<?php echo $info["path"]; ?>/js/base.js" type="text/javascript"></script>
        <script src="<?php echo $info["path"]; ?>/js/base_lista.js" type="text/javascript"></script>
        <script>
            var dominio = "<?php echo $info["dominio"]; ?>";
            var referer = "<?php echo $info["url"]; ?>";
            var host = "<?php echo $host; ?>";
            var estados = [ <?php for($i=0; $i<count($info['estados']); $i++){ if($i>0){ echo ", "; } echo "'".$info['estados'][$i]."'";  } ?> ];
        </script>
        <style>
            body{
                font-family: <?php echo $info["font"]['css']; ?>;
            }
        </style>
</head>