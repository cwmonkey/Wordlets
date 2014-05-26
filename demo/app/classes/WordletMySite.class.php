<?php

// Extending Wordlet class to add site-specific functionality
class WordletMySite extends \Wordlets\Wordlet {
	public $ShowEdit = false;
	public $ShowConfigure = false;

	// Extending to add form urls to output attributes
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

	// Extending to add token replacements for wordlet page URL's
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