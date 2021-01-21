<?php
return array(
	'basePath'=>dirname(__FILE__).DS.'..',
	'name'=>'Новый сайт',

	'preload'=>array('log', 'd'),

	'aliases'=>array(
		'widget'=>'application.widget',
		'widgets'=>'application.widget',
	),

	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.components.behaviors.*',
		'application.components.filters.*',
		'application.components.helpers.*',
		'application.components.models.*',
		'application.components.validators.*',
        'ext.*',
        'ext.helpers.*',
        'ext.sitemap.*',
        'ext.CmsMenu.*',
        'ext.ContentDecorator.*'
	),
	
	'modules'=>array(
		'modules'=>array(
		    'actions',
		),
        'admin',
        'devadmin',
        /*'gii'=>array(
             'class'=>'system.gii.GiiModule',
             'password'=>'1',
             'generatorPaths'=>array(
                 'application.gii',   // псевдоним пути
             ),
              'ipFilters'=>array('127.0.0.1'),
             // 'newFileMode'=>0666,
             // 'newDirMode'=>0777,
         ),*/
	),

	// application components
	'components'=>array(
		'd'=>array(
			'class'=>'DApi',
			'modules'=>@include(dirname(__FILE__).DS.'modules.php')?:array(),
			'configDCart'=>include(dirname(__FILE__).DS.'dcart.php')
		),
			
		'user'=>array(
			'class'=>'DWebUser',
			'allowAutoLogin'=>true,
            'loginUrl' => array('admin/default/login'),
		),

        'cache'=>array(
            'class'=>'system.caching.CFileCache',
         ),

        'settings'=>array(
            'class'     => 'CmsSettings',
            'cacheId'   => 'global_website_settings',
            'cacheTime' => 84000,
        ),

		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,
			'rules'=>include(dirname(__FILE__).DS.'urls.php')
		),

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=',
			'emulatePrepare' => true,
			'username' => '',
			'password' => '',
			'charset' => 'utf8',
            'tablePrefix' => ''
		),

		'errorHandler'=>array(
            'errorAction'=>'error/error',
        ),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			)
		),

        'email' => array(
            'class'=>'ext.email.Email',
            'delivery'=>'php' //debug|php
  		),

        'image' => array(
            'class'=>'ext.image.CImageComponent',
            // GD or ImageMagick
            'driver'=>'GD',
            // ImageMagick setup path
            //'params'=>array('directory'=>"C:\ImageMagick\\"),
        ),

        'clientScript' => array(
            'class' => 'ext.minify.EClientScript',
            'combineScriptFiles' => false,
            'combineCssFiles' => false,
            'optimizeCssFiles' => false,
            'optimizeScriptFiles' => false
        ),
        'assetManager'=>array(
			'class'=>'ext.EAssetManager.EAssetManager',
			'lessCompile'=>true,
			'lessCompiledPath'=>'webroot.assets.css',
			'lessFormatter'=>'compressed',
			'lessForceCompile'=>false,
		),		
	),

	'params'=>array(
		'adaptiveTemplate'=>false,
        'month' => true,
		'adminEmail'            => 'okkel@kontur-agency.ru',
        'menu_limit'            => 5,
        'news_limit'            => 7,
        'posts_limit'           => 10,
        'hide_news'             => false,
        'tmb_height'            => 380,
        'tmb_width'             => 0,
        'max_image_width'       => 800,
        'dev_year'              => 2016,
        'subcategories'         => true,
	),

    'language'=>'ru',
);
 
