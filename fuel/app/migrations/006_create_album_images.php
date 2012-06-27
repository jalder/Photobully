<?php

namespace Fuel\Migrations;

class Create_album_images
{
	public function up()
	{
		\DBUtil::create_table('album_images', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'album_id' => array('constraint' => 11, 'type' => 'int'),
			'image_id' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('album_images');
	}
}