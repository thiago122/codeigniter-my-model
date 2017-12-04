<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller {

	public function __construct(){

		parent::__construct();

		$this->load->model(['ModelPost','ModelAuthor','ModelCategory']);
		$this->load->helpers(['form','url']);
		$this->load->library(['form_validation']);

	}

	public function index()
	{
		$dados['posts'] 	 = $this->ModelPost->join('author')->all();
		$dados['categories'] = $this->ModelPost->belongsToMany('categories', 2);

		$this->load->view('post/index', $dados);

	}

	public function create($idPost = null)
	{
		
		$dados['authors'] = $this->ModelAuthor->all();
		$dados['categories'] = $this->ModelCategory->all();

		$this->load->view('post/create', $dados);
	} 

	public function edit($idPost = null)
	{
		
		$post 	 		 = $this->ModelPost->join('author')->find($idPost); 
		$authors 		 = $this->ModelAuthor->all();
		$categories 	 = $this->ModelPost->belongsToMany('categories', $post->id_post, true);

		$dados['post'] 	 = $post; 
		$dados['authors'] = $authors;
		$dados['categories'] = $categories;
		
		$this->load->view('post/edit', $dados);
	}

	public function show($idPost = null)
	{

		$post 	= $this->ModelPost->find($idPost); 
		$author = $this->ModelPost->hasOne('author', $post->author_id);

		$dados['post'] 	 = $post; 
		$dados['author'] = $author;

		$this->load->view('post/show', $dados);
	}

	public function store()
	{

		$update = [
			'title' 	=> $this->input->post('title', true),
			'content' 	=> $this->input->post('content', true),
			'author_id' => $this->input->post('author_id', true),
		];

		$idPost = $this->ModelPost->save($insert);
		$this->ModelPost->attach('categories', $idPost, $_POST['category']);

		redirect('Post');
	}

	public function update($idPost)
	{
		
		$update = [
			'title' 	=> $this->input->post('title', true),
			'content' 	=> $this->input->post('content', true),
			'author_id' => $this->input->post('author_id', true),
		];

		$this->ModelPost->save($update, $idPost);

		$categoriesToSave = $this->input->post('category');

		if($categoriesToSave){
			$this->ModelPost->attach('categories', $idPost, $categories);
		} 
		
		redirect('Post');
	}

	public function delete($idPost)
	{
		
		$this->ModelPost->delete( $idPost );
		
		redirect('Post');
	}
}
