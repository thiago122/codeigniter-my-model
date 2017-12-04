<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>

<div>
	<?php echo validation_errors(); ?>
</div>

<a href="<?php echo base_url('Post/create/') ?>">Novo</a>	
<table border="1">

	<tr>
		<th>Título</th>
		<th>Autor</th>
		<th></th>
		<th></th>
		<th></th>
	</tr>

	<?php foreach($posts as $post): ?>
	<tr>
		<td>
			<?php echo $post->title ?>	
		</td>
		<td>
			<?php echo $post->nm_author ?>	
		</td>
		<td>
			<a href="<?php echo base_url('Post/show/'.$post->id_post) ?>">Visualizar</a>
		</td>
		<td>
			<a href="<?php echo base_url('Post/edit/'.$post->id_post) ?>">Editar</a>
		</td>
		<td>
			<a href="<?php echo base_url('Post/delete/'.$post->id_post) ?>">Excluir</a>
		</td>
	</tr>
	<?php endforeach; ?>
</table>


</body>
</html>