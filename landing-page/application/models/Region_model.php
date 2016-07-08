<?php

Class Region_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function getAll(){
        $SQL = "SELECT id, nome_regiao FROM regioes";
        $QUERY = $this->db->query($SQL);

        return $QUERY->result();
    }
}