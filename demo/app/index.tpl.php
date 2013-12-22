<h1><?=w('Title') ?></h1>

<? if ( $subtitle = w('SubTitle') ): ?>
	<h2 <?=wa($subtitle) ?>>
		<?=$subtitle->single(false) ?>
	</h2>
<? endif ?>

<? if ( $image = w('Image') ): ?>
	<div <?=wa($image) ?>>
		<img src="<?=$image->src ?>" alt="<?=$image->alt ?>">
	</div>
<? endif ?>

<? if ( ($list = w('List')) && count($list) ): ?>
	<ul>
		<? foreach ( $list as $key => $value ): ?>
			<li><?=$value ?></li>
		<? endforeach ?>
	</ul>
<? endif ?>

<?=w('NotMade') ?>

<? if ( ($nmlist = w('NotMadeList')) && count($nmlist) ): ?>
	<ul>
		<? foreach ( $nmlist as $key => $value ): ?>
			<li><?=$value ?></li>
		<? endforeach ?>
	</ul>
<? endif ?>

<nav>
	<ul>
		<li><a href="?admin=1">admin view</a></li>
		<li><a href="?">regular view</a></li>
		<li><a href="?page=form">add wordlets</a></li>
	</ul>
</nav>