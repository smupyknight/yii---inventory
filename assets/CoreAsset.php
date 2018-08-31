<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Djava UI <support@djavaui.com>
 */
class CoreAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
       // 'bower_components/bootstrap/dist/css/bootstrap.min.css',
    ];
    public $js = [ //'bower_components/jquery/dist/jquery.min.js',
        'bower_components/jquery-cookie/jquery.cookie.js',
        //'bower_components/bootstrap/dist/js/bootstrap.min.js',
        'bower_components/typehead.js/dist/handlebars.js',
        'bower_components/typehead.js/dist/typeahead.bundle.min.js',
        'bower_components/jquery-nicescroll/jquery.nicescroll.min.js',
        'bower_components/jquery.sparkline.min/index.js',
        'bower_components/jquery-easing-original/jquery.easing.1.3.min.js',
        'bower_components/ionsound/js/ion.sound.min.js',
        'bower_components/bootbox/bootbox.js',
		'js/custom.js'
    ];
    
	public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
	
    public $depends = [
        'app\assets\IE9Asset',
		'yii\web\YiiAsset',
    ];

}
