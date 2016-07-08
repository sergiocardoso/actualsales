<?php

Class Unity_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function getByRegionID($id){
        $SQL = "SELECT id, nome_unidade FROM unidades WHERE regioes_id like ".$id." ORDER BY nome_unidade ASC";
        $QUERY = $this->db->query($SQL);

        return $QUERY->result();
    }
}