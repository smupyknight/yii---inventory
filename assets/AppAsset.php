<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/reset.css',
		'css/layout.css',
        'css/components.css',
        'css/plugins.css',
        'css/yii-custom.css',
        'css/themes/default.theme.css',
        'css/custom.css',
		'bower_components/fontawesome/css/font-awesome.min.css',
        'bower_components/animate.css/animate.min.css',
        'bower_components/dropzone/downloads/css/dropzone.css',
        'bower_components/jquery.gritter/css/jquery.gritter.css'
    ];
    public $js = [
		'js/apps.js',
        'js/pages/blankon.dashboard.js',
        'js/demo.js',
		'js/blankon.form.element.js'
    ];
	public $jsOptions = array(
    	//'position' => \yii\web\View::POS_HEAD
	);
    public $depends = [
	'\app\assets\CoreAsset',
       //'yii\web\YiiAsset',
       // 'yii\bootstrap\BootstrapAsset',
    ];
}
