<?php

$admin = @$_GET['admin'];
$editor = @$_GET['editor'];

if ( $admin ) {
	$wordlets->showMarkup = true;
	$wordlets->ShowEdit = true;
	$wordlets->ShowConfigure = true;
} elseif ( $editor ) {
	$wordlets->showMarkup = true;
	$wordlets->ShowEdit = true;
}

include($app . '/config.php');

$page = @$_GET['page'];
foreach ( $routes as $name => $route ) {
	if ( $name == $page ) {
		$page = $route;
	}
}

if ( !$page ) {
	foreach ( $routes as $page ) { break; }
}

foreach ( $page['load'] as $load ) {
	$wordlets->loadObjects($load);
}

include($app . '/templates/' . $page['template'] . '.tpl.php');
