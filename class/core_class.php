<?php

class Core{
    
    public $host = null;
    public $dir_info = null;
    public $file_info = null;
    public $dir_data = null;

    public function __construct(){

        //$this->host = "www.fireapp.cl";
        $this->host = $_SERVER["HTTP_HOST"];

        if($_SERVER["HTTP_HOST"] == "localhost"){
            $this->dir_info = "C:/var/".$this->host."/";
            $this->file_info = "C:/var/".$this->host."/last.json";
            $this->dir_data = "C:/AppServ/www/restaurants_web/deliveryweb/data/";
            $this->dir_img = "C:/AppServ/www/restaurants_web/deliveryweb/images/";
        }else{
            $this->dir_info = "/var/data/".$this->host."/";
            $this->file_info = "/var/data/".$this->host."/last.json";
            $this->dir_data = "/var/www/html/data/";
            $this->dir_img = "/var/www/html/images/";
        }

    }

    public function get_data(){

        if(is_dir($this->dir_info)){
            if(file_exists($this->file_info)){
                return json_decode(file_get_contents($this->file_info));
            }else{
                $send["host"] = $this->host;
                $send["ft"] = 1;
                return $this->curlData($send);
            }
        }else{
            if(mkdir($this->dir_info, 0777)){
                $send["host"] = $this->host;
                $send["ft"] = 1;
                return $this->curlData($send);
            }
        }

    }
    public function curlData($send){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://misitiodelivery.cl/servicio.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
        $data = json_decode(curl_exec($ch));
 
        /*
        if(file_exists($this->file_info)){
            rename($this->file_info, $this->dir_info.date("Ymd", filemtime($this->file_info)).".json");
        }
        */

        if(file_put_contents($this->file_info, json_encode($data->{"info"}))){
            
            echo "<pre>";
            print_r($data->{"info"});
            echo "</pre>";
        }

        if(file_put_contents($this->dir_data.$data->{"info"}->{"js_data"}, "var data=".json_encode($data->{"data"}))){
            
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

        curl_close($ch);
        return $data->{"info"};

    }

}