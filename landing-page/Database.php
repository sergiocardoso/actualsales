<?php

trait Database {

    protected $db;

    private $userdb = 'root';
    private $passdb = 'root';

    public function connect(){
     
        try {
            $this->db = new PDO('mysql:host=localhost;dbname=Test', $this->userdb, $this->passdb);
        } 

        catch(PDOException $e){
            print 'Connection Error -->'.$e->getMessage();
            die;
        }
    }

    function __desctruct(){
        $this->db = null;
    }

    public function insert($user){

        self::connect();
       
        $SQL = "INSERT INTO usuarios(nome,
                data_nascimento,
                email,
                telefone,
                pontos,
                unidades_id) VALUES (
                :nome,
                :data_nascimento,
                :email,
                :telefone,
                :pontos,
                :unidades_id)";

        $query = $this->db->prepare($SQL);
        $query->bindParam(':nome', $user['user']['nome'], PDO::PARAM_STR);
        $query->bindParam(':data_nascimento', $user['user']['data_nascimento'], PDO::PARAM_STR);
        $query->bindParam(':email', $user['user']['email'], PDO::PARAM_STR);
        $query->bindParam(':telefone', $user['user']['telefone'], PDO::PARAM_STR);
        $query->bindParam(':pontos', $user['user']['pontos'], PDO::PARAM_STR);
        $query->bindParam(':unidades_id', $user['user']['unidade']['id'], PDO::PARAM_STR);
        $query->execute();
    }
}