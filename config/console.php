<?php
use \yii\web\Request;
Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');
$baseUrl = str_replace('/web', '', (new Request)->getBaseUrl());

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
		'request' => [

            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
           // 'cookieValidationKey' => 'n2O2aEwo-r3td2TszQE7i8C3YxtQ5nZF',
            //'baseUrl' => (new Request)->getBaseUrl(),
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            //'showScriptName' => false,
            //'enablePrettyUrl' => true,
            'baseUrl' => $baseUrl,
            //'homeUrl' => (new Request)->getBaseUrl(),
            'scriptUrl' =>(new Request)->getBaseUrl(),
            ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    	'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        /*'mailer' => [
            'class' => 'boundstate\mailgun\Mailer',
            'key' => 'key-7ef231000b677af90dc9c3b7dc8bbe8f',
            'domain' => 'rodeonlinesurveys.co.za',
        ],
        /*'mailer' => [
            'class' => 'ustmaestro\sparkpost',
            'apiKey' => '5cb0b3d39d0c63970045864acc097bbb09223616',
            'viewPath' => '@app/mail',
            'defaultEmail' => 'info@rode.co.za', // optional if 'adminEmail' app param is specified or 'useDefaultEmail' is false
            'retryLimit' => 3, // optional
        ],
        /*'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
			'viewPath' => '@app/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
			'transport' => [
				'class' => 'Swift_SmtpTransport',
				'host' => 'smtp.rodeonlinesurveys.co.za',//'smtp.gmail.com:465', //smtp.minamooweb.com
				'username' => 'noreplay@rodeonlinesurveys.co.za',//'a2ztester06@gmail.com', //contact@minamooweb.com
				'password' => 'NoR3pLy123$', //U6Vts9Lu5JM5v8CzLcEt //Min@m0oWeb#
				'port' => '587', //587
				// 'encryption' => 'ssl',//'tls',
          ],
            'useFileTransport' => false,
        ],*/
        'db' => $db,
    ],
    'params' => $params,
];
