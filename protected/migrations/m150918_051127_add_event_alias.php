<?php

class m150918_051127_add_event_alias extends CDbMigration
{
	public function up()
	{
		$this->addColumn('event', 'alias', 'VARCHAR(255)');
	}

	public function down()
	{
		$this->dropColumn('event', 'alias');
	}
}