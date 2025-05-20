<?php
// This file is generated. Do not modify it manually.
return array(
	'dmg-task' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'dmg-task/stylised-link',
		'version' => '0.1.0',
		'title' => 'Stylised Link',
		'category' => 'widgets',
		'icon' => 'admin-links',
		'description' => 'Creates a stylised link to the selected post.',
		'example' => array(
			
		),
		'attributes' => array(
			'targetPostId' => array(
				'type' => 'number',
				'default' => 0
			),
			'targetPostTitle' => array(
				'type' => 'string',
				'default' => ''
			),
			'targetPostLink' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'dmg-task',
		'editorScript' => array(
			'file:./index.js',
			'file:./metadata-sync.js'
		),
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css'
	)
);
