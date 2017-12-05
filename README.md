# codeigniter-my-model

Simples MY Model para o codeigniter, desenvolvido para evitar a repetição de código manter os controllers, models limpos, fazer o desenvolvimento ficar muito mais rápido.

IMPORTANTE: Está em desenvolvimento e não está com documentação completa


Para ajudar a explicar vou usar um blog como exemplo:

### O básico

Model Post básico com a configuração mínima
```php

class ModelPost extends MY_model {

	public function __construct()
	{
		parent::__construct();
		$this->table 		= 'posts';
		$this->primaryKey 	= 'id_post';
	}

}

```

No controller
```php

// Listar todos
$this->ModelPost->all();

// retorna um
$this->ModelPost->find(1);

// retorna vários
$this->ModelPost->find([1,2,3]);

// Where básico
$this->ModelPost->where('author','1')->all();

// Like básico
$this->ModelPost->like('title','meu título')->all();

// Ordenação básico
$this->ModelPost->order('title','ASC')->all();

// String para o select
$this->ModelPost->fields('title')->all();

// Count
$this->ModelPost->where('author','1')->count();

// Limit
$this->ModelPost->limit($limit, $offset)->all();

// Salvar - Retorna o id
$insert = [
	'title' => 'Meu título'
	'content' => 'Meu conteúdo',
	'author_id'=> 1
];

$this->ModelPost->save($insert);

// Atualizar
$update = [
	'title' => 'Meu título'
	'content' => 'Meu conteúdo',
	'author_id'=> 1
];

$idPost = 1;
$this->ModelPost->save($update, $idPost);

// Excluir
$idPost = 1;
$this->ModelPost->delete($idPost);


```

## Relancionamentos



Model Post
```php

class ModelPost extends MY_model {

	public function __construct()
	{
		parent::__construct();
		$this->table 		= 'posts';
		$this->primaryKey 	= 'id_post';

		// -------------------------------------------------------------------------------------------------

		// Um post pertence a um autor
		//                apelido         model        fk
		$this->belongs_to['author'] = ['ModelAuthor','author_id'];
		
		// ONDE
		// apelido: nome dados ao model que se relaciona com o post 
		// model:   classe model com as configurações do autor( mais abaixo ) 
		// fk:      nome da chave que o post recebe para indicar o id do autor que publicou o post 

		// -------------------------------------------------------------------------------------------------
		// um post pertence a muitas categorias
		//                      apelido            model           tabela pivot       fk principal	 fk secundaria
		$this->belongs_to_many['categories'] = ['ModelCategory', 'posts_categories', 'post_id',     'category_id'];

		// apelido: 		nome dados ao model que se relaciona com o post 
		// model:   		classe model com as configurações do autor( mais abaixo ) 
		// tabela pivot:    tabela que comporta o relacionamento e recebe as chaves
		// fk principal:    chave que se refere ao model atual no caso o post_id
		// fk secundária    chave que se refere ao model model referenciado no caso category_id

	}


}// end class

```

Model Author
```php

	public function __construct()
	{
		parent::__construct();
		$this->table 	  = 'authors';
		$this->primaryKey = 'id_author';

		// -------------------------------------------------------------------------------------------------
		// Um autor possui vários posts
		//               apelido         model      fk
		$this->has_many['posts'] = ['ModelPost','author_id'];

		// ONDE
		// apelido: nome dados ao model que se relaciona com o post 
		// model:   classe model com as configurações do post 
		// fk:      nome da chave que o post recebe para indicar o id do autor que publicou o post 

	}

	}// end class

```

Model Category básico
```php
	class ModelCategory extends MY_model {

		public function __construct()
		{
			parent::__construct();
			$this->table 	  = 'categories';
			$this->primaryKey = 'id_category';
		}

	}// end class

```

### Métodos de relacionamento

Model Category básico
```php
	
	// listar os post e relizar o join para trazer os autores
	$this->ModelPost->join('author')->all();

	// Pegar o author do post
	$post 	= $this->ModelPost->find($idPost); 
	$author = $this->ModelPost->hasOne('author', $post->author_id);

	// ou
	$post 	= $this->ModelPost->join('author')->find($idPost);

	// categorias de um determinado post
	$this->ModelPost->belongsToMany('categories', $post->id_post);

	// Todas a categorias com um vetor extra para indicar qual pertence ao post
	$this->ModelPost->belongsToMany('categories', $post->id_post, true);

	// adicionando categorias ao post
	$this->ModelPost->attach('categories', $idPost, [1,2,3]);

	// Removendo uma categoria do post
	$this->ModelPost->attach('categories', $idPost, 1);

```

Lembrando que no model podem ser criados métodos normalmente e se você usar o $this como retorno 
poderá encadear com os métodos normalmente.


Um controller usando os métodos

```php
	
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
		
		$post 	 		 = $this->ModelPost->find($idPost); 
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
			$this->ModelPost->attach('categories', $idPost, $categoriesToSave);
		} 
		
		redirect('Post');
	}

	public function delete($idPost)
	{
		
		$this->ModelPost->delete( $idPost );
		
		redirect('Post');
	}
}


```