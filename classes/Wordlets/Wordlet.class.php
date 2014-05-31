<?php

namespace Wordlets;

class Wordlet implements \Iterator, \Countable {
	public $Values = array();
	public $Attrs = array();
	public $Page;
	public $Name;
	public $Configured = false;
	public $Parent;

	public $DefaultConfig = array(
		'type' => 'single',
		'html' => 'none',
		'order' => 0,
		'instanced' => 0,
		'show_markup' => 1,
	);

	public $DefaultParams = array(
		'id' => null,
		'attrs' => null,
		'values' => null,
		'show_markup' => null,
		'cardinality' => null,
		'instanced' => null,
	);

	// Countable
	public function count() {
		return count($this->Values);
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
		return key($this->Values);
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
	public function __construct($page, $name, array $params = array()) {
		$this->Page = $page;
		$this->Name = $name;

		$params += $this->DefaultParams;

		$this->Attrs = $params['attrs'];
		$values = $params['values'];

		if ( is_array($values) ) $this->Values = $values;
		$this->length = count($values);
		if ( $this->Attrs ) $this->Configured = true;

		return $params;
	}

	public function __get($name) {
		return $this->__call($name);
	}

	public function __call($name, $args = null) {
		$value = $this->getValue($name, $args);
		if ( !$value ) return null;

		return $value['value'];
	}

	public function getValue($name, $args = null) {
		$values = $this->GetCurrent();

		if ( !isset($values[$name]) ) {
			$values[$name] = null;
		}

		return $values[$name];
	}

	public function __toString() {
		$values = $this->GetCurrent();
		if ( is_array($values) ) {
			foreach ( $values as $key => $value ) {
				return $this->__get($key) . '';
			}
		}

		return $this->__get(null) . '';
	}

	public function GetCurrent() {
		if ( $this->Current ) {
			$var = $this->Current;
		} elseif ( $this->ValueId ) {
			if ( isset($this->InstanceValues[0]) ) {
				$var = $this->InstanceValues[0];
			} else {
				$var = null;
			}
		} elseif ( isset($this->Values[0]) ) {
			$var = $this->Values[0];
		} else {
			$var = null;
		}

		return $var;
	}
}
