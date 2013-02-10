<?php

class WordletsCustom extends Wordlets {
	private static $_ones;
	private static $_manys;

	public static function getOne($attrs) {
		if ( isset(self::$_ones[$attrs['name']]) ) return self::$_ones[$attrs['name']];
		$values = ( count($attrs['values']) ) ? $attrs['values'][0] : array();
		self::$_ones[$attrs['name']] = new WordletItemCustom($attrs['name'], $attrs['configs'], $values);
		return self::$_ones[$attrs['name']];
	}

	public static function getMany($attrs) {
		if ( isset(self::$_manys[$attrs['name']]) ) return self::$_manys[$attrs['name']];
		$array = array();
		foreach ( $attrs['values'] as $vs ) {
			$array[] = new WordletItemCustom($attrs['name'], $attrs['configs'], $vs);
		}

		self::$_manys[$attrs['name']] = new WordletItems($array);
		return self::$_manys[$attrs['name']];
	}
}

// Objects passed to the front end
class WordletItemCustom extends WordletItem {
	private $defaults = array(
		'format' => 'text',
		'multi' => false,
		'wordlet' => true,
	);

	public function preProcess($value, $name) {
		$config = ( isset($this->_configs[$name]) ) ? $this->_configs[$name] : array();
		$config += $this->defaults;

		if ( !$this->_configured ) {
			$value = '<span class="wordlet_configure">Configure ' . $this->_name . '</span>';
		} else {
			switch ( $config['format'] ) {
				case 'text':
					$value = strip_tags($value);
					break;
				case 'simple-html':
					$value = strip_tags($value, '<a><b><i><strong><em><br><br/><img>');
					break;
				case 'full-html':
					$value = strip_tags($value, '<a><b><i><strong><em><br><br/><img><p><ul><ol><li><blockquote><hr><hr/><h1><h2><h3><h4><h5><h6>');
					break;
			}
		}

		if ( $config['wordlet'] ) {
			$edit = ' data-edit="/edit"';
			$configure = ' data-configure="/configure"';
			$tag = (( $config['multi'] ) ? 'div' : 'span');
			$value = '<' . $tag . ' class="wordlet"' . $edit . $configure . '>' . $value . '</' . $tag . '>';
		}

		return $value;
	}
}
