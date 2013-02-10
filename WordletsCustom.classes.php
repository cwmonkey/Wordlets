<?php

class WordletsCustom extends Wordlets {
	private static $_ones;
	private static $_manys;

	public static function getOne($attrs) {
		if ( isset(self::$_ones[$attrs['name']]) ) return self::$_ones[$attrs['name']];
		self::$_ones[$attrs['name']] = new WordletItemCustom($attrs['configs'], $attrs['values'][0]);
		return self::$_ones[$attrs['name']];
	}

	public static function getMany($attrs) {
		if ( isset(self::$_manys[$attrs['name']]) ) return self::$_manys[$attrs['name']];
		$array = array();
		foreach ( $attrs['values'] as $vs ) {
			$array[] = new WordletItemCustom($attrs['configs'], $vs);
		}

		self::$_manys[$attrs['name']] = new WordletItems($array);
		return self::$_manys[$attrs['name']];
	}
}

// Objects passed to the front end
class WordletItemCustom extends WordletItem {
	private $_values;
	private $_configs;

	public function __construct($configs, $values) {
		$this->_configs = $configs;
		foreach ( $configs as $key => $config ) {
			$this->_values[$config['name']] = $values[$key];
		}
	}

	public function __get($name) {
		return $this->get($name);
	}

	public function get($name, $preprocess = true) {
		$val = '';
		if ( isset($this->_values[$name]) ) {
			$val = $this->_values[$name];
		}

		if ( $preprocess ) return $this->preProcess($val);

		return $val;
	}

	public function __call($name, $parameters) {
		$val = $this->get($name, false);

		return call_user_method_array('preProcess', $this, $parameters);
	}

	public function __toString() {
		$val = '';
		foreach ( $this->_values as $value ) {
			$val = $value . '';
			break;
		}

		return $this->preProcess($val);
	}

	public function preProcess($value) {
		return $value;
	}
}
