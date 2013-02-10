<?php

require_once('Wordlets.classes.php');
require_once('WordletsCustom.classes.php');

$wordlet_info = array(
	'title' => array(
		'name' => 'title',
		'title' => 'Page Title',
		'description' => 'description',
		'configured' => true,
		'configs' => array(
			array(
				'name' => 'value',
				'title' => 'Value',
				'default' => 'My Site',
				'description' => 'Plain Text',
				'format' => 'text',
				'multi' => false,
				'wordlet' => true,
			),
		),
		'values' => array(
			array(
				'My Page',
			),
		),
	),
	'body' => array(
		'name' => 'body',
		'title' => '',
		'description' => '',
		'configured' => false,
		'configs' => array(
		),
		'values' => array(
		),
	),
	'tiles' => array(
		'name' => 'tiles',
		'title' => 'SNAP tiles',
		'description' => '',
		'configured' => true,
		'configs' => array(
			array(
				'name' => 'name',
				'title' => 'Query string name',
				'default' => '',
				'description' => '',
				'format' => 'text',
				'multi' => false,
				'wordlet' => false,
			),
			array(
				'name' => 'image',
				'title' => 'Image URL',
				'default' => '',
				'description' => '100x50',
				'format' => 'text',
				'multi' => false,
				'wordlet' => false,
			),
			array(
				'name' => 'href',
				'title' => 'Link URL',
				'default' => '',
				'description' => '',
				'format' => 'text',
				'multi' => false,
				'wordlet' => false,
			),
		),
		'values' => array(
			array(
				'jim',
				'http://placekitten.com/100/50',
				'http://placekitten.com/',
			),
			array(
				'bob',
				'http://placekitten.com/101/51?2',
				'http://placekitten.com/2',
			),
			array(
				'roger',
				'http://placekitten.com/102/52?3',
				'http://placekitten.com/3',
			),
		),
	)
);

function w($name) {
	global $wordlet_info;
	return WordletsCustom::getOne($wordlet_info[$name]);
}

function wl($name) {
	global $wordlet_info;
	return WordletsCustom::getMany($wordlet_info[$name]);
}

?>

<p><?=w('title')?></p>

<p><?=w('tiles')?></p>

<p><?=w('body')?></p>

<? foreach ( wl('tiles') as $w ): ?>
	<p>
		<a href="<?=$w->href?>"><img src="<?=$w->image?>"/></a>
	</p>
<? endforeach ?>

<p>Selected:</p>

<? if ( ($w = wl('tiles')->find('name', 'bob')) ): ?>
	<p>
		<a href="<?=$w->href?>"><img src="<?=$w->image?>"/></a>
	</p>
<? endif ?>

<hr/>

<?highlight_file(__FILE__)?>