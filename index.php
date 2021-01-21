<?php
require_once(dirname(__FILE__).'/protected/components/helpers/HKontur.php');
HKontur::robots();

mb_internal_encoding('utf-8');

define('DS', DIRECTORY_SEPARATOR);

error_reporting(0);


if(is_file(dirname(__FILE__) . DS . 'local.index.php')) {
	include('local.index.php'); 
	die;
}

$yii = '../../yii/yii.php';

if (!is_file($yii))
    $yii = dirname(__FILE__).'/../yii/yii.php';

if (!is_file($yii))
    die('Framework not found!');

define('D_MODE_LOCAL', (strpos($_SERVER['SERVER_NAME'], 'local') !== false));

if(D_MODE_LOCAL) defined('YII_DEBUG') or define('YII_DEBUG',true);

defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

$config = dirname(__FILE__).'/protected/config/main.php';

require_once($yii);

Yii::createWebApplication($config)->run();
