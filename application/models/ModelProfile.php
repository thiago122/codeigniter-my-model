<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelProfile extends MY_model {

    public function __construct()
    {
        parent::__construct();
        $this->table      = 'profile';
        $this->primaryKey = 'id_profile';

        $this->has_many['posts'] = ['ModelPost','author_id'];
        $this->belongs_to['author'] = ['ModelAuthor','author_id'];

    }

}// end class
