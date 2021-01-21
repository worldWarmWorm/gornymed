<?php

class m151007_105528_add_hit_column_by_product_table extends CDbMigration
{
	public function up()
	{
		$this->addColumn('product', 'hit', 'boolean');
	}

	public function down()
	{
		$this->dropColumn('product', 'hit');
	}
}