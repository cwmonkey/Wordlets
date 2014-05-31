<?php

$query = $pdo->prepare("TRUNCATE {$tablePrepend}page");
$result = $query->execute();

$query = $pdo->prepare("TRUNCATE {$tablePrepend}val");
$result = $query->execute();

$query = $pdo->prepare("TRUNCATE {$tablePrepend}attr");
$result = $query->execute();

$query = $pdo->prepare("TRUNCATE {$tablePrepend}object");
$result = $query->execute();

$site_title = new \Wordlets\Wordlet(
	'_site',
	'Site Title',
	array(
		'attrs' => array(
			'single' => array(
				'type' => 'single',
				'html' => 'none',
				'format' => 'none',
				'idx' => 0,
				'show_markup' => 1,
			),
		),
		'values' => array(
			array(
				'single' => array('value' => 'This is the Site Title'),
			),
		),
	)
);
$wordlets->saveObject($site_title, 1);

$title = new \Wordlets\Wordlet(
	'index',
	'Title',
	array(
		'attrs' => array(
			'single' => array(
				'type' => 'single',
				'html' => 'none',
				'format' => 'none',
				'idx' => 0,
				'show_markup' => 1,
			),
		),
		'values' => array(
			array(
				'single' => array('value' => 'This is the Page Title'),
			),
		),
	)
);
$wordlets->saveObject($title, 1);

$title = new \Wordlets\Wordlet(
	'mysite',
	'MySite Title',
	array(
		'attrs' => array(
			'single' => array(
				'type' => 'single',
				'html' => 'none',
				'format' => 'none',
				'idx' => 0,
				'show_markup' => 1,
			),
		),
		'values' => array(
			array(
				'single' => array('value' => 'This is the MySite Title'),
			),
		),
	)
);
$wordlets->saveObject($title, 1);

$title = new \Wordlets\Wordlet(
	'mysite-index',
	'MySite Index Title',
	array(
		'attrs' => array(
			'single' => array(
				'type' => 'single',
				'html' => 'none',
				'format' => 'none',
				'idx' => 0,
				'show_markup' => 1,
			),
		),
		'values' => array(
			array(
				'single' => array('value' => 'This is the MySite Index Page Title'),
			),
		),
	)
);
$wordlets->saveObject($title, 1);

$schools = new \Wordlets\Wordlet(
	'mysite-index',
	'Schools',
	array(
		'attrs' => array(
			'Title' => array(
				'type' => 'single',
				'html' => 'none',
				'format' => 'none',
				'idx' => 0,
				'show_markup' => 1,
			),
			'Image' => array(
				'type' => 'single',
				'html' => 'none',
				'format' => 'none',
				'idx' => 1,
				'show_markup' => 1,
				'instanced' => 1,
			),
			'Description' => array(
				'type' => 'multi',
				'html' => 'safe',
				'format' => 'simple',
				'idx' => 2,
				'show_markup' => 1,
			),
		),
		'values' => array(
			array(
				'Title' => array('value' => 'School One'),
				'Image' => array('value' => ''),
				'Description' => array('value' => 'We is best.'),
			),
			array(
				'Title' => array('value' => 'School Two'),
				'Image' => array('value' => ''),
				'Description' => array('value' => 'We is better.'),
			),
		),
	)
);
$id = $wordlets->saveObject($schools, 2);

$schools = $wordlets->getObjectsById($id);

$images = new WordletMySite(
	$schools->Page,
	$schools->Name . ':' . $schools->Attrs['Image']['name'],
	array(
		'attrs' => array(
			'src' => array(
				'type' => 'single',
				'html' => 'none',
				'format' => 'none',
				'idx' => 0,
				'show_markup' => 0,
			),
			'width' => array(
				'type' => 'number',
				'html' => 'none',
				'format' => 'none',
				'idx' => 0,
				'show_markup' => 0,
			),
			'height' => array(
				'type' => 'number',
				'html' => 'none',
				'format' => 'none',
				'idx' => 0,
				'show_markup' => 0,
			),
		),
		'values' => array(
			$schools->Values[0]['Image']['id'] => array(
				array(
					'src' => array('value' => 'http://lorempizza.com/100/100'),
					'width' => array('value' => '100'),
					'height' => array('value' => '100'),
				),
				array(
					'src' => array('value' => 'http://lorempixel.com/100/100'),
					'width' => array('value' => '100'),
					'height' => array('value' => '100'),
				),
			),
			$schools->Values[1]['Image']['id'] => array(
				array(
					'src' => array('value' => 'http://dummyimage.com/100x100'),
					'width' => array('value' => '100'),
					'height' => array('value' => '100'),
				),
			),
		),
		'attr_id' => $schools->Attrs['Image']['id']
	)
);
$wordlets->saveObject($images, 2);

$meta = new \Wordlets\Wordlet(
	'_site',
	'Meta Description',
	array(
		'attrs' => array(
			'single' => array(
				'type' => 'single',
				'html' => 'none',
				'format' => 'none',
				'idx' => 0,
				'show_markup' => 1,
			),
		),
		'values' => array(
			array(
				'single' => array('value' => 'Wordlets, a cms thing'),
			),
		),
	)
);
$wordlets->saveObject($meta, 1);

