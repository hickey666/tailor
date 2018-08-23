<?php
namespace backend\controllers;

use yii\web\Controller;

/**
 * Site controller
 */
class ErrorsController extends Controller
{
    public $layout = false;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}
