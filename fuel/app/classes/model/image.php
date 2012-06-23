<?php

class Model_Image extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'short_name',
		'original_name',
		'user_id',
		'file_type',
		'created_at',
		'updated_at'
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);
}
