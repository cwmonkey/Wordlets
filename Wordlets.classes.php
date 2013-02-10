<?php

class Wordlets {
	private static $_ones;
	private static $_manys;

	public static function getOne($attrs) {
		if ( isset(self::$_ones[$attrs['name']]) ) return self::$_ones[$attrs['name']];
		self::$_ones[$attrs['name']] = new WordletItem($attrs['configs'], $attrs['values'][0]);
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