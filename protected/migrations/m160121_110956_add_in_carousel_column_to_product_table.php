<?php

class m160121_110956_add_in_carousel_column_to_product_table extends CDbMigration
{
	public function up()
	{
		$this->addColumn('product', 'in_carousel', 'boolean');
	}

	public function down()
	{
		$this->dropColumn('product', 'in_carousel');
	}
}