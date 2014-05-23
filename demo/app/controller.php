<?php

session_start();

include('config.php');
include('config.local.php');

$app = __DIR__;

// Static class for handling site functionality
class Site {
	public static $Wordlets;
	public static $routes = array();

	public static function loadClass($name) {
		if ( is_readable('../classes/' . $name . '.class.php') ) {
			require_once('../classes/' . $name . '.class.php');
		}
	}
}

spl_autoload_register('Site::loadClass');

// Cheese a global variable for wordlet value replacements
Site::$routes = $routes;

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
	public $ShowEdit = false;
	public $ShowConfigure = false;

	public function getWordlet($page, $name, $id = null, $attrs = null, $values = null, $show_markup = false, $cardinality = 1) {
		$wordlet = new WordletMySite($page, $name, $id, $attrs, $values, $show_markup, $cardinality);
		$wordlet->ShowEdit = $this->ShowEdit;
		$wordlet->ShowConfigure = $this->ShowConfigure;
		return $wordlet;
	}
}

// Class to add a href to a form to edit wordlet
class WordletMySite extends \Wordlets\Wordlet {
	public $ShowEdit = false;
	public $ShowConfigure = false;

	public function HtmlAttrs() {
		if ( !$this->ShowMarkup ) return '';

		$attrs = parent::HtmlAttrs();
		if ( $this->Configured ) {
			if ( $this->ShowEdit ) $attrs .= ' data-wordlet-edit="?do=form&action=edit&id=' . $this->Id . '"';
			if ( $this->ShowConfigure ) $attrs .= ' data-wordlet-configure="?do=form&action=configure&id=' . $this->Id . '"';
		} else {
			if ( $this->ShowConfigure ) $attrs .= ' data-wordlet-configure="?do=form&action=configure&page=' . $this->Page . '&name=' . $this->Name . '"';
		}

		return $attrs;
	}


	public function Value($value, $config) {
		$value = parent::Value($value, $config);
		foreach ( Site::$routes as $name => $route ) {
			if ( isset($route['url']) ) {
				$value = str_replace('{' . $name . '_url}', $route['url'], $value);
			}
		}
		return $value;
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

function wn($name) {
	try {
		return Site::$Wordlets->getOne($name, false);
	} catch (Exception $e) {
		return '';
	}
}

function wa($wordlet) {
	try {
		$obj = ( is_object($wordlet) ) ? $wordlet : w($wordlet);
		if ( Site::$Wordlets->showMarkup ) return $obj->HtmlAttrs();
		return '';
	} catch (Exception $e) {
		return '';
	}
}

$tablePrepend = 'wordlet_';
$pdo = new \PDO('mysql:host=' . $dbaddr . ';dbname=' . $dbname, $dbuser, $dbpass);

$user = '';
if ( isset($_GET['user']) && ($user = $_GET['user']) ) {
	if ( $user == 'admin' ) {
		$_SESSION['user'] = 'admin';
	} elseif ( $user == 'editor' ) {
		$_SESSION['user'] = 'editor';
	} else {
		$_SESSION['user'] = '';
	}
} elseif ( isset($_SESSION['user']) && ($user = $_SESSION['user']) ) {

}

$page = @$_GET['do'];
if ( $page == 'delete' ) {
	include('controllers/delete.controller.php');
} elseif ( $page == 'reset' ) {
	include('controllers/reset.controller.php');
} elseif ( $page == 'form' ) {
	include('controllers/form.controller.php');
} else {
	include('controllers/page.controller.php');
}

