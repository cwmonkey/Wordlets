<?php

namespace Wordlets;

class Wordlet implements \Iterator, \Countable {
	public $Values = array();
	public $Attrs = array();
	public $Page;
	public $Name;
	public $Id;
	public $ShowMarkup;
	public $Cardinality;
	public $Configured = false;

	public $DefaultConfig = array(
		'type' => 'single',
		'html' => 'none',
		'order' => 0,
		'show_markup' => 1,
	);

	// Countable
	public function count() {
		$var = count($this->Values);
		return $var;
	}

	// Iterator
	public $Current = null;
	public function rewind() {
		reset($this->Values);
	}

	public function current() {
		$this->Current = current($this->Values);
		return $this;
	}

	public function key() {
		$var = key($this->Values);
		return $var;
	}

	public function next() {
		$this->Current = next($this->Values);
		return $this;
	}

	public function valid() {
		$key = key($this->Values);
		$var = ($key !== NULL && $key !== FALSE);
		return $var;
	}

	// The rest
	public function __construct($page, $name, $id = null, $attrs = null, $values = null, $show_markup = false, $cardinality = 1) {
		$this->Page = $page;
		$this->Name = $name;
		$this->Id = $id;
		$this->Attrs = $attrs;
		$this->ShowMarkup = $show_markup;
		$this->Cardinality = $cardinality;

		/*foreach ( $values as $value ) {
			$v = new WordletsValue($value, $config, $show_markup);
			$this->Values[] = $v;
		}*/
		if ( is_array($values) ) $this->Values = $values;
		$this->length = count($values);
		if ( $attrs ) $this->Configured = true;
	}

	public function __get($name) {
		return $this->__call($name);
	}

	public function __call($name, $args = null) {
		$show_markup = @$args[0];
		$values = $this->GetCurrent();

		if ( !isset($values[$name]) ) {
			//$this->Values[$name] = new WordletsValue(null, $this->DefaultConfig);
			$values[$name] = null;
		}

		if ( isset($this->Attrs[$name]) ) {
			$config = $this->Attrs[$name];
		} else {
			$config = $this->DefaultConfig;
		}

		$value = $values[$name];

		if ( $this->ShowMarkup && ($show_markup || ($show_markup === null && @$config['show_markup'])) ) {
			return '<span ' . $this->HtmlAttrs() . '>'
			. $this->Value($value, $config)
			. '</span>';
		} else {
			return $this->Value($value, $config);
		}
	}

	public function __toString() {
		$values = $this->GetCurrent();

		if ( is_array($values) ) {
			foreach ( $values as $key => $value ) {
				return $this->__get($key) . '';
			}
		}

		return '';
	}

	public function GetCurrent() {
		if ( $this->Current ) {
			$var = $this->Current;
		} elseif ( isset($this->Values[0]) ) {
			$var = $this->Values[0];
		} else {
			$var = null;
		}

		return $var;
	}

	public function Value($value, $config) {
		if ( is_array($value) ) {
			foreach ( $value as $v ) {
				$value = $v;
				break;
			}
		}

		if ( $value === null ) return '';

		switch (@$config['html']) {
			case 'convert':
				$value = htmlspecialchars($value);
				break;
			case 'safe':
				$value = strip_tags($value, '<p><strong><b><i><em><div><span><br><br/><hr><hr/>');
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

	public function HtmlAttrs() {
		if ( !$this->ShowMarkup ) return '';

		$attrs = 'data-wordlet="wordlet" data-wordlet-configured="' . ($this->Configured?'true':'false') . '" data-wordlet-name="' . $this->Name . '" data-wordlet-page="' . $this->Page . '"';
		return $attrs;
	}
}
