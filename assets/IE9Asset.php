<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Djava UI <support@djavaui.com>
 */
class IE9Asset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = ['condition' => 'lte IE9','position' => \yii\web\View::POS_HEAD];
    public $css = [
    ];
    public $js = [ 'bower_components/html5shiv/dist/html5shiv.min.js',
        'bower_components/respond-minmax/src/respond.js',
    ];

}
