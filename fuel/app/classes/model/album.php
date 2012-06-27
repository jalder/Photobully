<?php

class Model_Album extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'name',
		'user_id',
		'description',
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
	
	protected static $_many_many = array(
	    'images' => array(
	        'key_from' => 'id',
	        'key_through_from' => 'album_id', 
	        'table_through' => 'album_images',
	        'key_through_to' => 'image_id',
	        'model_to' => 'Model_Image',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);
	
}
