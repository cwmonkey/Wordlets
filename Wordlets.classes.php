<?php

class Wordlets {
	private static $_ones;
	private static $_manys;

	public static function getOne($attrs) {
		if ( isset(self::$_ones[$attrs['name']]) ) return self::$_ones[$attrs['name']];
		$values = ( count($attrs['values']) ) ? $attrs['values'][0] : array();
		self::$_ones[$attrs['name']] = new WordletItem($attrs['name'], $attrs['configs'], $values);
		return self::$_ones[$attrs['name']];
	}

	public static function getMany($attrs) {
		if ( isset(self::$_manys[$attrs['name']]) ) return self::$_manys[$attrs['name']];
		$array = array();
		foreach ( $attrs['values'] as $vs ) {
			$array[] = new WordletItem($attrs['configs'], $vs);
		}

		self::$_manys[$attrs['name']] = new WordletItems($array);
		return self::$_manys[$attrs['name']];
	}
}

// Objects passed to the front end
class WordletItem {
	protected $_values = array();
	protected $_configs = array();
	protected $_configured = false;
	protected $_name;

	public function __construct($name, $configs, $values) {
		if ( count($configs) ) $this->_configured = true;
		$this->_name = $name;

		foreach ( $configs as $key => $config ) {
			$this->_values[$config['name']] = $values[$key];
			$this->_configs[$config['name']] = $config;
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

		if ( $preprocess ) return $this->preProcess($val, $name);

		return $val;
	}

	public function __call($name, $parameters) {
		$val = $this->get($name, false);

		array_unshift($parameters, $name);
		array_unshift($parameters, $val);

		return call_user_method_array('preProcess', $this, $parameters);
	}

	public function __toString() {
		foreach ( $this->_values as $key => $value ) {
			return $this->get($key);
		}

		return $this->get(null);
	}

	public function preProcess($value, $name) {
		return $value;
	}
}

class WordletItems implements Iterator {
    private $position = 0;
    private $array = array();  

    public function __construct($array) {
        $this->array = $array;
        $this->position = 0;
    }

    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->array[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->array[$this->position]);
    }

    function find($field, $value) {
    	foreach ( $this->array as $w ) {
    		if ( $w->get($field, false) == $value ) {
    			return $w;
    		}
    	}
    	return null;
    }
}