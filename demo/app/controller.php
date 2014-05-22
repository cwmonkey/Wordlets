<?php

include('config.local.php');

$app = __DIR__;

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

$tablePrepend = 'wordlet_';
$pdo = new \PDO('mysql:host=' . $dbaddr . ';dbname=' . $dbname, $dbuser, $dbpass);

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

