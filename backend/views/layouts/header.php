<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">T</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="/assets/images/touxiang.png" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= Yii::$app->user->identity->username?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="/assets/images/touxiang.png" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <?= Yii::$app->user->identity->username?>
                                <small>上次登录时间：<?=date('Y-m-d H:i', Yii::$app->user->identity->last_login_time)?></small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(
                                    '修改密码',
                                    ['/user/reset-password?id=' . Yii::$app->user->identity->id],
                                    ['class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    '退出',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
