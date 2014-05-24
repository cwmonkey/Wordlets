<?php

$action = $_GET['action'];
$id = NULL;

// POST
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	// Update wordlet
	if ( isset($_POST['id']) ) {
		$id = $_POST['id'];
		$wordlet = $wordlets->getObjectsById($id);

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
					if ( $v !== '' ) {
						$set = true;
						break;
					}
				}

				if ( $set ) $values[] = $value;
			}
			$wordlet->Values = $values;
			$wordlets->saveObject($wordlet, $wordlet->Cardinality);
		}
	// Add a new wordlet
	} elseif ( $action == 'configure' ) {
		$page_name = $_POST['page_id'];

		$attrs = array();
		$values = array(array());
		foreach ( $_POST['attr'] as $key => $attr ) {
			if ( isset($attr['name']) && $attr['name'] ) {
				$attr['show_markup'] = ( @$attr['show_markup'] ) ? 1 : 0;
				$attrs[$attr['name']] = $attr;
				$values[0][$attr['name']] = $attr['value'];
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
				'html' => 'none',
				'format' => 'none',
				'info' => '',
				'order' => 0,
				'show_markup' => 1,
				'value' => '',
			),
		),
		$values = array(
			array(
				'single' => 'This is a Title'
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
	$battr = array('name' => '', 'show_markup' => false, 'type' => 'single', 'html' => 'none', 'format' => 'none', 'info' => '');

	// If this is a new wordlet, add a value field
	if ( !$id ) $battr['value'] = '';

	$attrs = $wordlet->Attrs;
	for ( $i = 0; $i < 11; $i++ ) {
		$attrs[] = $battr;
	}

	foreach ( $attrs as $attr ) {
		$a = new stdClass();
		foreach ( $attr as $key => $value ) {
			$a->{$key} = $value;
		}

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

	foreach( $wordlet->Values as $value ) {
		$v = new stdClass();
		foreach( $wordlet->Attrs as $name => $attr ) {
			$v->{$name} = htmlspecialchars($value[$name]);
		}
		$form->values[] = $v;
	}

	$max = ( $wordlet->Cardinality ) ? $wordlet->Cardinality - count($wordlet->Values) : 10;

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
