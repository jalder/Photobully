<?php

namespace Fuel\Migrations;

class Add_privacy_to_images
{
	public function up()
	{
		\DBUtil::add_fields('images', array(
			'privacy' => array('constraint' => 11, 'type' => 'int'),

		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('images', array(
			'privacy'
    
		));
	}
}