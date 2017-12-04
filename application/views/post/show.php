<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	
<h2><?php echo $post->title ?></h2>

<hr>
<article>
	<?php echo $post->content ?>
</article>

<hr>
<ul>
	<li><b>Autor:</b> <?php echo $author->nm_author ?></li>
</ul>

</body>
</html>