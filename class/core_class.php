<?php

class Core{
    
    public $host = null;
    public $code = null;
    public $dir_info = null;
    public $dir_data = null;
    public $server_ip = null;

    public function __construct(){

        $this->code = file_get_contents("/var/code.json");
        
        if(file_exists("/var/server_ip.json")){
            $this->server_ip = file_get_contents("/var/server_ip.json");
        }else{
            $this->server_ip = file_get_contents("http://ipecho.net/plain");
            file_put_contents("/var/server_ip.json", $this->server_ip);
        }

        if($_SERVER["HTTP_HOST"] == $this->server_ip){
            $this->host = (count(explode(".", $_GET["url"])) == 2) ? "www.".$_GET["url"] : $_GET["url"] ;
        }else{
            $this->host = (count(explode(".", $_SERVER["HTTP_HOST"])) == 2) ? "www.".$_SERVER["HTTP_HOST"] : $_SERVER["HTTP_HOST"] ;
        }
        if($_SERVER["HTTP_HOST"] == "localhost"){
            $this->dir_info = "C:/var/".$this->host."/";
            $this->dir_data = "C:/AppServ/www/restaurants_web/deliveryweb/";
        }else{
            $this->dir_info = "/var/data/".$this->host."/";
            $this->dir_data = "/var/www/html/";
        }

    }
    public function volver(){
        
        $config = $this->get_config();

        $versiones = opendir($this->dir_info."versiones/");
        while($archivo = readdir($versiones)){
            $config["info"] = $archivo;
            echo "version: ".$archivo."<br/>";
        }
        $polygon = opendir($this->dir_info."polygon/");
        while($archivo = readdir($polygon)){
            $config["polygon"] = $archivo;
            echo "polygon: ".$archivo."<br/>";
        }
        
        //file_put_contents($this->dir_info."config.json", json_encode($config));

    }
    private function actualizar(){
        
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

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://misitiodelivery.cl/servicio.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
        $data = json_decode(curl_exec($ch));

        if(isset($data->{'op'}) && $data->{'op'} == 1){

            if(!is_dir($this->dir_info)){
                mkdir($this->dir_info, 0777);
                mkdir($this->dir_info."pedidos/", 0777);
                mkdir($this->dir_info."versiones/", 0777);
                mkdir($this->dir_info."polygon/", 0777);
            }

            if(file_exists($this->dir_info."versiones/last.json")){
                rename($this->dir_info."versiones/last.json", $this->dir_info."versiones/".date("Ymd", filemtime($this->dir_info."versiones/last.json")).".json");
            }
            if(file_put_contents($this->dir_info."versiones/last.json", json_encode($data->{"info"}))){
                if($data->{"info"}->{"logo"} != "sinlogo.png"){
                    if(!file_put_contents($this->dir_data."data/".$data->{"info"}->{"code"}."/".$data->{"info"}->{"logo"}, file_get_contents("http://www.misitiodelivery.cl/images/logos/".$data->{"info"}->{"logo"}))){
                        // REPORTAR ERROR
                    }
                }
            }
            if(file_exists($this->dir_info."polygon/last.json")){
                rename($this->dir_info."polygon/last.json", $this->dir_info."polygon/".date("Ymd", filemtime($this->dir_info."polygon/last.json")).".json");
            }
            if(file_put_contents($this->dir_info."polygon/last.json", json_encode($data->{"polygons"}))){
                // REPORTAR ERROR
            }
            if(!is_dir($this->dir_data."data/".$data->{"info"}->{"code"})){
                mkdir($this->dir_data."data/".$data->{"info"}->{"code"}, 0777);
                file_put_contents($this->dir_data."data/".$data->{"info"}->{"code"}."/index.html", "");
            }
            if(file_put_contents($this->dir_data."data/".$data->{"info"}->{"code"}."/index.js", "var data=".json_encode($data->{"data"}))){
                $categorias = $data->{"data"}->{"catalogos"}[0]->{"categorias"};
                for($i=0; $i<count($categorias); $i++){
                    if(strlen($categorias[$i]->{"image"}) == 24 || strlen($categorias[$i]->{"image"}) == 26){
                        if(!file_exists($this->dir_data."data/".$data->{"info"}->{"code"}."/".$categorias[$i]->{"image"})){
                            if(!file_put_contents($this->dir_data."data/".$data->{"info"}->{"code"}."/".$categorias[$i]->{"image"}, file_get_contents("http://www.misitiodelivery.cl/images/categorias/".$categorias[$i]->{"image"}))){
                                // REPORTAR ERROR
                            }
                        }
                    }
                }
            }

        }
        curl_close($ch);
        return $data->{'info'};

    }
    public function get_info_despacho($lat, $lng){

        $config = $this->get_config();
        $polygons = json_decode(file_get_contents($this->dir_info."polygon/".$config["polygon"]));
        $precio = 9999999;
        $info['op'] = 2;
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
    public function enviar_pedido(){

        $pedido = json_decode($_POST['pedido']);
        $nombre = $pedido->{'nombre'};
        $telefono = str_replace(" ", "", $pedido->{'telefono'});
        
        if(strlen($nombre) > 2){
            if(strlen($telefono) >= 12 && strlen($telefono) <= 14){

                $send['pedido'] = $pedido;
                $send['puser'] = json_decode($_POST['puser']);
                $send['carro'] = json_decode($_POST['carro']);
                $send['promos'] = json_decode($_POST['promos']);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://misitiodelivery.cl/enviar_pedido.php');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                $info = json_decode(curl_exec($ch));

                if($info->{'op'} == 1 && $info->{'id_ped'} > 0){

                    $send['pedido']->{'id_ped'} = $info->{'id_ped'};
                    $send['pedido']->{'num_ped'} = $info->{'num_ped'};
                    $send['pedido']->{'pedido_code'} = $info->{'pedido_code'};
                    $send['pedido']->{'fecha'} = $info->{'fecha'};
                    file_put_contents($this->dir_info."pedidos/".$info->{'pedido_code'}.".json", json_encode($send));

                }

                curl_close($ch);

            }
        }

        return $info;

    }
    public function ver_pedido(){

        $pedido_code = $_GET["code"];
        $config = $this->get_config();
        $file = $this->dir_info."pedidos/".$pedido_code.".json";

        if(file_exists($file)){
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
            $info['op'] = 2;
        }
        return $info;

    }

}