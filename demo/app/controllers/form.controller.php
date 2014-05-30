<?php

$action = $_GET['action'];
$id = NULL;
$attr_id = ( isset($_GET['attr_id']) ) ? $_GET['attr_id'] : null;
$val_id = ( isset($_GET['val_id']) ) ? $_GET['val_id'] : null;
$index = ( isset($_GET['val_id']) ) ? $_GET['val_id'] : 0;

// POST
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

	// Update wordlet
	if ( isset($_POST['id']) ) {
		$id = $_POST['id'];
		$wordlet = $wordlets->getObjectsById($id);
		if ( $attr_id ) {
			$wordlet->AttrId = $attr_id;
		}

		if ( $val_id ) {
			$wordlet->ValueId = $val_id;
		}

		// Update wordlet configuration
		if ( $action == 'configure' ) {
			// Object
			$wordlet->Page = $_POST['page_id'];
			$wordlet->Name = $_POST['name'];

			// Attrs
			$attrs = array();

			foreach ( $_POST['attr'] as $key => $attr ) {
				if ( isset($attr['name']) && $attr['name'] ) {
					$attr['show_markup'] = ( @$attr['show_markup'] ) ? 1 : 0;
					$attr['instanced'] = ( @$attr['instanced'] ) ? 1 : 0;
					$attrs[$attr['name']] = $attr;
				}
			}

			$wordlet->Attrs = $attrs;
			$wordlets->saveObject($wordlet, $_POST['cardinality']);
		// Update wordlet values
		} elseif ( $action == 'edit' ) {
			$values = array();

			foreach ( $_POST['value'] as $value ) {
				$set = false;
				foreach ( $value as $k => $v ) {
					if ( $v['value'] !== '' ) {
						$set = true;
						break;
					}
				}

				if ( $set ) $values[$index][] = $value;
			}
			$wordlet->Values = $values;
			$wordlets->saveObject($wordlet, $wordlet->Cardinality);
		}
	// Add a new wordlet
	} elseif ( $action == 'configure' ) {
		$page_name = $_POST['page_id'];

		$attrs = array();
		$values = array();
		foreach ( $_POST['attr'] as $key => $attr ) {
			if ( isset($attr['name']) && $attr['name'] ) {
				$attr['show_markup'] = ( @$attr['show_markup'] ) ? 1 : 0;
				$attr['instanced'] = ( @$attr['instanced'] ) ? 1 : 0;
				$attrs[$attr['name']] = $attr;

				if ( $val_id ) {
					$values[$val_id][0][$attr['name']]['value'] = $attr['value'];
				} else {
					$values[$attr['name']]['value'] = $attr['value'];
				}
			}
		}

		$wordlet = new \Wordlets\Wordlet(
			$page_name,
			$_POST['name'],
			null,
			$attrs,
			$values,
			false,
			$_POST['cardinality']
		);

		if ( $attr_id ) {
			$wordlet->AttrId = $attr_id;
		}

		if ( $val_id ) {
			$wordlet->ValueId = $val_id;
		}

		$id = $wordlets->saveObject($wordlet, $_POST['cardinality']);
	}
} else {
	$id = @$_GET['id'];
}

$form = new stdClass();
$form->action = '/demo/?' . $_SERVER['QUERY_STRING'];

$attrattrs = array(
	'type' => array('single', 'multi', 'number'),
	'html' => array('convert', 'safe', 'all', 'none'),
	'format' => array('none', 'simple'),
);

if ( $id ) {
	$wordlet = $wordlets->getObjectsById($id);
} else {
	$wordlet = new \Wordlets\Wordlet(
		$_GET['page'],
		$_GET['name'],
		null,
		array(
			'single' => array(
				'name' => 'single',
				'type' => 'single',
				'instanced' => 0,
				'html' => 'none',
				'format' => 'none',
				'info' => '',
				'idx' => 0,
				'show_markup' => 1,
				'value' => '',
			),
		),
		$values = array(
			$index => array(
				'single' => array('value' => 'This is a Title'),
			),
		),
		false,
		1
	);
}

/* // Normal language style post arrays
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$post = array();
	foreach ( explode('&', file_get_contents('php://input')) as $keyValuePair ) {
		list($key, $value) = explode('=', $keyValuePair);
		$post[$key][] = $value;
	}

	var_dump($post);
}*/

// Configure wordlet
if ( $action == 'configure' ) {
	$page_name = NULL;

	// Object
	if ( $wordlet->Id ) {
		$form->id = $wordlet->Id;
		$form->name = $wordlet->Name;
		$form->cardinality = $wordlet->Cardinality;
		$form->SaveText = 'Edit';
	} else {
		$form->name = $_GET['name'];
		$form->cardinality = 1;
		$form->SaveText = 'Add';
	}

	// Pages dropdown
	$form->pages = array();

	foreach ( $routes as $name => $route ) {
		$obj = new stdClass();
		$obj->value = $name;
		$obj->text = ( isset($route['title']) ) ? $route['title'] : $name;
		$obj->selected = ( $wordlet->Page == $name );
		$form->pages[] = $obj;
	}

	// Attrs

	// Make a blank attr for new attrs
	$battr = array(
		'name' => '',
		'show_markup' => false,
		'type' => 'single',
		'instanced' => false,
		'html' => 'none',
		'format' => 'none',
		'info' => ''
	);

	// If this is a new wordlet, add a value field
	if ( !$id ) $battr['value'] = '';

	$attrs = $wordlet->Attrs;

	foreach ( $attrs as $last_attr ) { }
	$last_idx = $last_attr['idx'] + 1;

	for ( $i = 0; $i < 11; $i++ ) {
		$battr['idx'] = $last_idx + $i;
		$attrs[] = $battr;
	}

	foreach ( $attrs as $attr ) {
		$a = new stdClass();
		foreach ( $attr as $key => $value ) {
			$a->{$key} = $value;
		}

		// attrs with dropdowns
		foreach ( $attrattrs as $akey => $attrattr) {
			$a->{$akey . 's'} = array();
			foreach ( $attrattr as $type ) {
				$t = new stdClass();
				$t->value = $type;
				$t->text = $type;
				$t->selected = ( $type == $attr[$akey] );
				$a->{$akey . 's'}[] = $t;
			}
		}

		$form->attrs[] = $a;
	}

	include($app . '/templates/form_configure.tpl.php');
// Edit wordlet values
} elseif ( $action == 'edit' ) {
	$form->SaveText = 'Edit';
	$form->attrs = array();
	$form->id = $id;

	if ( $val_id ) {
		$values = isset($wordlet->Values[$val_id]) ? $wordlet->Values[$val_id] : array();
	} else {
		$values = $wordlet->Values;
	}

	foreach( $values as $value ) {
		$v = new stdClass();
		foreach( $wordlet->Attrs as $name => $attr ) {
			if ( isset($value[$name]) ) {
				$v->{$name} = htmlspecialchars($value[$name]['value']);
			} else {
				$v->{$name} = '';
			}
		}
		$form->values[] = $v;
	}

	$max = ( $wordlet->Cardinality ) ? $wordlet->Cardinality - count($values) : 10;

	for ( $i = 0; $i < $max; $i++ ) {
		$form->values[] = null;
	}

	foreach ( $wordlet->Attrs as $attr ) {
		$a = new stdClass();
		foreach ( $attr as $key => $value ) {
			$a->{$key} = htmlspecialchars($value);
		}

		$form->attrs[] = $a;
	}

	include($app . '/templates/form_edit.tpl.php');
}
