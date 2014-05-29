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

	public function getOne($name, $echo = true) {
		// return cached wordlet
		$keys = array_keys($this->pages);
		for ( $i = count($keys) - 1; $i >= 0; $i-- ) {
			$key = $keys[$i];
			$page = $this->pages[$key];
			if ( isset($page[$name]) ) {
				$page[$name]->ShowMarkup = $this->showMarkup;
				// Turn markup off if specified
				if ( !$echo ) $page[$name]->ShowMarkup = false;
				return $page[$name];
			}
		}

		// Make a blank wordlet to return
		$show_markup = $this->showMarkup;
		if ( !$echo ) $show_markup = false;
		$wordlet = $this->getWordlet($this->currentPage, $name, null, null, null, $show_markup, 1, 0);

		$page[$name] = $wordlet;

		return $page[$name];
	}

	public function getWordlet($page, $name, $id = null, $attrs = null, $values = null, $show_markup = false, $instanced = false) {
		return new Wordlet($page, $name, $id, $attrs, $values, $show_markup, $instanced);
	}
}
