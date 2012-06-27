<?php

namespace Fuel\Migrations;

class Add_caption_to_images
{
	public function up()
	{
		\DBUtil::add_fields('images', array(
			'caption' => array('constraint' => 255, 'type' => 'varchar'),

		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('images', array(
			'caption'
    
		));
	}
}