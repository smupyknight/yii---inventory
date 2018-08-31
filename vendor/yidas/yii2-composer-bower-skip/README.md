Yii2-Composer-Bower-skip
=====

A composer package that allows you to install or update yii2 without bower-asset.

After requiring this composer, Bower packages will not be updated, so you need to manually update Bower-Asset if needed.

---

INSTALLATION
-----

In yii2 `composer.json`, require `yidas/yii2-composer-bower-skip` before `yiisoft/yii2`.

Example json:
```
"require": {
    "php": ">=5.4.0",
    "yidas/yii2-composer-bower-skip": "~2.0.0",
    "yiisoft/yii2": "~2.0.5",
    "yiisoft/yii2-bootstrap": "~2.0.0"
}
```

After that, you can do the `composer update` without bower-asset.

---

## How to use when installing Yii2 via Composer

If you did not install yii from an Archive File but installed via Composer, we don't recommend you to globally require `yii2-composer-bower-skip` before create-project.

Instead, you can install Yii2 by using [yidas/yii2-app-light](https://github.com/yidas/yii2-app-light) package:

    php composer.phar create-project yidas/yii2-app-light

Above package includes yii2 framework with `yii2-composer-bower-skip` requiring in `composer.json` file already.

---

## How to install & update bower-asset via Composer 

Thanks to [Asset-Packagist](https://asset-packagist.org/), you can now install Yii2 smoothly with [new version](https://github.com/yiisoft/yii2-app-basic/commit/fc2ec7dfee9313288171e2fe8a5b80e22c1e1509).
