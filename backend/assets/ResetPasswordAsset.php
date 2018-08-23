<?php

namespace backend\assets;

/**
 * Main backend application asset bundle.
 */
class ResetPasswordAsset extends AppAsset
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'assets/js/user/getcode.js',
        'assets/js/user/resetpassword.js',
    ];
    public $depends = [
        'backend\assets\AppAsset',
    ];
}
