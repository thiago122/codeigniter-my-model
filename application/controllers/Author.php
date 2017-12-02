<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Author extends CI_Controller {

	public function __construct(){

		parent::__construct();

		$this->load->model('ModelAuthor');

	}

	public function index()
	{
		// LIST ALL
		$authors = $this->ModelAuthor->all();

		foreach ($authors as $author) {
			echo ' <br> ' . $author->id_author . ' - ' . $author->nm_author;
		}
		
	}

	public function store(){

		$this->ModelAuthor->save([
			'nm_author' => 'Jesus'
		]);
	}


	public function show($idAuthor = 2){

		$author = $this->ModelAuthor->find($idAuthor);
		$posts  = $this->ModelAuthor->hasMany('posts', $idAuthor);
		
		foreach ($posts as $post) {
			echo ' <br> ' . $post->id_post . ' - ' . $post->title . ' - ' . $post->author_id	;
		}

	}

}
