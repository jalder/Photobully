<?php

namespace Fuel\Migrations;

class Add_location_to_images
{
	public function up()
	{
		\DBUtil::add_fields('images', array(
			'location' => array('constraint' => 255, 'type' => 'varchar'),

		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('images', array(
			'location'
    
		));
	}
}