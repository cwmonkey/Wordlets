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
	public $Instanced = false;
	public $ValueId;
	public $InstanceValues;
	public $Parent;

	public $DefaultConfig = array(
		'type' => 'single',
		'html' => 'none',
		'order' => 0,
		'instanced' => 0,
		'show_markup' => 1,
	);

	// Countable
	public function count() {
		if ( $this->ValueId ) {
			$values =& $this->InstanceValues;
		} else {
			$values =& $this->Values;
		}
		$var = count($values);
		return $var;
	}

	// Iterator
	public $Current = null;
	public function rewind() {
		if ( $this->ValueId ) {
			$values =& $this->InstanceValues;
		} else {
			$values =& $this->Values;
		}
		reset($values);
	}

	public function current() {
		if ( $this->ValueId ) {
			$values =& $this->InstanceValues;
		} else {
			$values =& $this->Values;
		}
		$this->Current = current($values);
		return $this;
	}

	public function key() {
		if ( $this->ValueId ) {
			$values =& $this->InstanceValues;
		} else {
			$values =& $this->Values;
		}
		$var = key($values);
		return $var;
	}

	public function next() {
		if ( $this->ValueId ) {
			$values =& $this->InstanceValues;
		} else {
			$values =& $this->Values;
		}
		$this->Current = next($values);
		return $this;
	}

	public function valid() {
		if ( $this->ValueId ) {
			$values =& $this->InstanceValues;
		} else {
			$values =& $this->Values;
		}
		$key = key($values);
		$var = ($key !== NULL && $key !== FALSE);
		return $var;
	}

	// The rest
	public function __construct($page, $name, $id = null, $attrs = null, $values = null, $show_markup = false, $cardinality = 1, $instanced = false) {
		$this->Page = $page;
		$this->Name = $name;
		$this->Id = $id;
		$this->Attrs = $attrs;
		$this->ShowMarkup = $show_markup;
		$this->Cardinality = $cardinality;
		$this->Instanced = $instanced;

		if ( is_array($values) ) $this->Values = $values;
		$this->length = count($values);
		if ( $attrs ) $this->Configured = true;
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
