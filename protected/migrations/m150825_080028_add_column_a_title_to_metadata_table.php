<?php

class m150825_080028_add_column_a_title_to_metadata_table extends CDbMigration
{
	public function up()
	{
		$this->addColumn('metadata', 'a_title', 'VARCHAR(255)');
	}

	public function down()
	{
		$this->dropColumn('metadata', 'a_title');
	}
}