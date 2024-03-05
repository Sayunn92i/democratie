<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inscription extends CI_Controller {
    public function index() {
        $this->load->view('inscription');
    }

    public function inscription_submit() {
        // Vérification si la méthode est POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Récupération des données du formulaire
            $username = $this->input->post("username");
            $password = $this->input->post("password");
            $confirm_password = $this->input->post("confirm_password");
            $email = $this->input->post("email"); // Ajout de la récupération de l'email
    
            // Vérification si les mots de passe correspondent
            if ($password !== $confirm_password) {
                // Redirection avec un message d'erreur si les mots de passe ne correspondent pas
                redirect('inscription/index?error=password_mismatch');
            }
    
            // Charger le modèle pour interagir avec la base de données (si nécessaire)
            $this->load->model('Utilisateur_model');
            $hashed_password = hash('sha256', $password);
            // Créer un tableau des données à insérer dans la base de données
            $data = array(
                'nom_uti' => $username, // Changement du champ username à nom_uti
                'mdp_uti' => $hashed_password, // Hachage du mot de passe
                'email_uti' => $email, // Ajout du champ email
                'type_uti' => 'Utilisateur' // Spécification du type d'utilisateur, vous pouvez ajuster selon vos besoins
                // Vous pouvez ajouter d'autres champs si nécessaire
            );
    
            // Insérer les données dans la base de données en utilisant le modèle
            $result = $this->Utilisateur_model->inscrire_utilisateur($data);
    
            if ($result) {
                // Redirection avec un message de succès si l'inscription est réussie
                redirect('connexion');
            } else {
                // Redirection avec un message d'erreur si l'inscription a échoué
                redirect('inscription/index?error=registration_failed');
            }
        }
    }
}
