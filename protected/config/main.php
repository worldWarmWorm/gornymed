<?php
$prefix='';

return CMap::mergeArray(
	require(dirname(__FILE__).'/defaults.php'),
	array(
		'components'=>array(
			'db'=>include(dirname(__FILE__)."/{$prefix}db.php"),
		),
        'theme'=>'template'
	)
);
