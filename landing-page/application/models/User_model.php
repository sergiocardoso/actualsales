<?php

Class User_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function record($user, $points){
        $QUERY = "INSERT INTO usuario(nome, data_nascimento, email, telefone, unidades_id, pontos)
        VALUES (
            '".$user['nome']."', 
            '".$user['data_nascimento']."', 
            '".$user['email']."', 
            '".$user['telefone']."', 
            '".$user['unidade']['id']."', 
            '".$points."')";  

        if($this->db->query($QUERY)){
            return true;
        }     
    }
}