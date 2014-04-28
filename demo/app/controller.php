<?php

include('config.local.php');

// Static class for handling site functionality
class Site {
	public static $Wordlets;

	public static function loadClass($name) {
		if ( is_readable('../classes/' . $name . '.class.php') ) {
			require_once('../classes/' . $name . '.class.php');
		}
	}
}

spl_autoload_register('Site::loadClass');

// A front end wrapper for pretty wordlet output
class WordletWrapper {
	public $wordlets;
	public function __construct($wordlets) {
		$this->wordlets = $wordlets;
	}

	public function __get($name) {
		return $this->__call($name);
	}

	public function __call($name, $args = array()) {
		array_unshift($args, $name);
		return call_user_func_array(array($this->wordlets, 'getOne'), $args);
	}
}

// Classes to enhance default functionality of Wordlets
class WordletsMySite extends \Wordlets\WordletsMySql {
	public function getWordlet($page, $name, $attrs = null, $values = null, $show_markup = false) {
		return new WordletMySite($page, $name, $attrs, $values, $show_markup);
	}
}

// Class to add a href to a form to edit wordlet
class WordletMySite extends \Wordlets\Wordlet {
	public function HtmlAttrs() {
		if ( !$this->ShowMarkup ) return '';

		$attrs = parent::HtmlAttrs();
		$attrs .= ' data-wordlet-href="#"';
		return $attrs;
	}
}

Site::$Wordlets = $wordlets = new WordletsMySite('mysql:host=' . $dbaddr . ';dbname=' . $dbname, $dbuser, $dbpass, 'wordlet_');

// Making object for templates to use, more oo style
$w = new WordletWrapper($wordlets);

// Making shorthand functions for the template to use, Drupal style
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
	$image = new \Wordlets\Wordlet(
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

	$title = new \Wordlets\Wordlet(
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

	$subtitle = new \Wordlets\Wordlet(
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

	$list = new \Wordlets\Wordlet(
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

