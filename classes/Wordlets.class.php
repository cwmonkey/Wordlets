<?php

class WordletsBase {
	public static $Pages = array();
	public static $ShowMarkup = false;
	public static $CurrentPage;

	public static function SetObject($object) {
		if ( !isset(self::$Pages[$object->Page]) ) self::$Pages[$object->Page] = array();
		self::$Pages[$object->Page][$object->Name] = $object;
	}

	public static function GetOne($name, $echo = true) {
		$keys = array_keys(self::$Pages);
		for ( $i = count($keys) - 1; $i >= 0; $i-- ) {
			$key = $keys[$i];
			$page = self::$Pages[$key];
			if ( isset($page[$name]) ) {
				$page[$name]->ShowMarkup = self::$ShowMarkup;
				return $page[$name];
			}
		}

		// Make a blank wordlet
		$wordlet = new WordletsObject(self::$CurrentPage, $name, null, null, self::$ShowMarkup);

		if ( self::$ShowMarkup && !$wordlet->Configured && $echo ) echo '<span ' . $wordlet->HtmlAttrs() . '></span>';

		$page[$name] = $wordlet;

		return $page[$name];
	}
}

class WordletsObject implements Iterator, Countable {
	public $Values = array();
	public $Attrs = array();
	public $Page;
	public $Name;
	public $ShowMarkup;
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
	public function __construct($page, $name, $attrs = null, $values = null, $show_markup = false) {
		$this->Page = $page;
		$this->Name = $name;
		$this->Attrs = $attrs;
		$this->ShowMarkup = $show_markup;

		/*foreach ( $values as $value ) {
			$v = new WordletsValue($value, $config, $show_markup);
			$this->Values[] = $v;
		}*/
		$this->Values = $values;
		$this->length = count($values);
		if ( $attrs ) $this->Configured = true;
	}

	public function __get($name) {
		return $this->__call($name);
	}

	public function __call($name, $show_markup = null) {
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

		if ( $this->ShowMarkup && ($show_markup || $show_markup === null && $config['show_markup']) ) {
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
		/*if ( $this->ShowMarkup ) {
			return '<span ' . $this->Attrs() . '></span>';
		} else {
			return '';
		}*/
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

		switch ($config['html']) {
			case 'none':
				$value = strip_tags($value);
				break;
			case 'convert':
				$value = htmlspecialchars($value);
				break;
			case 'safe':
				$value = strip_tags($value, '<p><strong><b><i><em><div><span><br><br/><hr><hr/>');
				break;
			case 'all':
				break;
		}

		return $value;
	}

	public function HtmlAttrs($show_class = true) {
		if ( !$this->ShowMarkup ) return '';

		$attrs = 'data-wordlet-configured="' . ($this->Configured?'true':'false') . '" data-wordlet-name="' . $this->Name . '" data-wordlet-page="' . $this->Page . '"';
		if ( $show_class ) $attrs = ' class="wordlet wordlet_' . ($this->Configured?'':'un') . 'configured" ' . $attrs;
		return $attrs;
	}
}

class WordletsValue {
	public $ShowMarkup = true;
	public $value = '';
	public $Config;

	public function __construct($value, $config, $show_markup = true) {
		$this->ShowMarkup = $show_markup;
		$this->value = $value;
		$this->Config = $config;
	}

	public function Value() {
		if ( $this->value === null ) return '';
		$value = $this->value;

		switch ($this->Config['html']) {
			case 'none':
				$value = strip_tags($value);
				break;
			case 'convert':
				$value = htmlspecialchars($value);
				break;
			case 'safe':
				$value = strip_tags($value, '<p><strong><b><i><em><div><span><br><br/><hr><hr/>');
				break;
			case 'all':
				break;
		}

		return $value;
	}
}