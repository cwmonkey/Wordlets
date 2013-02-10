<?php

require_once('Wordlets.classes.php');

$wordlet_info = array(
	'title' => array(
		'name' => 'title',
		'configs' => array(
			array(
				'name' => 'value',
			),
		),
		'values' => array(
			array(
				'My Page',
			),
		),
	),
	'tiles' => array(
		'name' => 'tiles',
		'configs' => array(
			array(
				'name' => 'name',
			),
			array(
				'name' => 'image',
			),
			array(
				'name' => 'href',
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
	return Wordlets::getOne($wordlet_info[$name]);
}

function wl($name) {
	global $wordlet_info;
	return Wordlets::getMany($wordlet_info[$name]);
}

?>

<p><?=w('title')?></p>

<p><?=w('tiles')?></p>

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