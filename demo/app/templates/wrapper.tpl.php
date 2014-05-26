<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?=wn('Site Title') ?></title>
	<meta name="description" content="<?=wn('Meta Description') ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<? foreach ( $styles as $style ): ?>
		<link rel="stylesheet" href="<?=$style ?>">
	<? endforeach ?>
</head>
<body>

<? if ( $user == 'admin' || $user == 'editor' ): ?>
	<div id="wordlet_page_config">
		<p>Meta Description: <?=w('Meta Description') ?></p>
	</div>
<? endif ?>

<div id="site_wrapper">
	<header id="site_header" role="banner">
		<p class="source">
			<a href="https://github.com/cwmonkey/Wordlets/blob/master/demo/app/templates/wrapper.tpl.php">wrapper.tpl.php on github</a>
		</p>
		<h2 id="site_title"><?=w('Site Title') ?></h2>
		<nav id="menu">
			<a class="config" href="?user=admin">Admin</a>
			<a class="config" href="?user=editor">Editor</a>
			<a class="config" href="?user=user">User</a>
			<a class="config" href="?do=delete">Delete All</a>
			<a class="config" href="?do=reset">Reset</a>

			<a href="/demo/?page=mysite-index">Microsite Example</a>
		</nav>
	</header>

	<div id="site_content">
		<?=$content ?>
	</div>

	<footer id="site_footer"><?=w('Footer') ?></footer>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="vendor/jquery/jquery-1.10.2.min.js"><\/script>')</script>
<? foreach ( $scripts as $script ): ?>
	<script src="<?=$script ?>"></script>
<? endforeach ?>

</body>
</html>