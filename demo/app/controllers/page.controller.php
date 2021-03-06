<?php

if ( $user == 'admin' ) {
	$wordlets->showMarkup = true;
	$wordlets->ShowEdit = true;
	$wordlets->ShowConfigure = true;
} elseif ( $user == 'editor' ) {
	$wordlets->showMarkup = true;
	$wordlets->ShowEdit = true;
}

$page = @$_GET['page'];
foreach ( $routes as $name => $route ) {
	if ( $name == $page ) {
		$page = $route;
	}
}

if ( !$page ) {
	$page = $routes['index'];
}

$styles = array(
	'css/reset.css',
	'css/cms-normalize.css',
	'css/site.css',
	'vendor/cwmModal/cwmModal.css'
);

$scripts = array(
	'vendor/jquery.load.js',
	'vendor/cwmModal/cwmModal.jquery.js',
	'js/script.js'
);

if ( $user == 'admin' || $user == 'editor' ) {
	$styles[] = 'css/wordlets.css';
	$scripts[] = 'js/wordlets.jquery.js';
}

if ( !empty($page['stylesheets']) ) $styles = array_merge($styles, $page['stylesheets']);
if ( !empty($page['javascripts']) ) $scripts = array_merge($scripts, $page['javascripts']);

foreach ( $page['load'] as $load ) {
	$wordlets->loadObjects($load);
}

foreach ( $page['templates'] as $tpl ) {
	ob_start();
	include($app . '/' . $tpl . '.tpl.php');
	$content = ob_get_contents();
	ob_end_clean();
}

include($app . '/templates/wrapper.tpl.php');
