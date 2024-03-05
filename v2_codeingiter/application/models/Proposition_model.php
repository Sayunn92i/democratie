<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proposition_model extends CI_Model {

    // Créer une nouvelle proposition
    public function create_proposition($data) {
        $this->db->insert('t_proposition_pro', $data);
        return $this->db->insert_id();
    }

    // Récupérer une proposition par son ID
    public function get_proposition_by_id($id_pro) {
        return $this->db->get_where('t_proposition_pro', array('id_pro' => $id_pro))->row_array();
    }

    public function getAdminIdForProposition($propositionId)
    {
        // Préparez la requête SQL pour récupérer l'ID de l'administrateur du groupe
        $sql = "SELECT admin FROM t_groupe_grp WHERE id_grp = (SELECT id_grp FROM t_proposition_pro WHERE id_pro = ?)";
        
        // Exécutez la requête avec l'ID de proposition en paramètre
        $query = $this->db->query($sql, array($propositionId));
        
        // Vérifiez s'il y a des résultats
        if ($query->num_rows() == 1) {
            // Récupérez la ligne de résultat
            $row = $query->row_array();
            // Récupérez l'ID de l'administrateur
            $adminId = $row['admin'];
            // Retournez l'ID de l'administrateur
            return $adminId;
        } else {
            // Aucun résultat trouvé, retournez null ou une valeur par défaut selon votre besoin
            return null;
        }
    }

    
    public function getDerniereVersion($id_proposition) {
        $this->db->select('*');
        $this->db->from('t_modification_mod');
        $this->db->where('id_pro', $id_proposition);
        $this->db->order_by('datecrea_mod', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }
    //met à jour la proposition
    public function update_proposition($id_proposition, $data_proposition) {
        $this->db->where('id_pro', $id_proposition);
        $this->db->update('t_proposition_pro', $data_proposition);
        return $this->db->affected_rows();
    }

    //met à jour les versions
    public function update_version($id_modification, $data_version) {
        $this->db->where('id_mod', $id_modification);
        $this->db->update('t_modification_mod', $data_version);
        return $this->db->affected_rows();
    }


    //creer une version
    public function create_version($data_version) {
        $this->db->insert('t_modification_mod', $data_version);
        return $this->db->insert_id();
    }

    // Supprimer une proposition
    public function delete_proposition($id_pro) {
        $this->db->where('id_pro', $id_pro);
        $this->db->delete('t_proposition_pro');

        // Supprimer les lignes correspondantes dans t_modification_mod
    $this->db->where('id_pro', $id_pro);
    $this->db->delete('t_modification_mod');
        return $this->db->affected_rows();
    }

    // Récupérer toutes les propositions
    public function get_all_propositions() {
        return $this->db->get('t_proposition_pro')->result_array();
    }public function get_propositions_with_users() {
        $this->db->select('t_proposition_pro.*, t_groupe_grp.nom_grp, GROUP_CONCAT(t_utilisateur_uti.nom_uti) as utilisateurs');
        $this->db->from('t_proposition_pro');
        $this->db->join('t_groupe_grp', 't_proposition_pro.id_grp = t_groupe_grp.id_grp');
        $this->db->join('t_possede_pos', 't_proposition_pro.id_grp = t_possede_pos.id_grp', 'left');
        $this->db->join('t_utilisateur_uti', 't_possede_pos.id_uti = t_utilisateur_uti.id_uti', 'left');
        $this->db->group_by('t_proposition_pro.id_pro');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_proposition_versions($id_pro) {
        // Requête SQL pour récupérer les versions précédentes avec le nom de l'utilisateur
        $this->db->select('m.contenumodif_com, m.datecrea_mod, u.nom_uti');
        $this->db->from('t_modification_mod m');
        $this->db->join('t_utilisateur_uti u', 'm.id_uti = u.id_uti');
        $this->db->where('m.id_pro', $id_pro);
        $this->db->order_by('m.datecrea_mod', 'DESC'); // Trie du plus récent au plus ancien
    
        // Exécuter la requête et récupérer les résultats
        $query = $this->db->get();
    
        // Vérifier s'il y a des résultats
        if ($query->num_rows() > 0) {
            // Retourner les résultats sous forme de tableau associatif
            return $query->result_array();
        } else {
            // Aucune version précédente disponible
            return array();
        }
    }
}
