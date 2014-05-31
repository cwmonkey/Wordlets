<?php

namespace Wordlets;

class WordletsBase {
	public $showMarkup = false;
	public $currentPage;

	public $pages = array();

	public function setObject($object) {
		if ( !isset($this->pages[$object->Page]) ) $this->pages[$object->Page] = array();
		$this->pages[$object->Page][$object->Name] = $object;
	}

	public function getOne($name) {
		// return cached wordlet
		$keys = array_keys($this->pages);
		for ( $i = count($keys) - 1; $i >= 0; $i-- ) {
			$key = $keys[$i];
			$page = $this->pages[$key];
			if ( isset($page[$name]) ) {
				return $page[$name];
			}
		}

		// Make a blank wordlet to return
		$wordlet = $this->getWordlet($this->currentPage, $name);

		$page[$name] = $wordlet;

		return $page[$name];
	}

	public function getWordlet($page, $name, array $params = array()) {
		return new Wordlet($page, $name, $params);
	}
}
