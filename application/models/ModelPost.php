<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelPost extends MY_model {

	public function __construct()
	{
		parent::__construct();
		$this->table 		= 'posts';
		$this->primaryKey 	= 'id_post';

		$this->belongs_to['author'] = ['ModelAuthor','author_id'];
	}


}// end class
