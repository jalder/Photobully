<?php

namespace Fuel\Migrations;

class Create_images
{
	public function up()
	{
		\DBUtil::create_table('images', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'short_name' => array('constraint' => 20, 'type' => 'varchar'),
			'original_name' => array('constraint' => 255, 'type' => 'varchar'),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'file_type' => array('constraint' => 255, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('images');
	}
}