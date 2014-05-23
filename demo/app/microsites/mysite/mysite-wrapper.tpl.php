<div id="mysite_wrapper">
	<div id="mysite_header">
		<h1 id="mysite_title">
			<a href="/demo/?page=mysite-index"><?=w('MySite Title') ?></a>
		</h1>
		<? if ( $navs = w('navs') ): ?>
			<nav id="mysite_nav" <?=wa($navs) ?>>
				<? foreach ( $navs as $nav ): ?>
					<a href="<?=$nav->href ?>"><?=$nav ?></a>
				<? endforeach ?>
			</nav>
		<? endif ?>
	</div>

	<main id="mysite_content">
		<?=$content ?>
	</main>

	<div id="mysite_footer">MySite Footer</div>
</div>