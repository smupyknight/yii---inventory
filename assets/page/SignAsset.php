<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets\page;

use yii\web\AssetBundle;

/**
 * @author Djava UI <support@djavaui.com>
 */
class SignAsset extends AssetBundle
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
                'css/sign.css',
                'css/custom.css',
				'bower_components/fontawesome/css/font-awesome.min.css',
       		    'bower_components/animate.css/animate.min.css'

            ];
    public $js = [
                    'js/pages/blankon.sign.js',
                    'js/demo.js',
    ];
    public $depends = [
        'app\assets\CoreAccountAsset',
        
    ];
}
