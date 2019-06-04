<?php

class Core{
    
    public $host = null;
    public $dir_info = null;
    public $file_info = null;

    public function __construct(){

        //$this->host = "www.fireapp.cl";
        $this->host = $_SERVER["HTTP_HOST"];

        if($_SERVER["HTTP_HOST"] == "localhost"){
            $this->dir_info = "C:/var/".$this->host."/";
            $this->file_info = "C:/var/".$this->host."/last.json";
        }else{
            $this->dir_info = "/var/data/".$this->host."/";
            $this->file_info = "/var/data/".$this->host."/last.json";
        }

    }

    public function get_data(){

        if(is_dir($this->dir_info)){
            echo "0";
            if(file_exists($this->file_info)){
                return json_decode(file_get_contents($this->file_info));
            }else{
                $send['host'] = $this->host;
                $send['ft'] = 1;
                return $this->curlData($send);
            }
        }else{
            if(mkdir($this->dir_info, 0777)){
                $send['host'] = $this->host;
                $send['ft'] = 1;
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
 
        if(file_exists($this->file_info)){
            rename($this->file_info, $this->dir_info.date("Ymd", filemtime($this->file_info)).".json");
        }

        if(!file_put_contents($this->file_info, json_encode($data->{"info"}))){
            // REPORTAR ERROR
        }
        //file_put_contents($this->data, "var data=".json_encode($data->{"data"}));
        curl_close($ch);
        return $data;

    }

}