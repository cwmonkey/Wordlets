<?php

include('../classes/Wordlets.class.php');
include('WordletsMySql.class.php');
include('config.local.php');

WordletsMySql::OpenDb($dbaddr, $dbuser, $dbpass, $dbname, 'wordlet_');
$admin = @$_GET['admin'];
if ( $admin ) WordletsMySql::$ShowMarkup = true;
WordletsMySql::LoadObjects('_site', 'index');

class Site {
	public static $WordletClass = 'WordletsMySql';
}
// Making shorthand functions for the template to use
function w() {
	$func_get_args = func_get_args();
	try {
		return call_user_func_array(Site::$WordletClass . '::GetOne', $func_get_args);
	} catch (Exception $e) {
		return '';
	}
}

$page = @$_GET['page'];
if ( $page == 'form' ) {
	$image = new WordletsObject(
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
	WordletsMySql::SaveObject($image);

	$title = new WordletsObject(
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
	WordletsMySql::SaveObject($title);

	$subtitle = new WordletsObject(
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
	WordletsMySql::SaveObject($subtitle);

	$list = new WordletsObject(
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
	WordletsMySql::SaveObject($list);

	//include('form.php');
	//header('Location: .');
} else {
	include('index.tpl.php');
}

