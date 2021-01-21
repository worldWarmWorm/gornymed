<?php

return array(
    // Admin
    'cp'=>'admin/default/index',
    'cp/<controller>/<action:\w+>/<id:\d+>'=>'admin/<controller>/<action>',
    'cp/<controller>/<action>'=>'admin/<controller>/<action>',
    'cp/<controller>'=>'admin/<controller>',

    // Admin
    'devcp'=>'devadmin/default/index',
    'devcp/<controller>/<action:\w+>/<id:\d+>'=>'devadmin/<controller>/<action>',
    'devcp/<controller>/<action>'=>'devadmin/<controller>/<action>',
    'devcp/<controller>'=>'devadmin/<controller>',

    // Site Defaults
    '<code:(404)>'=>'error/index',
    ''=>'site/index',
    'shop'=>'shop/index',
    'cart'=>'dCart/index',
    'questions'=>'question/index',
    'sitemap'=>'site/sitemap',

    array('class'=>'application.components.rules.DAliasRule'),
    'news/<id:\d+>'=>'site/event',
    'news'=>'site/events',
	'sale'=>'sale/list',
	'sale/index'=>'sale/list',
	'sale/<id:\d+>'=>'sale/view',

    // Default Rules
    '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
    '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
    '<module>/<controller>/<action:\w+>/<id:\d+>'=>'<module>/<controller>/<action>',
    '<module>/<controller>/<action:\w+>'=>'<module>/<controller>/<action>',
);
