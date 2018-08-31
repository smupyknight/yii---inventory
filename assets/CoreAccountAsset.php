<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Djava UI <support@djavaui.com>
 */
class CoreAccountAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'bower_components/bootstrap/dist/css/bootstrap.min.css',
    ];
    public $js = [ //'bower_components/jquery/dist/jquery.min.js',
        'bower_components/jquery-cookie/jquery.cookie.js',
        'bower_components/bootstrap/dist/js/bootstrap.min.js',
        'bower_components/jpreloader/js/jpreloader.min.js',
        'bower_components/jquery-easing-original/jquery.easing.1.3.min.js',
        'bower_components/ionsound/js/ion.sound.min.js',
    ];

}
