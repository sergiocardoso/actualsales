<?php

date_default_timezone_set('America/Sao_Paulo');

Class AjaxController extends CI_Controller {

    protected $user = null;
    private $points = 10;
    private $token = 'c9f83aa06f7df1909befc8cca523b511';
    private $endpoint = 'http://api.actualsales.com.br/join-asbr/ti/lead';

    function __construct(){
        parent::__construct();
        $this->load->model('Region_model', 'region');
        $this->load->model('Unity_model', 'unity');
        $this->load->model('User_model', 'usermodel');
    }


    /*
    | show_regions()
    | List all regions from database
    | Output: json
    |----------------------------
    */
    function show_regions(){
        $region = $this->region->getAll();
        header('Content-type: application/json');
        echo json_encode($region);
    }



    /*
    | show_unity()
    | List all unity from specific region
    | Output: json
    |----------------------------
    */
    function show_unity($region_id){
        $unity = $this->unity->getByRegionID($region_id);
        header('Content-type: application/json');
        echo json_encode($unity);
    }



    /*
    | 
    | MAIN METHOD - data_post()
    | Description: This method is fire after user click on button from page. See the steps:
    |-------------------------------------------------------------------------------------------------
    | 1 - Verify if all data is present and if the key from all data exists.
    | 2 - Initialize Error array
    | 3 - Fire validate method for each key
    | 4 - From validate call validate_field thats apply expression regular for test
    | 5 - Back to data_post method, the array verify which one is not valid, and insert the info error key on Error array
    | 6a - If Error array more than one element, output json message to client
    | 6b - If all is true call the methods statics points(), postData() and record()
    | 7 - Points: Call the RegionConditional and AgeConditional to calculate points
    | 8 - PostData: Send post data to endpoint from ActualSales
    | 9 - Record: Save data on database
    |
    */
    function data_post(){
        
        $postdata = file_get_contents("php://input");
        $user = json_decode($postdata, 'true');
        $error = [];

        if(!is_null($user)){

            if(array_key_exists('user', $user)){
                                
                header('Content-type: application/json'); //OUTPUT

                //keys exists
                if(array_key_exists('nome', $user['user'])
                    && array_key_exists('data_nascimento', $user['user'])
                    && array_key_exists('email', $user['user'])
                    && array_key_exists('telefone', $user['user']))
                {
                    
                    $validation = self::validate($user['user']); 

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
                        
                        $this->user = $user['user'];
                        
                        self::points();
                        self::postData();
                        self::record();

                        echo json_encode(true);
                    }
                }

                else {
                    echo json_encode('Atenção, dados incompletos!');
                }
            }
        }
    }



    /*
    | record()
    | Record data to database
    |----------------------------
    */
    function record(){
        $user = $this->usermodel->record($this->user, $this->points);   
    }



    /*
    | points()
    | Call RegionConditional and AgeConditional to calculate points from user
    |----------------------------
    */
    function points(){
        self::RegionConditional();
        self::AgeConditional();
    }



    /*
    | RegionConditional()
    |----------------------------
    */
    function RegionConditional(){
        switch($this->user['regiao']['nome_regiao'])
        {
            case 'Nordeste':
                $this->points -= 4;
                break;

            case 'Sul':
                $this->points -= 2;
                break;

            case 'Centro-Oeste':
                $this->points -= 3;
                break;

            case 'Norte':
                $this->points -= 5;
                break;

            case 'Sudeste':
                $this->points -= ($this->user['unidade']['nome_unidade'] != 'São Paulo') ? 1 : 0;
                break;
        }
    }



    /*
    | Ageconditional()
    |----------------------------
    */
    function Ageconditional(){
        $actualData = new DateTime('2016-06-01');
        $birthDate = DateTime::CreateFromFormat('dmY', $this->user['data_nascimento']);
        $years = $birthDate->diff($actualData)->y;
       
        if($years >= 100 || $years < 18)
        {
            $this->points -= 5;
        }

        else if($years >= 40 || $years <= 99)
        {
            $this->points -= 3;
        }
    }



    /*
    | postData()
    | Send data to endpoint from ActualSales;
    |----------------------------
    */
    function postData(){

        $POST_DATA                    = [];
        $POST_DATA['nome']            = $this->user['nome'];
        $POST_DATA['email']           = $this->user['email'];
        $POST_DATA['telefone']        = $this->user['telefone'];
        $POST_DATA['regiao']          = $this->user['regiao']['nome_regiao'];
        $POST_DATA['unidade']         = ($this->user['unidade']['id'] == 0) ? 'INDISPONÍVEL' : $this->user['unidade']['nome_unidade'];
        $data_nascimento              = DateTime::CreateFromFormat('dmY', $this->user['data_nascimento']);

        $POST_DATA['data_nascimento'] = $data_nascimento->format('Y-m-d');
        $POST_DATA['score']           = $this->points;
        $POST_DATA['token']           = $this->token;

        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $POST_DATA);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        //var_dump($response);
    }



    /*
    | validate()
    | For each key call the validate_field method to apply regex pattern;
    |----------------------------
    */
    function validate($user){

        $validation = [];

        foreach($user as $key => $field){
            if(!is_array($field)){
                if($key == 'data_nascimento') $field = self::makeDate($field); //format date
                if($key == 'telefone') $field = self::makePhone($field); //format phone
                $validation[$key] = self::validate_field($field, $key);
            }
        }

        return $validation;
    }



    /*
    | validate_field()
    | Library for all patterns for this test inside a array
    | Return true is pattern is correct or the error message if's not;
    |----------------------------
    */
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


    /*
    | makeDate()
    | This helper make the date in correct format!
    | FIX: Angular JS UI-MASK remove some special caracters
    |----------------------------
    */
    function makeDate($value){
        $temp = substr($value, 0, 2).'/'.substr($value,2,2).'/'.substr($value,4,4);
        return $temp;
    }



    /*
    | makePhone()
    | This helper make the phone in correct format!
    | FIX: Angular JS UI-MASK remove some special caracters
    |----------------------------
    */
    function makePhone($value){
        $temp = '('.substr($value, 0, 2).') '.substr($value, 2, 5).'-'.substr($value, 7, 4);
        return $temp;
    }
}