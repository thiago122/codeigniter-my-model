<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelCategory extends MY_model {

	public function __construct()
	{
		parent::__construct();
		$this->table 	  = 'categories';
		$this->primaryKey = 'id_category';
	}

}// end class
