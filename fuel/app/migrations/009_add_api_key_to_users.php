<?php

namespace Fuel\Migrations;

class Add_api_key_to_users
{
	public function up()
	{
		\DBUtil::add_fields('users', array(
			'api_key' => array('constraint' => 255, 'type' => 'varchar'),

		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('users', array(
			'api_key'
    
		));
	}
}