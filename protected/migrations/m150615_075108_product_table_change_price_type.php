<?php

class m150615_075108_product_table_change_price_type extends CDbMigration
{
	public function up()
	{
		$this->alterColumn('product', 'price', 'DECIMAL(15,2)');
	}

	public function down()
	{
		$this->alterColumn('product', 'price', 'INT(11)');
	}
}