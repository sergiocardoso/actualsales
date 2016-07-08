<?php

Class AjaxController extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Region_model', 'region');
        $this->load->model('Unity_model', 'unity');
    }

    function show_regions(){
        $region = $this->region->getAll();
        header('Content-type: application/json');
        echo json_encode($region);
    }

    function show_unity($region_id){
        $unity = $this->unity->getByRegionID($region_id);
        header('Content-type: application/json');
        echo json_encode($unity);
    }

    function data_post(){
        
        $postdata = file_get_contents("php://input");
        $user = json_decode($postdata, 'true');
        $error = [];

        if(!is_null($user)){

            if(array_key_exists('user', $user)){
                
                $validation = self::validate($user['user']); 
                
                header('Content-type: application/json'); //OUTPUT

                //keys exists
                if(array_key_exists('nome', $user['user'])
                    && array_key_exists('data_nascimento', $user['user'])
                    && array_key_exists('email', $user['user'])
                    && array_key_exists('telefone', $user['user']))
                {
                    
                    //validation data
                    foreach($validation as $field){

                        if(!is_bool($field)){
                            array_push($error, $field);
                        }
                    }

                    
                    if(count($error) > 0){
                        echo json_encode($error);
                    }
                    else {
                        echo json_encode(true);
                    }
                }

                else {
                    echo json_encode('Atenção, dados incompletos!');
                }
            }
        }
    }

    function validate($user){

        $validation = [];

        foreach($user as $key => $field){
            if(!is_array($field)){
                if($key == 'data_nascimento') $field = self::makeData($field); //format date
                if($key == 'telefone') $field = self::makePhone($field); //format phone
                $validation[$key] = self::validate_field($field, $key);
            }
        }

        return $validation;
    }

    function validate_field($value, $formatKey){
        
        $format = [];
        $format['nome']['pattern'] = '/^([a-zA-zÀ-úà-ú]+\s[a-zA-zÀ-úà-ú]+)*$/';
        $format['nome']['error'] = 'nome inválido [ é necessário no mínimo duas palavras ]';

        $format['data_nascimento']['pattern'] = '/^(0[1-9]|[12]\d|3[01])[\/]+(0?[1-9]|1[012])[\/]+(19|20)\d{2}$/';
        $format['data_nascimento']['error'] = 'formato de data de nascimento inválida';

        $format['email']['pattern'] = '/^\S+@\S+\.\S+$/';
        $format['email']['error'] = 'formato de e-mail inválido';

        $format['telefone']['pattern'] = '/^\([0-9]{2}\)+\s[9][0-9]{4}\-[0-9]{4}$/';
        $format['telefone']['error'] = 'formato de telefone inválido';


        if(preg_match($format[$formatKey]['pattern'], $value, $result)){
            return true;
        }
        else {
            return $format[$formatKey]['error'];
        }
    }

    function makeData($value){
        $temp = substr($value, 0, 2).'/'.substr($value,2,2).'/'.substr($value,4,4);
        return $temp;
    }

    function makePhone($value){
        $temp = '('.substr($value, 0, 2).') '.substr($value, 2, 5).'-'.substr($value, 7, 4);
        return $temp;
    }
}