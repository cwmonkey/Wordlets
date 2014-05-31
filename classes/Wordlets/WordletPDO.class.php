<?php

namespace Wordlets;

// Extending Wordlet class to add site-specific functionality
class WordletPDO extends Wordlet {
	public $Wordlets;
	public $AttrId;
	public $Id;
	public $Cardinality;
	public $ValueId;
	public $InstanceValues;

	public $PDODefaultParams = array(
		'id' => null,
		'instanced' => false,
		'cardinality' => 1,
		'attr_id' => null,
	);

	public function __construct($page, $name, array $params = array()) {
		$params += $this->PDODefaultParams;
		$params = parent::__construct($page, $name, $params);
		$this->Id = $params['id'];
		$this->Cardinality = $params['cardinality'];

		if ( @$params['attr_id'] ) {
			$this->AttrId = $params['attr_id'];
			$this->InstanceValues = $this->Values;
			$this->Values = null;
		}

		return $params;
	}

	public function __call($name, $args = null) {
		$value = parent::getValue($name, $args);

		if ( isset($this->Attrs[$name]) ) {
			$config = $this->Attrs[$name];
		} else {
			$config = $this->DefaultConfig;
		}

		if ( @$config['instanced'] ) {
			$value_id = ( $value ) ? $value['id'] : null;

			$wordlet = $this->Wordlets->getOne($this->Name . ':' . $config['name'], true, $config['id'], $value_id);
			return $wordlet;
		}

		$value = ( $value ) ? $value['value'] : $value;

		$value = $this->Value($value, $config);

		return $value;
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