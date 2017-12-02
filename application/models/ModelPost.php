<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelPost extends MY_model {

	public function __construct()
	{
		parent::__construct();
		$this->table 		= 'posts';
		$this->primaryKey 	= 'id_post';

		$this->belongs_to['author'] = ['ModelAuthor','author_id'];
		$this->belongs_to_many['categories'] = ['ModelCategory', 'posts_categories', 'post_id', 'category_id'];
	}


}// end class
