<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Connexion extends CI_Controller
{
    public function index()
    {
        $this->load->view('connexion');
    }

    public function connexion_submit()
    {
        // Récupération des données du formulaire
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        

        // Charger le modèle pour interagir avec la base de données 
        $this->load->model('Utilisateur_model');

        // Vérification des informations d'identification dans le modèle
        $user = $this->Utilisateur_model->get_user_by_username($username);

        if (!empty($user)) {
            $hashed_password = $user->mdp_uti;

            // Vérification du mot de passe
            if (hash('sha256', $password) == $hashed_password) {
                // Démarrer la session
                $session_data = array(
                    'loggedin' => true,
                    'username' => $user->nom_uti,
                    'user_id' => $user->id_uti,
                    'user_role' => $user->type_uti
                );
                $this->session->set_userdata($session_data);

               
                redirect('espace_prive');
            } else {
                redirect('connexion?error=WrongPassword');
            }
        } else {
            redirect('connexion?error=WrongUsername');
        }
    }

}


