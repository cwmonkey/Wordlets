<?php 

class WordletsMySql extends WordletsBase {
	public static $DbLink;
	public static $TablePrepend;

	public static function OpenDb($host, $user, $pass, $db, $prepend = '') {
		self::$DbLink = mysql_connect($host, $user, $pass);
		mysql_select_db($db);
		self::$TablePrepend = $prepend;
	}

	// Grab the wordlet objects/values from the db and store them in self::$Pages
	public static function LoadObjects() {
		$func_get_args = func_get_args();
		foreach ( $func_get_args as $page ) {
			self::$CurrentPage = $page;
			$page_query = sprintf('SELECT * FROM ' . self::$TablePrepend . 'page WHERE name="%s"',
				mysql_real_escape_string($page));

			$page_result = mysql_query($page_query);
			if ( !$page_result || !mysql_num_rows($page_result) ) continue;
			$page_row = mysql_fetch_assoc($page_result);

			$object_query = 'SELECT * FROM ' . self::$TablePrepend . 'object WHERE page_id="' . $page_row['id']  . '"';

			$object_result = mysql_query($object_query);

			while ( $object_row = mysql_fetch_assoc($object_result) ) {
				$attrs = unserialize($object_row['attrs']);
				$values = unserialize($object_row['vals']);

				/* $value_query = 'SELECT * FROM ' . self::$TablePrepend . 'value WHERE object_id="' . $object_row['id']  . '"';

				$value_result = mysql_query($value_query);
				$values = array();
				while ( $value_row = mysql_fetch_assoc($value_result) ) {
					$values[] = unserialize($value_row['value']);
				} */

				$wordlet_object = new WordletsObject($page, $object_row['name'], $attrs, $values, self::$ShowMarkup);

				self::SetObject($wordlet_object);
			}
		}
	}

	public static function SaveObject($object, $cardinality = 1) {
		$page_query = sprintf('SELECT * FROM ' . self::$TablePrepend . 'page WHERE name="%s"',
			mysql_real_escape_string($object->Page));

		$page_result = mysql_query($page_query);

		if ( !$page_result || !mysql_num_rows($page_result) ) {
			$page_query = sprintf('INSERT INTO ' . self::$TablePrepend . 'page (name) VALUES("%s")',
				mysql_real_escape_string($object->Page));

			$page_result = mysql_query($page_query);
			$page_id = mysql_insert_id();
		} else {
			$page_row = mysql_fetch_assoc($page_result);
			$page_id = $page_row['id'];
		}

		$object_query = sprintf('SELECT * FROM ' . self::$TablePrepend . 'object WHERE name="%s"',
			mysql_real_escape_string($object->Name));

		$object_result = mysql_query($object_query);

		if ( !$object_result || !mysql_num_rows($object_result) ) {
			$object_query = sprintf('INSERT INTO ' . self::$TablePrepend . 'object
				(page_id, name, attrs, vals, cardinality)
				VALUES(' . $page_id . ', "%s", "%s", "%s", "%s")',
				mysql_real_escape_string($object->Name),
				mysql_real_escape_string(serialize($object->Attrs)),
				mysql_real_escape_string(serialize($object->Values)),
				mysql_real_escape_string($cardinality)
				);

			$object_result = mysql_query($object_query);
		} else {
			$object_query = sprintf('UPDATE ' . self::$TablePrepend . 'object
				SET attrs="%s", vals="%s", cardinality="%s"
				WHERE page_id=' . $page_id . ' AND name="%s"',
				mysql_real_escape_string(serialize($object->Attrs)),
				mysql_real_escape_string(serialize($object->Values)),
				mysql_real_escape_string($cardinality),
				mysql_real_escape_string($object->Name)
				);

			$object_result = mysql_query($object_query);
		}
	}
}
