<?php

// Extending Wordlets to add Edit/Cinfigure flags
class WordletsMySite extends \Wordlets\WordletsPDO {
	public $ShowEdit = false;
	public $ShowConfigure = false;

	public function getWordlet($page, $name, array $params = array()) {
		$wordlet = new WordletMySite($page, $name, $params);
		$wordlet->ShowEdit = $this->ShowEdit;
		$wordlet->ShowConfigure = $this->ShowConfigure;
		$wordlet->Wordlets = $this;
		return $wordlet;
	}

	public function getOne($name, $echo = true, $attr_id = null, $value_id = null) {
		$wordlet = parent::getOne($name, $echo);
		$wordlet->AttrId = $attr_id;
		$wordlet->ValueId = $value_id;
		if ( $value_id ) {
			$wordlet->InstanceValues = ( isset($wordlet->Values[$value_id]) ) ? $wordlet->Values[$value_id] : array();
		} else {
			$wordlet->InstanceValues = null;
		}

		if ( $this->showMarkup && !$wordlet->Configured && $echo ) {
			echo '<span ' . $wordlet->HtmlAttrs() . '></span>';
			return null;
		}

		return $wordlet;
	}
}