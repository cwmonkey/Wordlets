<h1><?=w('Title') ?></h1>

<? if ( $subtitle = w('SubTitle') ): ?>
	<h2 <?=$subtitle->HtmlAttrs() ?>>
		<?=$subtitle->single(false) ?>
	</h2>
<? endif ?>

<? if ( $image = w('Image') ): ?>
	<div <?=$image->HtmlAttrs() ?>>
		<img src="<?=$image->src ?>" alt="<?=$image->alt ?>">
	</div>
<? endif ?>

<? if ( $list = w('List') ): ?>
	<ul>
		<? foreach ( $list as $key => $value ): ?>
			<li><?=$value ?></li>
		<? endforeach ?>
	</ul>
<? endif ?>



<nav>
	<ul>
		<li><a href="?admin=1">admin view</a></li>
		<li><a href="?page=form">add wordlets</a></li>
	</ul>
</nav>