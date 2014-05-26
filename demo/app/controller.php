<?php

include('config.php');
include('config.local.php');

$app = __DIR__;

// Static class for handling site functionality
class Site {
	public static $Wordlets;
	public static $routes = array();

	public static function loadClass($name) {
		$name = str_replace('\\', '/', $name);
		if ( is_readable('../classes/' . $name . '.class.php') ) {
			require_once('../classes/' . $name . '.class.php');
		} else if ( is_readable('app/classes/' . $name . '.class.php') ) {
			require_once('app/classes/' . $name . '.class.php');
		}
	}
}

spl_autoload_register('Site::loadClass');

// Cheese a global variable for wordlet value replacements
Site::$routes = $routes;

Site::$Wordlets = $wordlets = new WordletsMySite('mysql:host=' . $dbaddr . ';dbname=' . $dbname, $dbuser, $dbpass, 'wordlet_');

// Making object for templates to use, more oo style
$w = new WordletWrapper($wordlets);

// Making shorthand functions for the template to use, Drupal style
function w() {
	$func_get_args = func_get_args();
	try {
		$obj = call_user_func_array(array(Site::$Wordlets, 'getOne'), $func_get_args);
		return $obj;
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
		if ( !$obj ) return '';
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
		setcookie('user', 'admin');
	} elseif ( $user == 'editor' ) {
		setcookie('user', 'editor');
	} else {
		setcookie('user', '');
	}
} elseif ( isset($_COOKIE['user']) && ($user = $_COOKIE['user']) ) {

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

