<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Currency;

/**
 * CurrencySearch represents the model behind the search form of `common\models\Currency`.
 */
class CurrencySearch extends Currency
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'apply_commission_on_sell', 'apply_commission_on_buy', 'status', 'parent_id', 'type'], 'integer'],
            [['name', 'code', 'symbol', 'placeholder', 'regular', 'iso_code', 'card_number', 'sell_fields', 'buy_fields'], 'safe'],
            [['reserve', 'buy_commission', 'sell_commission'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Currency::find();

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
            'reserve' => $this->reserve,
            'buy_commission' => $this->buy_commission,
            'sell_commission' => $this->sell_commission,
            'apply_commission_on_sell' => $this->apply_commission_on_sell,
            'apply_commission_on_buy' => $this->apply_commission_on_buy,
            'status' => $this->status,
            'parent_id' => $this->parent_id,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'symbol', $this->symbol])
            ->andFilterWhere(['like', 'placeholder', $this->placeholder])
            ->andFilterWhere(['like', 'regular', $this->regular])
            ->andFilterWhere(['like', 'iso_code', $this->iso_code])
            ->andFilterWhere(['like', 'card_number', $this->card_number])
            ->andFilterWhere(['like', 'sell_fields', $this->sell_fields])
            ->andFilterWhere(['like', 'buy_fields', $this->buy_fields]);

        return $dataProvider;
    }
}
