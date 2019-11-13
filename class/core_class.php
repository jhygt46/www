<?php

class Core{
    
    public $host = null;
    public $code = null;
    public $dir = null;
    public $dir_info = null;
    public $dir_data = null;
    public $server_ip = null;

    public function __construct(){
        
        if(file_exists("/var/code.json")){
            $this->code = file_get_contents("/var/code.json");
            if(file_exists("/var/server_ip.json")){
                $this->server_ip = file_get_contents("/var/server_ip.json");
            }else{
                $this->server_ip = file_get_contents("http://ipecho.net/plain");
                file_put_contents("/var/server_ip.json", $this->server_ip);
            }
            if($_SERVER["HTTP_HOST"] == $this->server_ip){
                if(isset($_GET["url"])){
                    $this->host = (count(explode(".", $_GET["url"])) == 2) ? "www.".strtolower($_GET["url"]) : strtolower($_GET["url"]) ;
                }else{
                    $var = explode("?url=", $_SERVER["HTTP_REFERER"]);
                    $this->host = (count(explode(".", $var[1])) == 2) ? "www.".strtolower($var[1]) : strtolower($var[1]) ;
                }
            }else{
                $this->host = (count(explode(".", $_SERVER["HTTP_HOST"])) == 2) ? "www.".strtolower($_SERVER["HTTP_HOST"]) : strtolower($_SERVER["HTTP_HOST"]) ;
            }
            $this->dir = "/var/www/data/";
            $this->dir_info = "/var/www/data/".$this->host."/";
            $this->dir_data = "/var/www/html/";
            $this->file_err = "/var/error/error.log";
        }else{ die("ARCHIVO CODE NO EXISTE"); }
    }
    public function volver(){
        if(file_exists($this->dir_info."versiones/last.json")){
            $aux = json_decode(file_get_contents($this->dir_info."versiones/last.json"));
            $code = $aux->{'code'};
            if($code == $_POST["code"]){
                $config = $this->get_config();
                $ver_anterior = (isset($_POST["version"]) && is_numeric($_POST["version"])) ? $_POST["version"] : -1 ;
                $pol_anterior = (isset($_POST["polygon"]) && is_numeric($_POST["polygon"])) ? $_POST["polygon"] : -1 ;
                $versiones = opendir($this->dir_info."versiones/");
                while($archivo = readdir($versiones)){
                    if($archivo != "." && $archivo != ".." && $archivo != "last.json"){
                        $ver_file[] = $archivo;
                    }
                }
                ksort($ver_file);
                $polygon = opendir($this->dir_info."polygon/");
                while($archivo = readdir($polygon)){
                    if($archivo != "." && $archivo != ".." && $archivo != "last.json"){
                        $pol_file[] = $archivo;
                    }
                }
                ksort($pol_file);
                if($ver_anterior == -1){
                    $config["info"] = "last.json";
                }
                if($ver_anterior > -1 && $ver_anterior < count($ver_file)){
                    for($i=0; $i<count($ver_file); $i++){
                        if($i == $ver_anterior){
                            $config["info"] = $ver_file[$i];
                        }
                    }
                }
                if($pol_anterior == -1){
                    $config["info"] = "last.json";
                }
                if($pol_anterior > -1 && $pol_anterior < count($pol_file)){
                    for($i=0; $i<count($pol_file); $i++){
                        if($i == $pol_anterior){
                            $config["polygon"] = $pol_file[$i];
                        }
                    }
                }
                file_put_contents($this->dir_info."config.json", json_encode($config));
            }   
        }
    }
    public function actualizar(){
        $config = $this->get_config();
        $config["actualizar"] = 1;
        file_put_contents($this->dir_info."config.json", json_encode($config));
    }
    public function get_config(){
        if(file_exists($this->dir_info."config.json")){
            $aux_conf = json_decode(file_get_contents($this->dir_info."config.json"));
            $config["info"] = $aux_conf->{"info"};
            $config["polygon"] = $aux_conf->{"polygon"};
            $config["actualizar"] = $aux_conf->{"actualizar"};
        }else{
            $config["info"] = "last.json";
            $config["polygon"] = "last.json";
            $config["actualizar"] = 0;
            file_put_contents($this->dir_info."config.json", json_encode($config));
        }
        return $config;
    }
    public function get_data(){
        $config = $this->get_config();
        if(file_exists($this->dir_info."versiones/".$config["info"]) && $config["actualizar"] == 0){
            return json_decode(file_get_contents($this->dir_info."versiones/".$config["info"]));
        }else{
            return $this->curlData();
        }
    }
    public function curlData(){

        $send["code"] = $this->code;
        $send["host"] = $this->host;
        $send["tipo"] = 1;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://misitiodelivery.cl/web/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
        if(!curl_errno($ch)){
            $data = json_decode(curl_exec($ch));
            curl_close($ch);
            if($data->{'op'} == 1){
                if(!is_dir($this->dir)){
                    if(!mkdir($this->dir, 0777)){
                        die("LA CARPETA ".$this->dir." NO PUDO SER CREADA<br/>");
                        $this->enviar_error(16, "No se pudo crear el directorio ".$this->dir." ".$this->host);
                    }
                }
                if(!is_dir($this->dir_info)){
                    if(!mkdir($this->dir_info, 0777)){
                        die("LA CARPETA ".$this->dir_info." NO PUDO SER CREADA<br/>");
                        $this->enviar_error(16, "No se pudo crear el direcctorio ".$this->dir_info." ".$this->host);
                    }else{
                        if(!mkdir($this->dir_info."pedidos/", 0777)){
                            die("LA CARPETA ".$this->dir_info."pedidos/ NO PUDO SER CREADA<br/>");
                            $this->enviar_error(16, "No se pudo crear el direcctorio ".$this->dir_info."pedidos ".$this->host);
                        }
                        if(!mkdir($this->dir_info."versiones/", 0777)){
                            die("LA CARPETA ".$this->dir_info."versiones/ NO PUDO SER CREADA<br/>");
                            $this->enviar_error(16, "No se pudo crear el direcctorio ".$this->dir_info."versiones ".$this->host);
                        }
                        if(!mkdir($this->dir_info."polygon/", 0777)){
                            die("LA CARPETA ".$this->dir_info."polygon/ NO PUDO SER CREADA<br/>");
                            $this->enviar_error(16, "No se pudo crear el direcctorio ".$this->dir_info."polygon ".$this->host);
                        }
                    }
                }
                $config = $this->get_config();
                $config["actualizar"] = 0;
                if(!file_put_contents($this->dir_info."config.json", json_encode($config))){
                    $this->enviar_error(16, "No se pudo guardar actualizacion #0 ".$this->host);
                }
                if(file_exists($this->dir_info."versiones/last.json")){
                    rename($this->dir_info."versiones/last.json", $this->dir_info."versiones/".date("Ymd", filemtime($this->dir_info."versiones/last.json")).".json");
                }
                if(file_put_contents($this->dir_info."versiones/last.json", json_encode($data->{"info"}))){
                    if($data->{"info"}->{"logo"} != "sinlogo.png"){
                        if(!file_put_contents($this->dir_data."data/".$data->{"info"}->{"code"}."/".$data->{"info"}->{"logo"}, file_get_contents("http://www.misitiodelivery.cl/images/logos/".$data->{"info"}->{"logo"}))){
                            $this->enviar_error(16, "No se pudo guardar el logo ".$this->host);
                        }
                    }
                    if($data->{"info"}->{"favicon"} != "default.ico"){
                        if(!file_put_contents($this->dir_data."data/".$data->{"info"}->{"code"}."/".$data->{"info"}->{"favicon"}, file_get_contents("http://www.misitiodelivery.cl/images/favicon/".$data->{"info"}->{"favicon"}))){
                            $this->enviar_error(16, "No se pudo guardar el favicon ".$this->host);
                        }
                    }
                }
                if(file_exists($this->dir_info."polygon/last.json")){
                    rename($this->dir_info."polygon/last.json", $this->dir_info."polygon/".date("Ymd", filemtime($this->dir_info."polygon/last.json")).".json");
                }
                if(!file_put_contents($this->dir_info."polygon/last.json", json_encode($data->{"polygons"}))){
                    $this->enviar_error(16, "No se pudo guardar los poligonos ".$this->host);
                }
                if(!is_dir($this->dir_data."data/".$data->{"info"}->{"code"}."/")){
                    if(!mkdir($this->dir_data."data/".$data->{"info"}->{"code"}."/", 0777)){
                        die("LA CARPETA ".$this->dir_data."data/".$data->{"info"}->{"code"}."/ NO PUDO SER CREADA<br/>");
                        $this->enviar_error(16, "No se pudo crear el directorio ".$this->dir_data."data/".$data->{"info"}->{"code"}."/ ".$this->host);
                    }else{
                        file_put_contents($this->dir_data."data/".$data->{"info"}->{"code"}."/index.html", "");
                    }
                }
                if(file_put_contents($this->dir_data."data/".$data->{"info"}->{"code"}."/index.js", "var data=".json_encode($data->{"data"}))){
                    $categorias = $data->{"data"}->{"catalogos"}[0]->{"categorias"};
                    for($i=0; $i<count($categorias); $i++){
                        if(strlen($categorias[$i]->{"image"}) == 25){
                            if(!file_exists($this->dir_data."data/".$data->{"info"}->{"code"}."/".$categorias[$i]->{"image"})){
                                if(!file_put_contents($this->dir_data."data/".$data->{"info"}->{"code"}."/".$categorias[$i]->{"image"}, file_get_contents("http://www.misitiodelivery.cl/images/categorias/".$categorias[$i]->{"image"}))){
                                    $this->enviar_error(16, "No se pudo guardar las imagenes de categorias ".$this->host);
                                }
                            }
                        }
                    }
                    if($data->{"info"}->{"foto_retiro"} != ""){
                        if(!file_exists($this->dir_data."data/".$data->{"info"}->{"code"}."/".$data->{"info"}->{"foto_retiro"})){
                            if(!file_put_contents($this->dir_data."data/".$data->{"info"}->{"code"}."/".$data->{"info"}->{"foto_retiro"}, file_get_contents("http://www.misitiodelivery.cl/images/categorias/".$data->{"info"}->{"foto_retiro"}))){
                                $this->enviar_error(16, "No se pudo guardar la foto retiro ".$this->host);
                            }
                        }
                    }
                    if($data->{"info"}->{"foto_despacho"} != ""){
                        if(!file_exists($this->dir_data."data/".$data->{"info"}->{"code"}."/".$data->{"info"}->{"foto_despacho"})){
                            if(!file_put_contents($this->dir_data."data/".$data->{"info"}->{"code"}."/".$data->{"info"}->{"foto_despacho"}, file_get_contents("http://www.misitiodelivery.cl/images/categorias/".$data->{"info"}->{"foto_despacho"}))){
                                $this->enviar_error(16, "No se pudo guardar la foto despacho ".$this->host);
                            }
                        }
                    }
                    $locales = $data->{"data"}->{"locales"};
                    for($i=0; $i<count($locales); $i++){
                        if(strlen($locales[$i]->{'image'}) == 25){
                            if(!file_exists($this->dir_data."data/".$data->{"info"}->{"code"}."/".$locales[$i]->{'image'})){
                                if(!file_put_contents($this->dir_data."data/".$data->{"info"}->{"code"}."/".$locales[$i]->{'image'}, file_get_contents("http://www.misitiodelivery.cl/images/categorias/".$locales[$i]->{'image'}))){
                                    $this->enviar_error(16, "No se pudo guardar las imagen del local ".$this->host);
                                }
                            }
                        }
                    }
                    
                }else{ $this->enviar_error(16, "No se pudo crear el archivo index.js"); }
                
                return $data->{"info"};
            }else{ $this->enviar_error(17, "curlData() #2 ".$this->host); }
        }else{ $this->enviar_error(17, "curlData() #1 ".$this->host); }

    }
    public function get_info_despacho($lat, $lng){

        $config = $this->get_config();
        $polygons = json_decode(file_get_contents($this->dir_info."polygon/".$config["polygon"]));
        $precio = 9999999;
        $info['op'] = 2;
        if(count($polygons) > 0){
            foreach($polygons as $polygon){
                $lats = [];
                $lngs = [];
                $puntos = json_decode($polygon->{'poligono'});
                foreach($puntos as $punto){
                    $lats[] = $punto->{'lat'};
                    $lngs[] = $punto->{'lng'};
                }
                $is = $this->is_in_polygon($lats, $lngs, $lat, $lng);
                if($is){
                    if($precio > $polygon->{'precio'}){
                        $info['op'] = 1;
                        $info['id_loc'] = intval($polygon->{'id_loc'});
                        $info['precio'] = intval($polygon->{'precio'});
                        $info['nombre'] = $polygon->{'nombre'};
                        $info['lat'] = $lat;
                        $info['lng'] = $lng;
                        $precio = $polygon->{'precio'};
                    }
                }
            }
        }else{
            $info['op'] = 3;
            $this->enviar_error(16, "Sin Poligonos en ".$this->host);
        }
        return $info;

    }
    public function is_in_polygon($vertices_x, $vertices_y, $longitude_x, $latitude_y){

        $points_polygon = count($vertices_x) - 1;
        $i = $j = $c = $point = 0;
        for($i=0, $j=$points_polygon ; $i<$points_polygon; $j=$i++) {
            $point = $i;
            if($point == $points_polygon)
                $point = 0;
            if((($vertices_y[$point] > $latitude_y != ($vertices_y[$j] > $latitude_y)) && ($longitude_x < ($vertices_x[$j] - $vertices_x[$point]) * ($latitude_y - $vertices_y[$point]) / ($vertices_y[$j] - $vertices_y[$point]) + $vertices_x[$point])))
                $c = !$c;
        }
        return $c;

    }
    private function bloquear(){

        if(file_exists($this->dir_info."/bloqueo.json")){
            $file_time = filemtime($this->dir_info."/bloqueo.json");
            $segundos = intval(file_get_contents($this->dir_info."/bloqueo.json"));
            $diff = time() - $file_time;
            if($diff < $segundos){
                if($diff <= 3){
                    $aux = $segundos + 60;
                    $aux2 = ($aux > 300) ? 300 : $aux;
                    file_put_contents($this->dir_info."/bloqueo.json", $aux2);
                }else{
                    $aux = $segundos - $diff;
                    file_put_contents($this->dir_info."/bloqueo.json", $aux);
                }
                return false;
            }else{
                unlink($this->dir_info."/bloqueo.json");
                return true;
            }
        }else{
            $pedidos = opendir($this->dir_info."pedidos/");
            while($archivo = readdir($pedidos)){
                if($archivo != "." && $archivo != ".."){
                    $tiempo_existe = time() - filemtime($this->dir_info."pedidos/".$archivo);
                    if($tiempo_existe < 10){
                        $tiempo1++;
                    }
                    if($tiempo_existe < 40){
                        $tiempo2++;
                    }
                }
            }
            if($tiempo1 > 4 || $tiempo2 > 9){
                file_put_contents($this->dir_info."/bloqueo.json", "300");
                return false;
            }else{
                return true;
            }
        }

    }
    public function enviar_pedido(){

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $datas = [
            'secret' => '6LdZp78UAAAAALb66uCWx7RR3cuSjhQLhy8sWZdu',
            'response' => $_POST['token'],
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];
        $options = array(
            'http' => array(
                'header'  => 'Content-type: application/x-www-form-urlencoded\r\n',
                'method'  => 'POST',
                'content' => http_build_query($datas)
            )
        );
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $res = json_decode($response, true);

        if($res['success'] == true){

            $pedido = json_decode($_POST['pedido']);
            $nombre = $pedido->{'nombre'};
            $telefono = $pedido->{'telefono'};

            if(strlen($nombre) > 2){
                if(strlen($telefono) >= 12 && strlen($telefono) <= 14){

                    $send['pedido'] = $pedido;
                    $send['puser'] = json_decode($_POST['puser']);
                    $send['carro'] = json_decode($_POST['carro']);
                    $send['promos'] = json_decode($_POST['promos']);
                    $send["id_loc"] = $pedido->{'id_loc'};
                    $send["code"] = $this->code;
                    $send["host"] = $this->host;
                    $send["tipo"] = 2;
                    
                    $file['pedido'] = $pedido;
                    $file['puser'] = json_decode($_POST['puser']);
                    $file['carro'] = json_decode($_POST['carro']);
                    $file['promos'] = json_decode($_POST['promos']);

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://misitiodelivery.cl/web/');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                    if(!curl_errno($ch)){
                        $resp = json_decode(curl_exec($ch));
                        curl_close($ch);
                        $info['resp'] = $resp;
                        /*
                        if($resp->{'op'} == 1){

                            $file['pedido']->{'id_ped'} = $resp->{'id_ped'};
                            $file['pedido']->{'num_ped'} = $resp->{'num_ped'};
                            $file['pedido']->{'pedido_code'} = $resp->{'pedido_code'};
                            $file['pedido']->{'fecha'} = $resp->{'fecha'};
                            $info['op'] = 1;
                            $info['pedido_code'] = $resp->{'pedido_code'};
                            $id_puser = (isset($file['puser']->{'id_puser'})) ? $file['puser']->{'id_puser'} : 0 ;
                            
                            if($resp->{'set_puser'} == 1){

                                $info['set_puser'] = 1;
                                $info['puser_id'] = $resp->{'puser_id'};
                                $info['puser_code'] = $resp->{'puser_code'};
                                $info['puser_nombre'] = $resp->{'puser_nombre'};
                                $info['puser_telefono'] = $resp->{'puser_telefono'};

                            }
                            if($resp->{'email'} == 1){

                                $info['email'] = 1;
                                $info['lat'] = $resp->{'lat'};
                                $info['lng'] = $resp->{'lng'};
                                $info['id_ped'] = $resp->{'id_ped'};
                                $info['num_ped'] = $resp->{'num_ped'};
                                $info['t_despacho'] = $resp->{'t_despacho'};
                                $info['t_retiro'] = $resp->{'t_retiro'};
                                $info['fecha'] = $resp->{'fecha'};

                            }
                            if($resp->{'email'} == 2){

                                $info['email'] = 2;
                                $info['tel'] = $resp->{'telefono'};
                                $info['mailto'] = $resp->{'correo'};
                                $info['body'] = $resp->{'url'}.'/detalle.php?code='.$resp->{'pedido_code'};
                            
                            }
                        }
                        if($resp->{'op'} == 2){

                            $info['op'] = 2;
                            $temp_code = $this->pass_generate(20);
                            $info['tel'] = $resp->{'telefono'};
                            $info['mailto'] = $resp->{'correo'};
                            $info['body'] = $resp->{'url'}.'/detalle.php?code='.$temp_code;
                            $file['pedido']->{'id_ped'} = 0;
                            $file['pedido']->{'num_ped'} = 0;
                            $file['pedido']->{'pedido_code'} = $temp_code;
                            $file['pedido']->{'fecha'} = date('Y-m-d H:i:s');

                        }
                        file_put_contents($this->dir_info."pedidos/".$file['pedido']->{'pedido_code'}.".json", json_encode($file));
                        */

                    }else{
                        $this->enviar_error(17, "Curl error enviar_pedido() #1 ".$this->host);
                    }
                }else{  }
            }else{  }

        }
        return $info;

    }
    public function ver_pedido(){

        $pedido_code = $_GET["code"];
        $config = $this->get_config();
        $file = $this->dir_info."pedidos/".$pedido_code.".json";

        if(file_exists($file) && !isset($_GET["n"])){

            $data = json_decode(file_get_contents($file));            
            $fecha = $data->{'pedido'}->{'fecha'};
            $diff = time() - $fecha;
            if($diff < 86400){
                $info['op'] = 1;
                $info['data'] = $data;
            }else{
                $info['op'] = 2;
            }

        }else{

            $send["tipo"] = 4;
            $send["pedido_code"] = $pedido_code;
            $send["host"] = $this->host;
            $send["code"] = $this->code;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://misitiodelivery.cl/web/');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));

            if(!curl_errno($ch)){

                $data = json_decode(curl_exec($ch));
                if($data->{"op"} == 1){
                    file_put_contents($file, $data->{"resp"});
                    $info['op'] = 1;
                    $info['data'] = json_decode($data->{"resp"});
                }else{
                    $info['op'] = 2;
                }
                curl_close($ch);
            }else{
                $info['op'] = 2;
            }
            
        }
        return $info;

    }
    public function enviar_error($code, $error){

        $send["tipo"] = 3;
        $send["codes"] = $code;
        $send["error"] = $error;
        $send["code"] = $this->code;
        $send["host"] = $this->host;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://misitiodelivery.cl/web/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
        if(!curl_errno($ch)){
            $resp = json_decode(curl_exec($ch));
            curl_close($ch);
            if($resp->{'op'} != 1){ $this->enviar_error_2($code." // ".$error); }
        }else{ 
            $this->enviar_error_2($code." // ".$error);
        }

    }
    public function enviar_contacto($nombre, $telefono, $correo, $comentario){

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $datas = [
            'secret' => '6LdZp78UAAAAALb66uCWx7RR3cuSjhQLhy8sWZdu',
            'response' => $_POST['token'],
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];
        $options = array(
            'http' => array(
                'header'  => 'Content-type: application/x-www-form-urlencoded\r\n',
                'method'  => 'POST',
                'content' => http_build_query($datas)
            )
        );
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $res = json_decode($response, true);

        if($res['success'] == true){

            $send["tipo"] = 5;
            $send["nombre"] = $nombre;
            $send["telefono"] = $telefono;
            $send["correo"] = $telefono;
            $send["comentario"] = $comentario;
            $send["code"] = $this->code;
            $send["host"] = $this->host;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://misitiodelivery.cl/web/');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
            if(!curl_errno($ch)){
                $resp = json_decode(curl_exec($ch));
                curl_close($ch);
                if($resp->{'op'} == 1){
                    $info['op'] = 1;
                    $info['mensaje'] = "Error Captcha";
                }
                if($resp->{'op'} != 1){
                    $info['op'] = 2;
                    $info['mensaje'] = "Error Captcha";
                    $this->enviar_error(17, "Curl error enviar_error() #1 ".$this->host);
                }
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Error Captcha";
                $this->enviar_error(17, "Curl error enviar_error() #1 ".$this->host);
            }

        }else{
            $info['op'] = 2;
            $info['mensaje'] = "Error Captcha";
        }
        return $info;

    }
    private function enviar_error_2($error){
        file_put_contents($this->file_err, $this->host." - ".$error);
    }
    private function pass_generate($n){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for($i=0; $i<$n; $i++){
            $r .= $chars{rand(0, strlen($chars)-1)};
        }
        return $r;
    }

}