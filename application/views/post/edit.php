<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>

<?php echo form_open(base_url('post/update/'.$post->id_post)) ?>	
	<label>Título</label><br>
	<input type="text" name="title" value="<?php echo $post->title ?>">

	<br><br>
	<label>Conteúdo</label><br>
	<textarea name="content" ><?php echo $post->content ?></textarea>

	<br><br>
	<label>Categorias</label><br>
	
		<?php foreach($categories as $category): ?>
			<br>

			<input type="checkbox" name="category[]" value="<?php echo $category->id_category ?>" <?php echo set_checkbox('category[]', $category->id_category, $category->ckecked ); ?>>

			<?php echo $category->nm_category ?>
		<?php endforeach ?>
	
	<br><br>

	<br><br>
	<label>Autor</label><br>
	<select name="author_id">
		<?php foreach($authors as $author): ?>
		<?php $selected = ( $author->id_author == $post->author_id )? ' selected="selected" ': '' ?>
		<option value="<?php echo $author->id_author ?>" <?php echo $selected ?>><?php echo $author->nm_author ?></option>
		<?php endforeach ?>
	</select>

	<br><br>

	<input type="submit" value="atualizar">
<?php echo form_close();?>	

</body>
</html>