<?php

class Core{
    
    public $host = null;
    public $code = null;
    public $dir_info = null;
    public $file_info = null;
    public $file_act = null;
    public $dir_data = null;
    public $dir_img = null;

    public function __construct(){

        $this->code = file_get_contents("/var/code.json");
        
        if($_SERVER["HTTP_HOST"] != "35.192.157.227"){
            $host = explode(".", $_SERVER["HTTP_HOST"]);
            $this->host = (count($host) == 2) ? "www.".$_SERVER["HTTP_HOST"] : $_SERVER["HTTP_HOST"] ;
        }else{
            $this->host = $_GET["url"];
        }

        if($_SERVER["HTTP_HOST"] == "localhost"){
            $this->dir_info = "C:/var/".$this->host."/";
            $this->file_info = "C:/var/".$this->host."/last.json";
            $this->file_act = "C:/var/".$this->host."/actualizar.json";
            $this->dir_data = "C:/AppServ/www/restaurants_web/deliveryweb/data/";
            $this->dir_img = "C:/AppServ/www/restaurants_web/deliveryweb/images/";
        }else{
            $this->dir_info = "/var/data/".$this->host."/";
            $this->file_info = "/var/data/".$this->host."/last.json";
            $this->file_act = "/var/data/".$this->host."/actualizar.json";
            $this->dir_data = "/var/www/html/data/";
            $this->dir_img = "/var/www/html/images/";
        }

    }
    public function actualizar(){
        file_put_contents($this->file_act, '');
    }
    public function get_data(){

        if(is_dir($this->dir_info)){
            if(file_exists($this->file_info) && !file_exists($this->file_act)){
                return json_decode(file_get_contents($this->file_info));
            }else{
                return $this->curlData();
            }
        }else{
            if(mkdir($this->dir_info, 0777)){
                if(mkdir($this->dir_info."pedidos/", 0777)){
                    if(mkdir($this->dir_info."versiones/", 0777)){
                        return $this->curlData();
                    }
                }
            }
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

            if(file_exists($this->file_info)){
                rename($this->file_info, $this->dir_info."versiones/".date("Ymd", filemtime($this->file_info)).".json");
            }
            if(!file_put_contents($this->dir_info."polygons.json", json_encode($data->{"polygons"}))){
                // REPORTAR ERROR
            }
            if(file_put_contents($this->file_info, json_encode($data->{"info"}))){
                if($data->{"info"}->{"logo"} != "sinlogo.png"){
                    if(!file_exists($this->dir_img."logos/".$data->{"info"}->{"logo"})){
                        if(!file_put_contents($this->dir_img."logos/".$data->{"info"}->{"logo"}, file_get_contents("http://www.misitiodelivery.cl/images/logos/".$data->{"info"}->{"logo"}))){
                            // REPORTAR ERROR
                        }
                    }
                }
            }

            if(!is_dir($this->dir_data.$data->{"info"}->{"code"})){
                mkdir($this->dir_data.$data->{"info"}->{"code"}, 0777);
                file_put_contents($this->dir_data.$data->{"info"}->{"code"}."/index.html", "");
            }

            if(file_put_contents($this->dir_data.$data->{"info"}->{"code"}."/index.js", "var data=".json_encode($data->{"data"}))){
                $categorias = $data->{"data"}->{"catalogos"}[0]->{"categorias"};
                for($i=0; $i<count($categorias); $i++){
                    if(strlen($categorias[$i]->{"image"}) == 24 || strlen($categorias[$i]->{"image"}) == 26){
                        if(!file_exists($this->dir_img."categorias/".$categorias[$i]->{"image"})){
                            if(!file_put_contents($this->dir_img."categorias/".$categorias[$i]->{"image"}, file_get_contents("http://www.misitiodelivery.cl/images/categorias/".$categorias[$i]->{"image"}))){
                                // REPORTAR ERROR
                            }
                        }
                    }
                }
            }
            unlink($this->file_act);

        }
        curl_close($ch);
        return $data->{"info"};
    }
    public function get_info_despacho($lat, $lng){

        if(is_dir($this->dir_info)){
            if(file_exists($this->dir_info."polygons.json")){

                $polygons = json_decode(file_get_contents($this->dir_info."polygons.json"));
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
        }
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
        $file = $this->dir_info."pedidos/".$pedido_code.".json";
        
        if(file_exists($file)){
            $data = json_decode(file_get_contents($this->dir_info."pedidos/".$pedido_code.".json"));
            $fecha = $data->{'pedido'}->{'fecha'};
            $diff = time() - $fecha;
            if($diff < 86400){
                $info['op'] = 1;
                $info['data'] = $data;
                $info['js_data'] = json_decode(file_get_contents($this->file_info))->{'code'}.".js";
            }else{
                $info['op'] = 2;
            }
        }else{
            $info['op'] = 2;
        }
        return $info;

    }

}