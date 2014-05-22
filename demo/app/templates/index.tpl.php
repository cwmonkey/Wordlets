<link rel="stylesheet" href="wordlets.css"/>

<p>Directly printing a wordlet:</p>
<h1><?=w('Title') ?></h1>

<h1><?=$w->Title ?></h1>

<p>Only showing markup if wordlet has been made, otherwise showing wordlet creation markup only</p>
<? if ( $subtitle = w('SubTitle') ): ?>
	<h2 <?=wa($subtitle) ?>>
		<?=$subtitle->single(false) ?>
	</h2>
<? endif ?>

<? if ( $subtitle = $w->SubTitle ): ?>
	<h2 <?=$subtitle->HtmlAttrs() ?>>
		<?=$subtitle->single(false) ?>
	</h2>
<? endif ?>

<p>Using attributes which do not output wordlet markup. Adding wordlet markup to surrounding markup.</p>
<? if ( $image = w('Image') ): ?>
	<div <?=wa($image) ?>>
		<img src="<?=$image->src ?>" alt="<?=$image->alt ?>">
	</div>
<? endif ?>

<p>List with multiple attributes.</p>
<? if ( $images = w('Images') ): ?>
	<ul <?=wa($images) ?>>
		<? foreach ( $images as $key => $image ): ?>
			<li><img src="<?=$image->src ?>" alt="<?=$image->alt ?>"></li>
		<? endforeach ?>
	</ul>
<? endif ?>

<p>Showing the same wordlet markup around individual items in list</p>
<? if ( $list = w('List') ): ?>
	<ul>
		<? foreach ( $list as $key => $value ): ?>
			<li><?=$value ?></li>
		<? endforeach ?>
	</ul>
<? endif ?>

<p>Showing wordlet markup only on parent markup of list</p>
<? if ( $list = w('List') ): ?>
	<ul <?=wa($list) ?>>
		<? foreach ( $list as $key => $value ): ?>
			<li><?=$value->single(false) ?></li>
		<? endforeach ?>
	</ul>
<? endif ?>

<?=w('NotMade') ?>

<? if ( $nmlist = w('NotMadeList') ): ?>
	<ul>
		<? foreach ( $nmlist as $key => $value ): ?>
			<li><?=$value ?></li>
		<? endforeach ?>
	</ul>
<? endif ?>

<? if ( $nmblock = w('NotMadeBlock') ): ?>
	<h3 <?=wa($nmblock) ?>>
		<?=$nmblock->single(false) ?>
	</h3>
<? endif ?>

<nav>
	<ul>
		<li><a href="?admin=1">admin view</a></li>
		<li><a href="?editor=1">editor view</a></li>
		<li><a href="?">regular view</a></li>
		<li><a href="?do=delete">delete all</a></li>
		<li><a href="?do=reset">reset</a></li>
	</ul>
</nav>

<script type="text/javascript" defer src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script defer>window.jQuery || document.write('<script defer src="jquery-1.7.2.min.js"><\/script>')</script>
<script type="text/javascript" defer src="wordlets.jquery.js"></script>