<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller {

	public function __construct(){

		parent::__construct();

		$this->load->model('ModelPost');

	}

	public function index()
	{
		// LIST ALL
		$posts = $this->ModelPost->joinMany('author')->all();

		foreach ($posts as $post) {
			echo ' <br> ' . $post->id_post . ' - ' . $post->title . ' - ' . $post->nm_author;
		}

		// $this->ModelPost->attach('categories', 2, [1,2,3]);
		 
		echo '<hr>';

		$categories = $this->ModelPost->getMany('categories', 2);
		
		foreach ($categories as $category) {
			echo ' <br> ' . $category->id_category . ' - ' . $category->nm_category;
		}
		
	}

	public function saveCategory(){

	}


	public function store(){

		$insert = [
			'title' => 'Post - 4',
			'content' => 'Content',
			'author_id' => 3
		];

		$this->ModelPost->save($insert);
	}

	public function update($idPost){
		
		$update = [
			'title' => 'Post - 4',
			'content' => 'Content',
			'author_id' => 3
		];

		$this->ModelPost->save($update, $idPost);
	}

	public function show($idPost = 2){

		// Junta o author no mesmo resultado
		$post = $this->ModelPost->joinMany('author')->find($idPost);
		echo ' <br> ' . $post->id_post . ' - ' . $post->title . ' - ' . $post->nm_author;

		// ou pega separadamente
		$post = $this->ModelPost->find($idPost); 
		$author = $this->ModelPost->hasOne('author', $idPost);

		echo ' <br><br><br> ' . $post->id_post . ' - ' . $post->title;
		echo ' <br> ' . $author->id_author . ' - ' . $author->nm_author;

	}
}
