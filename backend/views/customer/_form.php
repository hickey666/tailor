<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-form">

    <?php $form = ActiveForm::begin(); ?>
    <h3>个人信息：</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'phone_bak')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'height')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'feature')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'bak')->textInput(['maxlength' => true]) ?></th>
        </tr>
        </thead>
    </table>
    <br>
    <h3>衣服：</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><?= $form->field($model, 'clothing_length')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'shoulder_width')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'sleeve_lenght')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'arm')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'bust')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'waistline')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'swing_around')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'upper_waist_section')->textInput(['maxlength' => true]) ?></th>
        </tr>
        </thead>
    </table>
    <br>
    <h3>裤子：</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><?= $form->field($model, 'pants_length')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'pants_up_waist')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'pants_down_waist')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'pants_hip')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'straight')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'thigh')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'mid_range')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'lower_leg')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'trousers')->textInput(['maxlength' => true]) ?></th>
        </tr>
        </thead>
    </table>
    <br>
    <h3>裙子：</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><?= $form->field($model, 'skirt_length')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'skirt_up_waist')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'skirt_down_waist')->textInput(['maxlength' => true]) ?></th>
            <th><?= $form->field($model, 'skirt_hip')->textInput(['maxlength' => true]) ?></th>
        </tr>
        </thead>
    </table>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        <?= Html::a('返回列表', ['index'], ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
