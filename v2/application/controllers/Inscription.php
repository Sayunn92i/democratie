<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inscription extends CI_Controller {
    public function index() {
        $this->load->view('inscription');
    }

    public function inscription_submit() {
        
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $username = $_POST["username"];
            $password = $_POST["password"];
            $confirm_password = $_POST["confirm_password"];

            $data = array(
                'nom_uti' => $username,
                'mdp_uti' => 
                
            );
        }
        
    }
    
}