$content = new \Wordlets\Wordlet(
	'mysite-index',
	'Content',
	array(
		'attrs' => array(
			'multi' => array(
				'type' => 'multi',
				'html' => 'none',
				'format' => 'simple',
				'idx' => 0,
				'show_markup' => 1,
			),
		),
		'values' => array(
			array(
				'multi' => array('value' => 'A lone of text in a paragraph tag.

					Another line
					with a br.'),
			),
		),
	)
);
$wordlets->saveObject($content, 1);

$footer = new \Wordlets\Wordlet(
	'_site',
	'Footer',
	array(
		'attrs' => array(
			'single' => array(
				'type' => 'single',
				'html' => 'safe',
				'format' => 'none',
				'idx' => 0,
				'show_markup' => 1,
			),
		),
		'values' => array(
			array(
				'single' => array('value' => '<a href="https://github.com/cwmonkey/Wordlets">Source on GIT</a>'),
			),
		),
	)
);
$wordlets->saveObject($footer, 1);

$footer = new \Wordlets\Wordlet(
	'mysite',
	'MySite Footer',
	array(
		'attrs' => array(
			'single' => array(
				'type' => 'single',
				'html' => 'none',
				'format' => 'none',
				'idx' => 0,
				'show_markup' => 1,
			),
		),
		'values' => array(
			array(
				'single' => array('value' => 'MySite Footer'),
			),
		),
	)
);
$wordlets->saveObject($footer, 1);

$navs = new \Wordlets\Wordlet(
	'mysite',
	'navs',
	array(
		'attrs' => array(
			'single' => array(
				'type' => 'single',
				'html' => 'none',
				'format' => 'none',
				'idx' => 0,
				'show_markup' => 0,
			),
			'href' => array(
				'type' => 'single',
				'html' => 'none',
				'format' => 'none',
				'idx' => 1,
				'show_markup' => 0,
			),
		),
		'values' => array(
			array(
				'single' => array('value' => 'Home'),
				'href' => array('value' => '{mysite-index_url}'),
			),
			array(
				'single' => array('value' => 'Page 1'),
				'href' => array('value' => '{mysite-page1_url}'),
			),
			array(
				'single' => array('value' => 'Page 2'),
				'href' => array('value' => '{mysite-page2_url}'),
			),
			array(
				'single' => array('value' => 'Page 3'),
				'href' => array('value' => '{mysite-page3_url}'),
			),
		),
		'cardinality' => 0,
	)
);
$wordlets->saveObject($navs, 0);

$image = new \Wordlets\Wordlet(
	'index',
	'Image',
	array(
		'attrs' => array(
			'src' => array(
				'type' => 'single',
				'html' => 'none',
				'format' => 'none',
				'idx' => 0,
				'show_markup' => 0,
			),
			'alt' => array(
				'type' => 'single',
				'html' => 'none',
				'format' => 'none',
				'idx' => 1,
				'show_markup' => 0,
			),
		),
		'values' => array(
			array(
				'src' => array('value' => 'http://i.imgur.com/9fOG9nlb.jpg'),
				'alt' => array('value' => 'This is some Alt'),
			),
		),
	)
);
$wordlets->saveObject($image, 1);

$images = new \Wordlets\Wordlet(
	'index',
	'Images',
	array(
		'attrs' => array(
			'src' => array(
				'type' => 'single',
				'html' => 'none',
				'format' => 'none',
				'idx' => 0,
				'show_markup' => 0,
			),
			'alt' => array(
				'type' => 'single',
				'html' => 'none',
				'format' => 'none',
				'idx' => 1,
				'show_markup' => 0,
			),
		),
		'values' => array(
			array(
				'src' => array('value' => '//placehold.io/100/100.png?text=Sucka'),
				'alt' => array('value' => 'This is some Alt1'),
			),
			array(
				'src' => array('value' => 'http://placehold.it/100x100'),
				'alt' => array('value' => 'This is some Alt2'),
			),
			array(
				'src' => array('value' => 'http://dummyimage.com/100x100/ff0000/ffffff'),
				'alt' => array('value' => 'This is some Alt3'),
			),
			array(
				'src' => array('value' => 'http://fpoimg.com/100x100?text=Sucka'),
				'alt' => array('value' => 'This is some Alt4'),
			),
		),
	)
);
$wordlets->saveObject($images, 0);

$subtitle = new \Wordlets\Wordlet(
	'index',
	'SubTitle',
	array(
		'attrs' => array(
			'single' => array(
				'type' => 'single',
				'html' => 'none',
				'format' => 'none',
				'idx' => 0,
				'show_markup' => 1,
			),
		),
		'values' => array(
			array(
				'single' => array('value' => 'This is a Sub Title'),
			),
		),
	)
);
$wordlets->saveObject($subtitle, 1);

$list = new \Wordlets\Wordlet(
	'index',
	'List',
	array(
		'attrs' => array(
			'single' => array(
				'type' => 'single',
				'html' => 'none',
				'format' => 'none',
				'idx' => 0,
				'show_markup' => 1,
			),
		),
		'values' => array(
			array(
				'single' => array('value' => 'This is the first list item'),
			),
			array(
				'single' => array('value' => 'This is the second list item'),
			),
			array(
				'single' => array('value' => 'This is the third list item'),
			),
			array(
				'single' => array('value' => 'This is the fourth list item'),
			),
		),
		'cardinality' => 0,
	)
);
$wordlets->saveObject($list, 0);
