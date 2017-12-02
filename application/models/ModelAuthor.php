<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelAuthor extends MY_model {

	public function __construct()
	{
		parent::__construct();
		$this->table 	  = 'authors';
		$this->primaryKey = 'id_author';

		$this->has_many['posts'] = ['ModelPost','author_id'];

	}

}// end class
