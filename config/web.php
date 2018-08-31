<?php
use \yii\web\Request;
$params = require(__DIR__ . '/params.php');
require(__DIR__.'/messages.php');
require(__DIR__ . '/configuration.php');

$baseUrl = str_replace('/web', '', (new Request)->getBaseUrl());
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
	'layout' => 'lay-admin',
    'modules' => [
                       'gridview' =>  [
                            'class' => '\kartik\grid\Module',
                            'downloadAction' => 'export',
                        ]
        ],
    'components' => [

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'n2O2aEwo-r3td2TszQE7i8C3YxtQ5nZF',
			'baseUrl' => $baseUrl,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
		'session' => [
            'name' => 'PHPRODESESSID',
            //'savePath' => sys_get_temp_dir(),
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
			'loginUrl' => [ 'site/login' ],
		 ],
        'errorHandler' => [
            'errorAction' => 'site/error',
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
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
			'transport' => [
				'class' => 'Swift_SmtpTransport',
				'host' => 'smtp.rodeonlinesurveys.co.za',//'smtp.gmail.com:465', //smtp.minamooweb.com
				'username' => 'noreplay@rodeonlinesurveys.co.za',//'a2ztester06@gmail.com', //minamooweb@minamooweb.com //contact@minamooweb.com
				'password' => 'NoR3pLy123$', //VKHVmFtB6kNx6cnKC //Min@m0oWeb#
				'port' => '587', //587
				'encryption' => 'tls',
          ],
            'useFileTransport' => false,
        ],*/
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
		'urlManager' => [
			'class' => 'yii\web\UrlManager',
			'showScriptName' => false,
		    'enablePrettyUrl' => true,
			'baseUrl' => $baseUrl,
           // 'scriptUrl' =>(new Request)->getBaseUrl(),
			'rules' => [
                //'admin/question/add/<id:[0-9]+>/' => 'admin/surveys/addquestion',
				//'admin/question/update/<id:[0-9]+>' =>'admin/surveys/updatequestion',
                'admin/surveys/calculations/<id:[0-9]+>/<quarter:\d{4}:\d{1}>/<qid:[0-9]+>' => 'admin/surveys/calculations',
                'admin/reports/individualcontribution/<id:[0-9]+>/<quarter:\d{4}:\d{1}>/<node_id:[0-9]+>' => 'admin/reports/individualcontribution',
                'admin/<controller:outputtables>/<action:index>/<questid:[0-9]+>/<quart:\d{4}:\d{1}>' => 'admin/<controller>/<action>',
				'admin/<controller:surveys>/<action:questiondetails|outputtables|addquestion|updatequestion>/<id:[0-9]+>/<qid:\d{4}:\d{1}>' => 'admin/<controller>/<action>',
                'admin/<controller:surveys|reports>/<action>/<id:[0-9]+>/<quarter:\d{4}:\d{1}>' => 'admin/<controller>/<action>',
                'admin/surveys/calculations/<id:[0-9]+>/<quarter:\d{4}:\d{1}>/<qid:[0-9]+>' => 'admin/surveys/calculations',
                'admin/output_tables/<action>/<id:[0-9]+>/<quarter:\d{4}:\d{1}>' => 'admin/outputtables/<action>',
                'admin/users/index/<company_id:[0-9]+>/' => 'admin/users/index',
                'admin/<controller:surveys|question>/audit_trail/<id:[0-9]+>/' => 'admin/surveys/audit_trail',
                'admin/surveys/contributors/<id:[0-9]+>/<quarter_id:[0-9]+>' => 'admin/surveys/contributors',
                'admin/<controller:surveys>/<action>/<id:[0-9]+>/<quarter:\d{4}:\d{1}>' => 'admin/<controller>/<action>',
                'admin/reports/togglestatus/<id:[0-9]+>/<status:[0-9]+>' => 'admin/reports/togglestatus',

                'admin/<controller>/<action>/<id:[0-9]+>' => 'admin/<controller>/<action>',
                'admin/childnodes/<parent:[0-9]+>/' => 'admin/nodes/childnodes',
				'admin/childnodes/updateposition' => 'admin/nodes/updateposition',
				'admin/updateposition' => 'admin/nodes/updateposition',
				'admin/nodes/createchild/<prop:[0-9]+>/<parent:[0-9]+>/' => 'admin/nodes/createchild',
				'admin/editprofile' => 'admin/users/editprofile',
				'admin/changepassword' => 'admin/users/changepassword',
                'admin/surveys/contribution/<quarter_id:[0-9]+>/<filter_id:[0-9]+>/<node_id:[0-9]+>' => 'admin/surveys/contribution',
				//'admin/<controller>/<action>/<id:[0-9]+>/' => 'admin/<controller>/<action>',
				//'admin/<controller:\w+>/<action:\w+>/<id:\d+>' => 'admin/<controller>/<action>',
				'admin/surveys/contributors/<id:[0-9]+>/sendnotificationmail' => 'admin/surveys/sendnotificationmail',
				'admin/surveys/nodecategories/deletenode' => 'admin/surveys/deletenode',
                'admin/surveys/audit_trail/viewchanges' => 'admin/surveys/viewchanges',
                'admin/question/audit_trail/viewchanges' => 'admin/surveys/viewchanges',
				//'admin/question/details/changecapturelevel' => 'admin/surveys/changecapturelevel',
				//'admin/question/details/deletecolumn' => 'admin/surveys/deletecolumn',
				//'admin/question/details/updateorder' => 'admin/surveys/updateorder',
				'admin/surveys/questiondetails/<id:[0-9]+>/<action>' => 'admin/surveys/<action>',
                'admin' => 'admin/dashboard',
                'contributor' => 'contributor/surveys/index',
                'contributor/questions/answer/<id:[0-9]+>/<quarter_id:[0-9]+>'=> 'contributor/questions/answer',
                'contributor/<controller>/<action:index>/<id:[0-9]+>' => 'contributor/<controller>/<action>',
                 // 'contributor/<controller:questions>/<action>/<id:[0-9]+>/<quarter_id:[0-9]+>' => 'contributor/<controller>/<action>',



			],
		 ],
		 'customcomponent' => [
           'class' => 'app\components\Customcomponent',
        ],
        'db' => require(__DIR__ . '/db.php'),
		'assetManager' => [
        'bundles' => [
            'yii\bootstrap\BootstrapAsset' => [
                 //'js' => [],//to remove default bootstrap.js of yii
			],
             'yii\web\JqueryAsset' => [
                //'js'=>[]
            ],
        ],
    ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
