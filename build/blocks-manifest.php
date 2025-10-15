<?php
// This file is generated. Do not modify it manually.
return array(
	'user-teamgrid' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'usercompanygrid/team-grid',
		'version' => '0.1.0',
		'title' => 'Team Grid',
		'category' => 'widgets',
		'icon' => 'groups',
		'description' => 'Display team members in a grid based on company and department.',
		'attributes' => array(
			'companyId' => array(
				'type' => 'number'
			),
			'departmentId' => array(
				'type' => 'number'
			),
			'position' => array(
				'type' => 'string'
			),
			'columns' => array(
				'type' => 'number',
				'default' => 3
			),
			'showTitle' => array(
				'type' => 'boolean',
				'default' => true
			),
			'showDepartment' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'supports' => array(
			'html' => false,
			'align' => array(
				'wide',
				'full'
			)
		),
		'textdomain' => 'user-teamgrid',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	)
);
