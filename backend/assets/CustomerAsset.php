<?php

namespace backend\assets;

/**
 * Main backend application asset bundle.
 */
class CustomerAsset extends AppAsset
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'assets/js/customer/view.js',
    ];
    public $depends = [
        'backend\assets\AppAsset',
    ];
}
