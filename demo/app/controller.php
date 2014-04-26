<?php

include('../classes/Wordlets/Wordlets.class.php');
include('../classes/Wordlets/WordletsMySql.class.php');
include('config.local.php');

class Site {
	public static $WordletClass = '\Wordlets\WordletsMySql';
	public static $Wordlets;
}

Site::$Wordlets = $wordlets = new \Wordlets\WordletsMySql('mysql:host=' . $dbaddr . ';dbname=' . $dbname, $dbuser, $dbpass, 'wordlet_');

// Making shorthand functions for the template to use
function w() {
	$func_get_args = func_get_args();
	try {
		return call_user_func_array(array(Site::$Wordlets, 'getOne'), $func_get_args);
	} catch (Exception $e) {
		return '';
	}
}

function wa($wordlet) {
	try {
		$obj = ( is_object($wordlet) ) ? $wordlet : w($wordlet, false);
		if ( Site::$Wordlets->showMarkup ) return $obj->HtmlAttrs();
		return '';
	} catch (Exception $e) {
		return '';
	}
}

$page = @$_GET['page'];
if ( $page == 'form' ) {
	$image = new \Wordlets\Wobject(
		'index',
		'Image',
		array(
			'src' => array(
				'type' => 'single',
				'html' => 'none',
				'order' => 0,
				'show_markup' => 0,
			),
			'alt' => array(
				'type' => 'single',
				'html' => 'none',
				'order' => 1,
				'show_markup' => 0,
			),
		),
		$values = array(
			array(
				'src' => 'http://i.imgur.com/9fOG9nlb.jpg',
				'alt' => 'This is some Alt'
			),
		)
	);
	$wordlets->saveObject($image);

	$title = new \Wordlets\Wobject(
		'index',
		'Title',
		array(
			'single' => array(
				'type' => 'single',
				'html' => 'none',
				'order' => 0,
				'show_markup' => 1,
			),
		),
		$values = array(
			array(
				'single' => 'This is a Title'
			),
		)
	);
	$wordlets->saveObject($title);

	$subtitle = new \Wordlets\Wobject(
		'index',
		'SubTitle',
		array(
			'single' => array(
				'type' => 'single',
				'html' => 'none',
				'order' => 0,
				'show_markup' => 1,
			),
		),
		$values = array(
			array(
				'single' => 'This is a Sub Title'
			),
		)
	);
	$wordlets->saveObject($subtitle);

	$list = new \Wordlets\Wobject(
		'index',
		'List',
		array(
			'single' => array(
				'type' => 'single',
				'html' => 'none',
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
		)
	);
	$wordlets->saveObject($list);

	//include('form.php');
	//header('Location: .');
} else {
	$admin = @$_GET['admin'];
	if ( $admin ) $wordlets->showMarkup = true;
	$wordlets->loadObjects('_site', 'index');

	include('index.tpl.php');
}

