<?php 

namespace Wordlets;

class WordletsMySql extends WordletsBase {
	private $pdo;
	private $tablePrepend;
	private $pageQuery;
	private $pageInsertQuery;
	private $objectQuery;

	public function __construct($connect, $user, $password, $tablePrepend = '') {
		$this->pdo = new \PDO($connect, $user, $password);
		$this->tablePrepend = $tablePrepend;
		$this->pageQuery = $this->pdo->prepare("SELECT * FROM {$this->tablePrepend}page WHERE name=:name");
		$this->objectQuery = $this->pdo->prepare("SELECT * FROM {$this->tablePrepend}object WHERE page_id=:page_id");
	}

	private function getRows($query, $params = null) {
		$result = $query->execute($params);
		$rows = $query->fetchAll(\PDO::FETCH_OBJ);
		return $rows;
	}

	// Grab the wordlet objects/values from the db and store them in $this->pages
	public function loadObjects() {
		$func_get_args = func_get_args();
		foreach ( $func_get_args as $page ) {
			$this->loadObjectsByPage($page);
		}
	}

	public function loadObjectsByPage($page) {
		$this->currentPage = $page;
		$page_rows = $this->getRows($this->pageQuery, array(':name' => $page));

		if ( !count($page_rows) ) return;
		$page_row = $page_rows[0];

		$object_rows = $this->getRows($this->objectQuery, array(':page_id' => $page_row->id));

		foreach ( $object_rows as $object_row ) {
			$object_id = $object_row->id;

			$wordlet_object = $this->getObjectsById($object_id);

			$this->setObject($wordlet_object);
		}
	}

	public function getObjectsById($id) {
		$object_id = $id;
		$attrs = array();
		$values = array();

		$object_query = $this->pdo->prepare("SELECT * FROM {$this->tablePrepend}object WHERE id=:id LIMIT 1");
		$object_rows = $this->getRows($object_query, array(':id' => $object_id));
		$object_row = $object_rows[0];

		$page_query = $this->pdo->prepare("SELECT * FROM {$this->tablePrepend}page WHERE id=:id LIMIT 1");
		$page_rows = $this->getRows($page_query, array(':id' => $object_row->page_id));
		$page_row = $page_rows[0];
		$page = $page_row->name;

		$attr_query = $this->pdo->prepare("SELECT * FROM {$this->tablePrepend}attr WHERE object_id=:object_id ORDER BY idx ASC");
		$attr_result = $attr_query->execute(array(':object_id' => $object_id));
		$attr_rows = $attr_query->fetchAll(\PDO::FETCH_ASSOC);

		foreach ( $attr_rows as $attr_row ) {
			$attr_name = $attr_row['name'];
			$attrs[$attr_name] = $attr_row;
			$attr_id = $attr_row['id'];

			$value_query = $this->pdo->prepare("SELECT * FROM {$this->tablePrepend}val WHERE attr_id=:attr_id ORDER BY idx ASC");
			$value_result = $value_query->execute(array(':attr_id' => $attr_id));
			$value_rows = $value_query->fetchAll(\PDO::FETCH_ASSOC);
			foreach ( $value_rows as $value_row ) {
				$idx = $value_row['idx'];
				$values[$idx][$attr_name] = $value_row['value'];
			}
		}

		$wordlet_object = $this->getWordlet($page, $object_row->name, $object_row->id, $attrs, $values, $this->showMarkup, $object_row->cardinality);

		return $wordlet_object;
	}

