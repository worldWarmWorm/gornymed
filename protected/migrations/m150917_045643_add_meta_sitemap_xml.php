<?php

class m150917_045643_add_meta_sitemap_xml extends CDbMigration
{
	public function up()
	{
		$this->addColumn('metadata', 'priority', 'float');
		$this->addColumn('metadata', 'lastmod', 'VARCHAR(255)');
		$this->addColumn('metadata', 'changefreq', 'VARCHAR(255)');
	}

	public function down()
	{
		$this->dropColumn('metadata', 'priority');
		$this->dropColumn('metadata', 'lastmod');
		$this->dropColumn('metadata', 'changefreq');

		#echo "m150917_045643_add_meta_sitemap_xml does not support migration down.\n";
		return true;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}