<?php

// Extending Wordlets to add Edit/Cinfigure flags
class WordletsMySite extends \Wordlets\WordletsMySql {
	public $ShowEdit = false;
	public $ShowConfigure = false;

	public function getWordlet($page, $name, $id = null, $attrs = null, $values = null, $show_markup = false, $cardinality = 1) {
		$wordlet = new WordletMySite($page, $name, $id, $attrs, $values, $show_markup, $cardinality);
		$wordlet->ShowEdit = $this->ShowEdit;
		$wordlet->ShowConfigure = $this->ShowConfigure;
		return $wordlet;
	}

	public function getOne($name, $echo = true) {
		$wordlet = parent::getOne($name, $echo);

		if ( $this->showMarkup && !$wordlet->Configured && $echo ) {
			echo '<span ' . $wordlet->HtmlAttrs() . '></span>';
			return null;
		}

		return $wordlet;
	}
}