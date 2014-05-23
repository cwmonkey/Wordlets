<?php

$query = $pdo->prepare("TRUNCATE {$tablePrepend}val");
$result = $query->execute();

$query = $pdo->prepare("TRUNCATE {$tablePrepend}attr");
$result = $query->execute();

$query = $pdo->prepare("TRUNCATE {$tablePrepend}object");
$result = $query->execute();

$query = $pdo->prepare("TRUNCATE {$tablePrepend}page");
$result = $query->execute();

$site_title = new \Wordlets\Wordlet(
	'_site',
	'Site Title',
	null,
	array(
		'single' => array(
			'type' => 'single',
			'html' => 'none',
			'format' => 'none',
			'order' => 0,
			'show_markup' => 1,
		),
	),
	$values = array(
		array(
			'single' => 'This is the Site Title'
		),
	),
	false,
	1
);
$wordlets->saveObject($site_title, 1);

$title = new \Wordlets\Wordlet(
	'index',
	'Title',
	null,
	array(
		'single' => array(
			'type' => 'single',
			'html' => 'none',
			'format' => 'none',
			'order' => 0,
			'show_markup' => 1,
		),
	),
	$values = array(
		array(
			'single' => 'This is the Page Title'
		),
	),
	false,
	1
);
$wordlets->saveObject($title, 1);

$title = new \Wordlets\Wordlet(
	'mysite',
	'MySite Title',
	null,
	array(
		'single' => array(
			'type' => 'single',
			'html' => 'none',
			'format' => 'none',
			'order' => 0,
			'show_markup' => 1,
		),
	),
	$values = array(
		array(
			'single' => 'This is the MySite Title'
		),
	),
	false,
	1
);
$wordlets->saveObject($title, 1);

$title = new \Wordlets\Wordlet(
	'mysite-index',
	'MySite Index Title',
	null,
	array(
		'single' => array(
			'type' => 'single',
			'html' => 'none',
			'format' => 'none',
			'order' => 0,
			'show_markup' => 1,
		),
	),
	$values = array(
		array(
			'single' => 'This is the MySite Index Page Title'
		),
	),
	false,
	1
);
$wordlets->saveObject($title, 1);

$navs = new \Wordlets\Wordlet(
	'mysite',
	'navs',
	null,
	array(
		'single' => array(
			'type' => 'single',
			'html' => 'none',
			'format' => 'none',
			'order' => 0,
			'show_markup' => 1,
		),
		'href' => array(
			'type' => 'single',
			'html' => 'none',
			'format' => 'none',
			'order' => 1,
			'show_markup' => 0,
		),
	),
	$values = array(
		array(
			'single' => 'Home',
			'href' => '{mysite-index_url}'
		),
		array(
			'single' => 'Page 1',
			'href' => '{mysite-page1_url}'
		),
		array(
			'single' => 'Page 2',
			'href' => '{mysite-page2_url}'
		),
		array(
			'single' => 'Page 3',
			'href' => '{mysite-page3_url}'
		),
	),
	false,
	0
);
$wordlets->saveObject($navs, 0);

$image = new \Wordlets\Wordlet(
	'index',
	'Image',
	null,
	array(
		'src' => array(
			'type' => 'single',
			'html' => 'none',
			'format' => 'none',
			'order' => 0,
			'show_markup' => 0,
		),
		'alt' => array(
			'type' => 'single',
			'html' => 'none',
			'format' => 'none',
			'order' => 1,
			'show_markup' => 0,
		),
	),
	$values = array(
		array(
			'src' => 'http://i.imgur.com/9fOG9nlb.jpg',
			'alt' => 'This is some Alt'
		),
	),
	false,
	1
);
$wordlets->saveObject($image, 1);

$images = new \Wordlets\Wordlet(
	'index',
	'Images',
	null,
	array(
		'src' => array(
			'type' => 'single',
			'html' => 'none',
			'format' => 'none',
			'order' => 0,
			'show_markup' => 0,
		),
		'alt' => array(
			'type' => 'single',
			'html' => 'none',
			'format' => 'none',
			'order' => 1,
			'show_markup' => 0,
		),
	),
	$values = array(
		array(
			'src' => '//placehold.io/100/100.png?text=Sucka',
			'alt' => 'This is some Alt1'
		),
		array(
			'src' => 'http://placehold.it/100x100',
			'alt' => 'This is some Alt2'
		),
		array(
			'src' => 'http://dummyimage.com/100x100/ff0000/ffffff',
			'alt' => 'This is some Alt3'
		),
		array(
			'src' => 'http://fpoimg.com/100x100?text=Sucka',
			'alt' => 'This is some Alt4'
		),
	),
	false,
	1
);
$wordlets->saveObject($images, 0);

$subtitle = new \Wordlets\Wordlet(
	'index',
	'SubTitle',
	null,
	array(
		'single' => array(
			'type' => 'single',
			'html' => 'none',
			'format' => 'none',
			'order' => 0,
			'show_markup' => 1,
		),
	),
	$values = array(
		array(
			'single' => 'This is a Sub Title'
		),
	),
	false,
	1
);
$wordlets->saveObject($subtitle, 1);

$list = new \Wordlets\Wordlet(
	'index',
	'List',
	null,
	array(
		'single' => array(
			'type' => 'single',
			'html' => 'none',
			'format' => 'none',
			'order' => 0,
			'show_markup' => 1,
		),
	),
	$values = array(
		array(
			'single' => 'This is the first list item'
		),
		array(
			'single' => 'This is the second list item'
		),
		array(
			'single' => 'This is the third list item'
		),
		array(
			'single' => 'This is the fourth list item'
		),
	),
	false,
	0
);
$wordlets->saveObject($list, 0);
