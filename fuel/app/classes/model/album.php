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
	
	public static function public_albums()
	{
		$albums = Model_Album::find('all');
		$data = array();
		$i = 0;
		foreach($albums as $a){
			$images = Model_Album_Image::find()->where('album_id',$a->id)->get();
			foreach($images as $image){
				$check = Model_Image::find($image->image_id);
				if(!$check){
					continue;
				}
				if($check->privacy == 0){
					$i++;
				}
			}
			if($i){
				$data[] = $a;
				$i = 0;
			}
		}
		return $data;		
		
	}
	
}
