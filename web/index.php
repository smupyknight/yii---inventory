<?php

if(true==true) {
	defined('YII_DEBUG') or define('YII_DEBUG', true);
	defined('YII_ENV') or define('YII_ENV', 'dev');

	// Development
	define('YII_ENABLE_ERROR_HANDLER', false);
	define('YII_ENABLE_EXCEPTION_HANDLER', false);
	error_reporting(E_ALL ^ E_NOTICE);
} elseif($_SERVER['REMOTE_ADDR']=="195.168.77.18") {
	defined('YII_DEBUG') or define('YII_DEBUG', true);
	defined('YII_ENV') or define('YII_ENV', 'prod');
	error_reporting(0);
} else {
	defined('YII_ENV') or define('YII_ENV', 'prod');
	error_reporting(0);
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
