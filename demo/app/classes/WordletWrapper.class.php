<?php

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