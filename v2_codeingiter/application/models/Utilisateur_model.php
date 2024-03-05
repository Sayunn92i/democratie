<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Utilisateur_model extends CI_Model {

    //récupere les infos de l'utilsateur avec son pseudo
    public function get_user_by_username($username) {
        $query = $this->db->get_where('t_utilisateur_uti', array('nom_uti' => $username));

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return null;
        }
    }

    //inscrire un utilisateur
    public function inscrire_utilisateur($data) {
        // Insérer les données de l'utilisateur dans la table utilisateur
        $this->db->insert('t_utilisateur_uti', $data);
        
        // Vérifier si l'insertion a réussi en retournant l'ID de l'utilisateur inséré
        return $this->db->insert_id();
    }

}
