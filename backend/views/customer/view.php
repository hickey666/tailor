<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\assets\CustomerAsset;

/* @var $this yii\web\View */
/* @var $model backend\models\Customer */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '客户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
CustomerAsset::register($this);

?>
<div class="customer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::button('删除', [
            'class' => 'btn btn-danger',
            'id' => 'delete',
            'data' => [
                'id' => $model->id,
            ]
        ])
        ?>
        <?= Html::a('返回列表', ['index'], ['class' => 'btn btn-primary']) ?>
    </p>

    <h3>个人信息：</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><?= $model->getAttributeLabel('name') ?></th>
            <th><?= $model->getAttributeLabel('phone') ?></th>
            <th><?= $model->getAttributeLabel('phone_bak') ?></th>
            <th><?= $model->getAttributeLabel('height') ?></th>
            <th><?= $model->getAttributeLabel('weight') ?></th>
            <th><?= $model->getAttributeLabel('feature') ?></th>
            <th><?= $model->getAttributeLabel('bak') ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?= $model->name ?></td>
            <td><?= $model->phone ?></td>
            <td><?= $model->phone_bak ?></td>
            <td><?= $model->height ?></td>
            <td><?= $model->weight ?></td>
            <td><?= $model->feature ?></td>
            <td><?= $model->bak ?></td>
        </tr>
        </tbody>
    </table>
    <br>
    <h3>衣服：</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><?= $model->getAttributeLabel('clothing_length') ?></th>
            <th><?= $model->getAttributeLabel('shoulder_width') ?></th>
            <th><?= $model->getAttributeLabel('sleeve_lenght') ?></th>
            <th><?= $model->getAttributeLabel('arm') ?></th>
            <th><?= $model->getAttributeLabel('bust') ?></th>
            <th><?= $model->getAttributeLabel('waistline') ?></th>
            <th><?= $model->getAttributeLabel('swing_around') ?></th>
            <th><?= $model->getAttributeLabel('upper_waist_section') ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?= $model->clothing_length ?></td>
            <td><?= $model->shoulder_width ?></td>
            <td><?= $model->sleeve_lenght ?></td>
            <td><?= $model->arm ?></td>
            <td><?= $model->bust ?></td>
            <td><?= $model->waistline ?></td>
            <td><?= $model->swing_around ?></td>
            <td><?= $model->upper_waist_section ?></td>
        </tr>
        </tbody>
    </table>
    <br>
    <h3>裤子：</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><?= $model->getAttributeLabel('pants_length') ?></th>
            <th><?= $model->getAttributeLabel('pants_up_waist') ?></th>
            <th><?= $model->getAttributeLabel('pants_down_waist') ?></th>
            <th><?= $model->getAttributeLabel('pants_hip') ?></th>
            <th><?= $model->getAttributeLabel('straight') ?></th>
            <th><?= $model->getAttributeLabel('thigh') ?></th>
            <th><?= $model->getAttributeLabel('mid_range') ?></th>
            <th><?= $model->getAttributeLabel('lower_leg') ?></th>
            <th><?= $model->getAttributeLabel('trousers') ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?= $model->pants_length ?></td>
            <td><?= $model->pants_up_waist ?></td>
            <td><?= $model->pants_down_waist ?></td>
            <td><?= $model->pants_hip ?></td>
            <td><?= $model->straight ?></td>
            <td><?= $model->thigh ?></td>
            <td><?= $model->mid_range ?></td>
            <td><?= $model->lower_leg ?></td>
            <td><?= $model->trousers ?></td>
        </tr>
        </tbody>
    </table>
    <br>
    <h3>裙子：</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><?= $model->getAttributeLabel('skirt_length') ?></th>
            <th><?= $model->getAttributeLabel('skirt_up_waist') ?></th>
            <th><?= $model->getAttributeLabel('skirt_down_waist') ?></th>
            <th><?= $model->getAttributeLabel('skirt_hip') ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?= $model->skirt_length ?></td>
            <td><?= $model->skirt_up_waist ?></td>
            <td><?= $model->skirt_down_waist ?></td>
            <td><?= $model->skirt_hip ?></td>
        </tr>
        </tbody>
    </table>
    <input type="hidden" name="_csrf" id='csrf' value="<?= Yii::$app->request->csrfToken ?>">

</div>
