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
		$keys = array_keys($this->pages);
		for ( $i = count($keys) - 1; $i >= 0; $i-- ) {
			$key = $keys[$i];
			$page = $this->pages[$key];
			if ( isset($page[$name]) ) {
				$page[$name]->ShowMarkup = $this->showMarkup;
				return $page[$name];
			}
		}

		// Make a blank wordlet
		$wordlet = $this->getWordlet($this->currentPage, $name, null, null, null, $this->showMarkup, 1);

		if ( $this->showMarkup && !$wordlet->Configured && $echo ) {
			echo '<span ' . $wordlet->HtmlAttrs() . '></span>';
			return null;
		}

		$page[$name] = $wordlet;

		return $page[$name];
	}

	public function getWordlet($page, $name, $id = null, $attrs = null, $values = null, $show_markup = false) {
		return new Wordlet($page, $name, $id, $attrs, $values, $show_markup);
	}
}
