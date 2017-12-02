<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

	public function __construct(){

		parent::__construct();

		$this->load->model('ModelCategory');

	}

	public function index()
	{
		// LIST ALL
		$categories = $this->ModelCategory->all();

		foreach ($categories as $category) {
			echo ' <br> ' . $category->id_category . ' - ' . $category->nm_category;
		}
		
	}

	public function store(){

		$this->ModelCategory->save([
			'nm_category' => 'Car'
		]);
		
	}

}
