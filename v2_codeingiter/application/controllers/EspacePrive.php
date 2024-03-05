<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EspacePrive extends CI_Controller {
    
    public function index() {
        if ($this->session->userdata('loggedin') !== true) {
            redirect('connexion'); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        }
        //Chargement de la page d'accueil de l'utilisateur
        $this->load->view('espace_prive');

    }
    public function liste_propositions() {
        //affichage de la page liste_proposition
        if ($this->session->userdata('loggedin') !== true) {
            redirect('connexion'); 
        }
        $this->load->model('Proposition_model');
        $data['propositions'] = $this->Proposition_model->get_propositions_with_users();
        $this->load->view('liste_propositions', $data);
    }
    public function delete_proposition($id_pro){
        if ($this->session->userdata('loggedin') !== true) {
            redirect('connexion'); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        }
        $this->load->model('Proposition_model');
        $this->Proposition_model->delete_proposition($id_pro);
        redirect(base_url('espace_prive/liste_propositions'));
    }
    public function modifier_proposition($id_pro){
        if ($this->session->userdata('loggedin') !== true) {
            redirect('connexion'); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        }

        $this->load->model('Proposition_model');
        $adminId = $this->Proposition_model->getAdminIdForProposition($id_pro);
        $data['is_admin'] = ($this->session->userdata('username') == $adminId);
        $data['proposition'] = $this->Proposition_model->get_proposition_by_id($id_pro);
        $data['versions'] = $this->Proposition_model->get_proposition_versions($id_pro);
        $this->load->view('modifier_proposition',$data);
    }

    public function updateProposition() {
        $this->load->model('Proposition_model');
        // Récupérer les données du formulaire
        $titre = $this->input->post('titre');
        $contenu = $this->input->post('contenu');
        $id_proposition = $this->input->post('id_proposition');
    
        // Récupérer la dernière version de la proposition
        $derniere_version = $this->Proposition_model->getDerniereVersion($id_proposition);
    
        // Vérifier si la dernière version a été faite par un autre utilisateur
        if ($derniere_version['id_uti'] != $this->session->userdata('user_id')) {
            // Mettre à jour la proposition
            $data_proposition = array(
                'titre_pro' => $titre,
                'contenu_pro' => $contenu,
            );
            $this->Proposition_model->update_proposition($id_proposition, $data_proposition);
    
            // Créer une nouvelle version
            $data_version = array(
                'contenumodif_com' => $contenu,
                'datecrea_mod' => date('Y-m-d H:i:s'),
                'id_pro' => $id_proposition,
                'id_uti' => $this->session->userdata('user_id'),
            );
            $this->Proposition_model->create_version($data_version);
        } else {
            // Mise à jour de la proposition sans créer de nouvelle version
            $data_proposition = array(
                'titre_pro' => $titre,
                'contenu_pro' => $contenu,
            );
            $this->Proposition_model->update_proposition($id_proposition, $data_proposition);
            // Mise à jour de la dernière version 
            $data_version = array(
            'contenumodif_com' => $contenu,
            'datecrea_mod' => date('Y-m-d H:i:s'),
            );
            $this->Proposition_model->update_version($derniere_version['id_mod'], $data_version);

        }
    
        redirect(base_url('espace_prive/liste_propositions'));
    }

    public function deconnexion(){
        $this->session->sess_destroy();
        redirect('accueil');
    }
}