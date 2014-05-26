<?php

// Extending Wordlet class to add site-specific functionality
class WordletMySite extends \Wordlets\Wordlet {
	public $ShowEdit = false;
	public $ShowConfigure = false;

	// Helper attributes for ajax
	public function HtmlAttrs() {
		if ( !$this->ShowMarkup ) return '';

		$attrs = 'data-wordlet="wordlet" data-wordlet-configured="' . ($this->Configured?'true':'false') . '" data-wordlet-name="' . $this->Name . '" data-wordlet-page="' . $this->Page . '"';

		if ( $this->Configured ) {
			if ( $this->ShowEdit ) $attrs .= ' data-wordlet-edit="?do=form&action=edit&id=' . $this->Id . '"';
			if ( $this->ShowConfigure ) $attrs .= ' data-wordlet-configure="?do=form&action=configure&id=' . $this->Id . '"';
		} else {
			if ( $this->ShowConfigure ) $attrs .= ' data-wordlet-configure="?do=form&action=configure&page=' . $this->Page . '&name=' . $this->Name . '"';
		}

		return $attrs;
	}

	public function Value($value, $config) {
		if ( is_array($value) ) {
			foreach ( $value as $v ) {
				$value = $v;
				break;
			}
		}

		if ( $value === null ) return '';

		$value = $this->ValueHtml($value, @$config['html']);

		$value = $this->ValueFormat($value, @$config['format']);

		$value = $this->ValuePageTokens($value);

		return $value;
	}

	public function ValuePageTokens($value) {
		foreach ( Site::$routes as $name => $route ) {
			if ( isset($route['url']) ) {
				$value = str_replace('{' . $name . '_url}', $route['url'], $value);
			}
		}
		return $value;
	}

	public function ValueHtml($value, $config) {
		switch ($config) {
			case 'convert':
				$value = htmlspecialchars($value);
				break;
			case 'safe':
				$value = strip_tags($value, '<a><p><strong><b><i><em><div><span><br><br/><hr><hr/>');
				break;
			case 'all':
				break;
			case 'none':
			default:
				$value = strip_tags($value);
				break;
		}

		return $value;
	}

	public function ValueFormat($value, $config) {
		switch ($config) {
			case 'simple':
				$value = preg_replace("/[\r]+/", '', $value);
				$value = '<p>' . preg_replace("/[\n]{2,}/", '</p><p>', $value) . '</p>';
				$value = preg_replace("/[\n]/", '<br>', $value);
				break;
			case 'none':
			default:
				break;
		}

		return $value;
	}

	public function __call($name, $args = null) {
		$value = parent::__call($name, $args);

		$show_markup = @$args[0];

		if ( isset($this->Attrs[$name]) ) {
			$config = $this->Attrs[$name];
		} else {
			$config = $this->DefaultConfig;
		}

		$value = $this->Value($value, $config);

		if ( $this->ShowMarkup && ($show_markup || ($show_markup === null && @$config['show_markup'])) ) {
			return '<span ' . $this->HtmlAttrs() . '>'
			. $value
			. '</span>';
		} else {
			return $value;
		}

	}
}