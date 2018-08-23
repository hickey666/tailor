<?php

use yii\helpers\Html;
use backend\assets\ResetPasswordAsset;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
ResetPasswordAsset::register($this);

$this->title = '修改密码: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = '修改密码';
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <form id="rest-password" onsubmit="return false">
        <div class="box-body">
            <div class="form-group">
                <label>用户名：</label>
                <input type="text" class="form-control" value="<?= $model->username?>" disabled>
            </div>
            <div class="form-group">
                <label>邮箱：</label>
                <input type="text" id="email" class="form-control" name="email" placeholder="请输入邮箱">
            </div>
            <div class="form-group">
                <label>新密码：</label>
                <input type="password" id="password" class="form-control" name="password" placeholder="请输入密码">
            </div>
            <div class="form-group">
                <label>确认密码：</label>
                <input type="password" id="repassword" class="form-control" name="repassword" placeholder="请再次输入密码">
            </div>
            <div class="form-group">
                <label>验证码：</label>
                <div class="input-group">
                    <input type="text"  id="captcha" class="form-control" name="captcha" placeholder="请输入验证码">
                    <span class="input-group-btn">
                    <button id="getcode" class="btn btn-default" type="button">获取验证码</button>
                </span>
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" name="id" value="<?= $model->id?>">
                <button type="button" id="submit" class="btn btn-primary">保存</button>
            </div>
        </div>
    </form>

</div>
<script>
    //定义url变量
    var getInjectedUrls = function () {
        return {
            sendCaptcha: '<?=Url::to('send-captcha') ?>',
            save: '<?=Url::to(['reset-password', 'id' => $model->id]) ?>',
        }
    }
</script>
