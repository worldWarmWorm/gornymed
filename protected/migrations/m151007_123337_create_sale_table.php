<?php

class m151007_123337_create_sale_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('sale', array(
			'id'=>'pk',
			'alias'=>'string',
			'title'=>'string',
			'active'=>'boolean',
			'preview'=>'VARCHAR(32)',
			'enable_preview'=>'boolean',
			'preview_text'=>'text',
			'text'=>'text',
			'create_time'=>'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
		));
	}

	public function down()
	{
		$this->dropTable('sale');
	}
}