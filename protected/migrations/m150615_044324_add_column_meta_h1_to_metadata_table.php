<?php

class m150615_044324_add_column_meta_h1_to_metadata_table extends CDbMigration
{
	public function up()
	{
		$this->addColumn('metadata', 'meta_h1', 'string');
	}

	public function down()
	{
		$this->dropColumn('metadata', 'meta_h1');
	}
}