<?php

$routes = array(
	'_site' => array(
		'title' => 'Site Base',
	),

	'index' => array(
		'title' => 'Site Index',
		'url' => '/demo',
		'templates' => array('index'),
		'load' => array('_site', 'index'),
	),

	// My Site
	'mysite' => array(
		'title' => 'MySite Base',
	),
	'mysite-index' => array(
		'title' => 'MySite Index',
		'url' => '/demo/?page=mysite-index',
		'templates' => array('mysite/mysite-index', 'mysite/mysite-wrapper'),
		'stylesheets' => array('app/microsites/mysite/mysite.css'),
		'javascripts' => array('app/microsites/mysite/mysite.js'),
		'load' => array('_site', 'mysite', 'mysite-index'),
	),
);