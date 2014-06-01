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
		$wordlet = parent::getOne($name, $echo, $attr_id, $value_id);
		if ( !$echo ) {
			$wordlet->ShowMarkup = false;
		} else {
			$wordlet->ShowMarkup = $wordlet->_ShowMarkup;
		}

		if ( $this->showMarkup && !$wordlet->Configured && $echo ) {
			echo '<span ' . $wordlet->HtmlAttrs() . '></span>';
			return null;
		}

		return $wordlet;
	}
}