<?php

class User_model extends CI_Model {

    function insert_user($data){
        $this->db->insert('t_utilisateur_uti',$data);

    }
}