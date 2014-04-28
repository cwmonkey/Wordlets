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
		$this->pageQuery = $this->pdo->prepare('SELECT * FROM ' . $this->tablePrepend . 'page WHERE name=:name');
		$this->objectQuery = $this->pdo->prepare('SELECT * FROM ' . $this->tablePrepend . 'object WHERE page_id=:page_id');
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
			$attrs = array();
			$values = array();

			$attr_query = $this->pdo->prepare('SELECT * FROM ' . $this->tablePrepend . 'attr WHERE object_id=:object_id');
			$attr_result = $attr_query->execute(array(':object_id' => $object_id));
			$attr_rows = $attr_query->fetchAll(\PDO::FETCH_ASSOC);

			foreach ( $attr_rows as $attr_row ) {
				$attr_name = $attr_row['name'];
				$attrs[$attr_name] = $attr_row;
				$attr_id = $attr_row['id'];

				$value_query = $this->pdo->prepare('SELECT * FROM ' . $this->tablePrepend . 'val WHERE attr_id=:attr_id ORDER BY idx ASC');
				$value_result = $value_query->execute(array(':attr_id' => $attr_id));
				$value_rows = $value_query->fetchAll(\PDO::FETCH_ASSOC);
				foreach ( $value_rows as $value_row ) {
					$idx = $value_row['idx'];
					$values[$idx][$attr_name] = $value_row['value'];
				}
			}

			$wordlet_object = $this->getWordlet($page, $object_row->name, $attrs, $values, $this->showMarkup);

			$this->setObject($wordlet_object);
		}
	}

	public function saveObject($object, $cardinality = 1) {
		$page_rows = $this->getRows($this->pageQuery, array(':name' => $object->Page));

		if ( !count($page_rows) ) {
			$page_query = $this->pdo->prepare('INSERT INTO ' . $this->tablePrepend . 'page (name) VALUES(:name)');
			$page_result = $page_query->execute(array(':name' => $object->Page));
			$page_id = $this->pdo->lastInsertId();
		} else {
			$page_row = $page_rows[0];
			$page_id = $page_row->id;
		}

		$object_rows = $this->getRows($this->objectQuery, array(':page_id' => $page_id));

		if ( !count($object_rows) ) {
			$object_query = $this->pdo->prepare('INSERT INTO ' . $this->tablePrepend . 'object
				(page_id, name, attrs, vals, cardinality)
				VALUES(:page_id, :name, :attrs, :vals, :cardinality)');

			$object_result = $object_query->execute(array(
				':page_id' => $page_id,
				':name' => $object->Name,
				':attrs' => serialize($object->Attrs),
				':vals' => serialize($object->Values),
				':cardinality' => $cardinality,
			));

			$object_id = $this->pdo->lastInsertId();
		} else {
			$object_row = $object_rows[0];
			$object_id = $object_row->id;

			$object_query = $this->pdo->prepare('UPDATE ' . $this->tablePrepend . 'object
				SET attrs=:attrs, vals=:vals, cardinality=:cardinality
				WHERE id=:id');

			$object_result = $object_query->execute(array(
				':id' => $object_id,
				':attrs' => serialize($object->Attrs),
				':vals' => serialize($object->Values),
				':cardinality' => $cardinality,
			));
		}

		$attr_query = $this->pdo->prepare('SELECT * FROM ' . $this->tablePrepend . 'attr WHERE name=:name AND object_id=:object_id');
		$attr_objs = array();
		foreach ( $object->Attrs as $name => $attr ) {
			$attr_result = $attr_query->execute(array(':name' => $name, ':object_id' => $object_id));
			if ( $attr_result && ($attr_rows = $attr_query->fetchAll(\PDO::FETCH_OBJ)) ) {
				$attr_row = $attr_rows[0];

				$attr_update_query = $this->pdo->prepare('UPDATE ' . $this->tablePrepend . 'attr
					SET type=:type, name=:name, info=:info
					WHERE id=:id');

				$attr['info'] = '';

				$attr_update_result = $attr_update_query->execute(array(
					':id' => $attr_row->id,
					':type' => $attr['type'],
					':name' => $name,
					':info' => $attr['info'],
				));

				$attr_objs[$name] = $attr_row->id;
			} else {
				$attr_insert_query = $this->pdo->prepare('INSERT INTO ' . $this->tablePrepend . 'attr
					(object_id, type, name, info)
					VALUES(:object_id, :type, :name, :info)');

				$attr['info'] = '';

				$attr_insert_result = $attr_insert_query->execute(array(
					':object_id' => $object_id,
					':type' => $attr['type'],
					':name' => $name,
					':info' => $attr['info'],
				));
				$attr_id = $this->pdo->lastInsertId();
				$attr_objs[$name] = $attr_id;
			}
		}

		$value_query = $this->pdo->prepare('SELECT * FROM ' . $this->tablePrepend . 'val WHERE idx=:idx AND attr_id=:attr_id');
		foreach ( $object->Values as $idx => $value ) {
			foreach ( $value as $name => $val ) {
				$attr_id = $attr_objs[$name];
				$value_result = $value_query->execute(array(':idx' => $idx, ':attr_id' => $attr_id));

				if ( $value_result && ($value_rows = $value_query->fetchAll(\PDO::FETCH_OBJ)) ) {
					$value_row = $value_rows[0];

					$value_update_query = $this->pdo->prepare('UPDATE ' . $this->tablePrepend . 'val
						SET value=:value
						WHERE id=:id');

					$value_update_result = $value_update_query->execute(array(
						':id' => $value_row->id,
						':value' => $val,
					));
				} else {
					$value_insert_query = $this->pdo->prepare('INSERT INTO ' . $this->tablePrepend . 'val
						(value, idx, attr_id)
						VALUES(:value, :idx, :attr_id)');

					$value_insert_result = $value_insert_query->execute(array(
						':value' => $val,
						':idx' => $idx,
						':attr_id' => $attr_id,
					));
				}
			}
		}

	}
}
