<?php

namespace Wordlets;

// Extending Wordlet class to add site-specific functionality
class WordletPDO extends Wordlet {
	public $Wordlets;
	public $AttrId;
	public $Id;
	public $Instanced;
	public $Cardinality;

	public $PDODefaultParams = array(
		'id' => null,
		'instanced' => false,
		'cardinality' => 1,
	);

	public function __construct($page, $name, array $params = array()) {
		$params += $this->PDODefaultParams;
		$params = parent::__construct($page, $name, $params);
		$this->Id = $params['id'];
		$this->Instanced = $params['instanced'];
		$this->Cardinality = $params['cardinality'];

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
}