<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PreOrder;

/**
 * PreOrderSearch represents the model behind the search form of `common\models\PreOrder`.
 */
class PreOrderSearch extends PreOrder
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'direction_id', 'status', 'chat_id'], 'integer'],
            [['main_email', 'sell_amount', 'sell_wallet', 'sell_card_first_name', 'sell_card_last_name', 'sell_phone', 'buy_wallet', 'buy_card_first_name', 'buy_card_middle_name', 'buy_card_last_name', 'created_at', 'updated_at', 'buy_phone'], 'safe'],
            [['rate'], 'number'],
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
        $query = PreOrder::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes'   => ['id']
            ]
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
            'user_id' => $this->user_id,
            'direction_id' => $this->direction_id,
            'status' => $this->status,
            'rate' => $this->rate,
            'chat_id' => $this->chat_id,
        ]);

        $query->andFilterWhere(['like', 'main_email', $this->main_email])
            ->andFilterWhere(['like', 'sell_amount', $this->sell_amount])
            ->andFilterWhere(['like', 'sell_wallet', $this->sell_wallet])
            ->andFilterWhere(['like', 'sell_card_first_name', $this->sell_card_first_name])
            ->andFilterWhere(['like', 'sell_card_last_name', $this->sell_card_last_name])
            ->andFilterWhere(['like', 'sell_phone', $this->sell_phone])
            ->andFilterWhere(['like', 'buy_wallet', $this->buy_wallet])
            ->andFilterWhere(['like', 'buy_card_first_name', $this->buy_card_first_name])
            ->andFilterWhere(['like', 'buy_card_middle_name', $this->buy_card_middle_name])
            ->andFilterWhere(['like', 'buy_card_last_name', $this->buy_card_last_name])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at])
            ->andFilterWhere(['like', 'buy_phone', $this->buy_phone]);

        return $dataProvider;
    }
}
