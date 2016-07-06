<?php

require_once 'Database.php';

Class UserModel 
{

    use Database;

    private $points = 10;
    private $token = 'c9f83aa06f7df1909befc8cca523b511';
    private $endpoint = 'http://api.actualsales.com.br/join-asbr/ti/lead';
    private $user = [];

    public function __construct($user)
    {
        $this->user = $user;
        self::calculatePoints();
    }


    public function getPoints()
    {
        return $this->points;
    }

    public function getToken()
    {
        return $this->token;
    }

    /**
     * Send data to ActualSales endpoint
     */
    public function sendPostData(){

        $POST_DATA                    = [];
        $POST_DATA['nome']            = $this->user['user']['nome'];
        $POST_DATA['email']           = $this->user['user']['email'];
        $POST_DATA['telefone']        = $this->user['user']['telefone'];
        $POST_DATA['regiao']          = $this->user['user']['regiao']['name'];
        $POST_DATA['unidade']         = ($this->user['user']['unidade']['id'] == 0) ? 'INDISPONÍVEL' : $this->user['user']['unidade']['name'];
        $data_nascimento              = DateTime::CreateFromFormat('d/m/Y', $this->user['user']['data_nascimento']);

        $POST_DATA['data_nascimento']  = $data_nascimento->format('Y-m-d');
        $POST_DATA['score']           = $this->getPoints();
        $POST_DATA['token']           = $this->getToken();

        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $POST_DATA);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
    }

    /**
     * Save the data on the database using Trait Database;
     */
    public function saveDatabase()
    {
        $this->insert($this->user);
    }


    protected function calculatePoints()
    {
        self::RegionConditional();
        self::AgeConditional();
    }


    /*
    | RegionConditional()
    |----------------------------
    */
    protected function RegionConditional()
    {

        switch($this->user['user']['regiao']['name'])
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
                $this->points -= ($this->user['user']['regiao']['name'] != 'São Paulo') ? 1 : 0;
                break;
        }

    }

    /*
    | AgeConditional()
    |----------------------------
    */
    protected function AgeConditional()
    {

        $actualData = new DateTime('2016-06-01');
        $birthDate = DateTime::CreateFromFormat('d/m/Y', $this->user['user']['data_nascimento']);
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
}