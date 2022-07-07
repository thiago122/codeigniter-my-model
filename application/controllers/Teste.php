<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teste extends CI_Controller {

    public function __construct(){

        parent::__construct();

        $this->load->model(['ModelPost','ModelAuthor','ModelCategory','ModelProfile']);
        $this->load->helpers(['form','url']);
        $this->load->library(['form_validation']);

    }

    public function index()
    {

        $author = $this->ModelAuthor->find(2);
        $perfil = $this->ModelAuthor->hasOne('profile', $author);

        print_r($perfil);


        $profile = $this->ModelProfile->find([1]);

        $autor = $this->ModelProfile->belongsTo('author', $profile);
        // print_r($this->db->last_query()); 

    }


}
