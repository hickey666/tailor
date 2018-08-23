<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%customer}}".
 *
 * @property int $id
 * @property string $name 姓名
 * @property string $phone 手机号
 * @property string $phone_bak 备用手机号
 * @property string $height 身高
 * @property string $weight 体重
 * @property string $feature 特征
 * @property string $clothing_length 衣长
 * @property string $shoulder_width 肩宽
 * @property string $sleeve_lenght 袖长
 * @property string $arm 手臂
 * @property string $bust 胸围
 * @property string $waistline 腰围
 * @property string $swing_around 摆围
 * @property string $upper_waist_section 上腰节
 * @property string $pants_length 裤长
 * @property string $pants_up_waist 上腰（裤子）
 * @property string $pants_down_waist 下腰（裤子）
 * @property string $pants_hip 臀围（裤子）
 * @property string $straight 直裆
 * @property string $thigh 大腿
 * @property string $mid_range 中裆
 * @property string $lower_leg 小腿
 * @property string $trousers 裤口
 * @property string $skirt_length 裙长
 * @property string $skirt_up_waist 上腰（裙子）
 * @property string $skirt_down_waist 下腰（裙子）
 * @property string $skirt_hip 臀围（裙子）
 * @property string $bak 备注
 * @property int $status 0：删除, 1:未删除
 */
class Customer extends \yii\db\ActiveRecord
{
    protected $status = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            ['status', 'default', 'value' => 1],
            [['name', 'phone', 'phone_bak', 'height', 'weight', 'feature', 'clothing_length', 'shoulder_width', 'sleeve_lenght', 'arm', 'bust', 'waistline', 'swing_around', 'upper_waist_section', 'pants_length', 'pants_up_waist', 'pants_down_waist', 'pants_hip', 'straight', 'thigh', 'mid_range', 'lower_leg', 'trousers', 'skirt_length', 'skirt_up_waist', 'skirt_down_waist', 'skirt_hip', 'bak'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '姓名',
            'phone' => '手机号',
            'phone_bak' => '备用手机号',
            'height' => '身高',
            'weight' => '体重',
            'feature' => '特征',
            'clothing_length' => '衣长',
            'shoulder_width' => '肩宽',
            'sleeve_lenght' => '袖长',
            'arm' => '手臂',
            'bust' => '胸围',
            'waistline' => '腰围',
            'swing_around' => '摆围',
            'upper_waist_section' => '上腰节',
            'pants_length' => '裤长',
            'pants_up_waist' => '上腰',
            'pants_down_waist' => '下腰',
            'pants_hip' => '臀围',
            'straight' => '直裆',
            'thigh' => '大腿',
            'mid_range' => '中裆',
            'lower_leg' => '小腿',
            'trousers' => '裤口',
            'skirt_length' => '裙长',
            'skirt_up_waist' => '上腰',
            'skirt_down_waist' => '下腰',
            'skirt_hip' => '臀围',
            'bak' => '备注',
            'status' => '0：删除, 1:未删除',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    self::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => time(),
            ]
        ];
    }
}
