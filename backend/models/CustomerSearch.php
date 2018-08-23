<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Customer;

/**
 * CustomerSearch represents the model behind the search form of `backend\models\Customer`.
 */
class CustomerSearch extends Customer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['name', 'phone', 'phone_bak', 'height', 'weight', 'feature', 'clothing_length', 'shoulder_width', 'sleeve_lenght', 'arm', 'bust', 'waistline', 'swing_around', 'upper_waist_section', 'pants_length', 'pants_up_waist', 'pants_down_waist', 'pants_hip', 'straight', 'thigh', 'mid_range', 'lower_leg', 'trousers', 'skirt_length', 'skirt_up_waist', 'skirt_down_waist', 'skirt_hip', 'bak'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Customer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'phone_bak', $this->phone_bak])
            ->andFilterWhere(['like', 'height', $this->height])
            ->andFilterWhere(['like', 'weight', $this->weight])
            ->andFilterWhere(['like', 'feature', $this->feature])
            ->andFilterWhere(['like', 'clothing_length', $this->clothing_length])
            ->andFilterWhere(['like', 'shoulder_width', $this->shoulder_width])
            ->andFilterWhere(['like', 'sleeve_lenght', $this->sleeve_lenght])
            ->andFilterWhere(['like', 'arm', $this->arm])
            ->andFilterWhere(['like', 'bust', $this->bust])
            ->andFilterWhere(['like', 'waistline', $this->waistline])
            ->andFilterWhere(['like', 'swing_around', $this->swing_around])
            ->andFilterWhere(['like', 'upper_waist_section', $this->upper_waist_section])
            ->andFilterWhere(['like', 'pants_length', $this->pants_length])
            ->andFilterWhere(['like', 'pants_up_waist', $this->pants_up_waist])
            ->andFilterWhere(['like', 'pants_down_waist', $this->pants_down_waist])
            ->andFilterWhere(['like', 'pants_hip', $this->pants_hip])
            ->andFilterWhere(['like', 'straight', $this->straight])
            ->andFilterWhere(['like', 'thigh', $this->thigh])
            ->andFilterWhere(['like', 'mid_range', $this->mid_range])
            ->andFilterWhere(['like', 'lower_leg', $this->lower_leg])
            ->andFilterWhere(['like', 'trousers', $this->trousers])
            ->andFilterWhere(['like', 'skirt_length', $this->skirt_length])
            ->andFilterWhere(['like', 'skirt_up_waist', $this->skirt_up_waist])
            ->andFilterWhere(['like', 'skirt_down_waist', $this->skirt_down_waist])
            ->andFilterWhere(['like', 'skirt_hip', $this->skirt_hip])
            ->andFilterWhere(['like', 'bak', $this->bak]);

        return $dataProvider;
    }
}
