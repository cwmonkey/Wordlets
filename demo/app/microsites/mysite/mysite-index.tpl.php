<p class="source">
	<a href="https://github.com/cwmonkey/Wordlets/blob/master/demo/app/microsites/mysite/mysite-index.tpl.php">mysite-index.tpl.php on github</a>
</p>

<h1 class="page_title" id="mysite_index_title"><?=w('MySite Index Title') ?></h1>

<div class="cms">
	<?=w('Content') ?>
</div>

<div id="schools">
	<? if ( $schools = w('Schools') ): ?>
		<? foreach ( $schools as $school ): ?>
			<div class="school">
				<h2 class="title"><?=$school->Title ?></h2>
				<? if ( $images = $school->Image ): ?>
					<div class="images" <?=wa($images) ?>>
						<? foreach ( $images as $image ): ?>
							<div class="image">
								<img src="<?=$image->src ?>" width="<?=$image->width(false) ?>" height="<?=$image->height(false) ?>">
							</div>
						<? endforeach ?>
					</div>
				<? endif ?>
				<div class="cms">
					<?=$school->Description ?>
				</div>
			</div>
		<? endforeach ?>
	<? endif ?>
</div>