	public function saveObject($object, $cardinality = 1) {
		// Get Page
		$page_rows = $this->getRows($this->pageQuery, array(':name' => $object->Page));

		// Make new page
		if ( !count($page_rows) ) {
			$page_query = $this->pdo->prepare("INSERT INTO {$this->tablePrepend}page (name) VALUES(:name)");
			$page_result = $page_query->execute(array(':name' => $object->Page));
			$page_id = $this->pdo->lastInsertId();
		// Get existing page
		} else {
			$page_row = $page_rows[0];
			$page_id = $page_row->id;
		}

		// Get Object
		if ( isset($object->Id) ) {
			$object_query = $this->pdo->prepare("SELECT * FROM {$this->tablePrepend}object WHERE id=:id");
			$object_rows = $this->getRows($object_query, array(':id' => $object->Id));
		} else {
			$object_query = $this->pdo->prepare("SELECT * FROM {$this->tablePrepend}object WHERE page_id=:page_id AND name=:name");
			$object_rows = $this->getRows($object_query, array(':page_id' => $page_id, ':name' => $object->Name));
		}

		// Make new object
		if ( !count($object_rows) ) {
			$object_query = $this->pdo->prepare("INSERT INTO {$this->tablePrepend}object
				(page_id, name, cardinality)
				VALUES(:page_id, :name, :cardinality)");

			$object_result = $object_query->execute(array(
				':page_id' => $page_id,
				':name' => $object->Name,
				':cardinality' => $cardinality,
			));

			$object_id = $this->pdo->lastInsertId();
		// Update existing object
		} else {
			$object_row = $object_rows[0];
			$object_id = $object_row->id;

			$object_query = $this->pdo->prepare("UPDATE {$this->tablePrepend}object
				SET name=:name, page_id=:page_id, cardinality=:cardinality
				WHERE id=:id");

			$object_result = $object_query->execute(array(
				':id' => $object_id,
				':cardinality' => $cardinality,
				':name' => $object->Name,
				':page_id' => $page_id,
			));
		}

		// Attrs
		$attr_query = $this->pdo->prepare("SELECT * FROM {$this->tablePrepend}attr WHERE name=:name AND object_id=:object_id ORDER BY idx ASC");
		$attr_objs = array();
		$attr_ids = array();
		foreach ( $object->Attrs as $name => $attr ) {
			$attr_result = $attr_query->execute(array(':name' => $name, ':object_id' => $object_id));

			// Update existing attr
			if ( $attr_result && ($attr_rows = $attr_query->fetchAll(\PDO::FETCH_OBJ)) ) {
				$attr_row = $attr_rows[0];

				$attr_update_query = $this->pdo->prepare("UPDATE {$this->tablePrepend}attr
					SET type=:type, html=:html, format=:format, name=:name, info=:info, show_markup=:show_markup, idx=:idx
					WHERE id=:id");

				$attr['info'] = '';

				$attr_update_result = $attr_update_query->execute(array(
					':id' => $attr_row->id,
					':type' => $attr['type'],
					':html' => $attr['html'],
					':format' => $attr['format'],
					':name' => $name,
					':info' => $attr['info'],
					':idx' => $attr['idx'],
					':show_markup' => $attr['show_markup'],
				));

				$attr_objs[$name] = $attr_row->id;
				$attr_ids[] = $attr_row->id;
			// Make new Attr
			} else {
				$attr_insert_query = $this->pdo->prepare("INSERT INTO {$this->tablePrepend}attr
					(object_id, idx, type, html, format, name, info, show_markup)
					VALUES(:object_id, :idx, :type, :html, :format, :name, :info, :show_markup)");

				$attr['info'] = '';

				$attr_insert_result = $attr_insert_query->execute(array(
					':object_id' => $object_id,
					':type' => $attr['type'],
					':html' => $attr['html'],
					':format' => $attr['format'],
					':name' => $name,
					':info' => $attr['info'],
					':idx' => $attr['idx'],
					':show_markup' => $attr['show_markup'],
				));
				$attr_id = $this->pdo->lastInsertId();
				$attr_objs[$name] = $attr_id;
				$attr_ids[] = $attr_id;
			}
		}

		// Delete old Attrs
		$qs = str_repeat('?,', count($attr_ids) - 1) . '?';
		$attr_delete_query = $this->pdo->prepare("DELETE FROM {$this->tablePrepend}attr WHERE id NOT IN ($qs) AND object_id = ?");
		$attr_ids[] = $object_id;
		$attr_delete_query->execute($attr_ids);

		// Values
		$value_query = $this->pdo->prepare("SELECT * FROM {$this->tablePrepend}val WHERE idx=:idx AND attr_id=:attr_id");
		foreach ( $object->Values as $idx => $value ) {
			foreach ( $value as $name => $val ) {
				$attr_id = $attr_objs[$name];
				$value_result = $value_query->execute(array(':idx' => $idx, ':attr_id' => $attr_id));

				// Update existing value
				if ( $value_result && ($value_rows = $value_query->fetchAll(\PDO::FETCH_OBJ)) ) {
					$value_row = $value_rows[0];

					$value_update_query = $this->pdo->prepare("UPDATE {$this->tablePrepend}val
						SET value=:value
						WHERE id=:id");

					$value_update_result = $value_update_query->execute(array(
						':id' => $value_row->id,
						':value' => $val,
					));
				// Add new value
				} else {
					$value_insert_query = $this->pdo->prepare("INSERT INTO {$this->tablePrepend}val
						(value, idx, attr_id)
						VALUES(:value, :idx, :attr_id)");

					$value_insert_result = $value_insert_query->execute(array(
						':value' => $val,
						':idx' => $idx,
						':attr_id' => $attr_id,
					));
				}
			}
		}

		// Remove old values
		$delete_values_query = $this->pdo->prepare("DELETE FROM {$this->tablePrepend}val WHERE idx > :idx AND attr_id = :attr_id");
		foreach ( $object->Values as $value ) {
			foreach ( $value as $name => $val ) {
				$attr_id = $attr_objs[$name];
				$delete_values_query->execute(array(':idx' => $idx, ':attr_id' => $attr_id));
			}
		}

		return $object_id;
	}
}
