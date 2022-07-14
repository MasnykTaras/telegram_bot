<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form of `common\models\Order`.
 */
class OrderSearch extends Order
{

    public $sell_currency_name;
    public $buy_currency_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sell_currency_id', 'buy_currency_id', 'status', 'sub_status', 'user_id', 'direction_id'], 'integer'],
            [['hash', 'order_hash', 'sell_source', 'buy_target', 'payment_address', 'created_at', 'updated_at', 'main_email', 'user_ip'], 'safe'],
            [['sell_amount', 'buy_amount', 'rate', 'old_rate', 'init_sell_amount', 'init_buy_amount', 'our_buy_amount', 'our_sell_amount'], 'number'],
            [['sell_currency_name', 'buy_currency_name'], 'safe'],
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
    public function getSellCurrencyID()
    {
        if ($model = Currency::find()->andFilterWhere(['like', 'name', $this->sell_currency_name])->all()) {
            return ArrayHelper::getColumn($model, 'id');
        }
        return false;
    }

    public function getBuyCurrencyID()
    {
        if ($model = Currency::find()->andFilterWhere(['like', 'name', $this->buy_currency_name])->all()) {
            return ArrayHelper::getColumn($model, 'id');
        }
        return false;
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
        $query = Order::find();

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
            'sell_amount' => $this->sell_amount,
            'buy_amount' => $this->buy_amount,
            'rate' => $this->rate,
            'old_rate' => $this->old_rate,
            'sell_currency_id' => $this->sell_currency_id,
            'buy_currency_id' => $this->buy_currency_id,
            'status' => $this->status,
            'sub_status' => $this->sub_status,
            'user_id' => $this->user_id,
            'direction_id' => $this->direction_id,
            'init_sell_amount' => $this->init_sell_amount,
            'init_buy_amount' => $this->init_buy_amount,
            'our_buy_amount' => $this->our_buy_amount,
            'our_sell_amount' => $this->our_sell_amount,
        ]);

        $query->andFilterWhere(['like', 'hash', $this->hash])
            ->andFilterWhere(['like', 'order_hash', $this->order_hash])
                ->andFilterWhere(['like', 'sell_source', $this->sell_source])
                ->andFilterWhere(['like', 'buy_target', $this->buy_target])
            ->andFilterWhere(['like', 'payment_address', $this->payment_address])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at])
            ->andFilterWhere(['like', 'main_email', $this->main_email])
            ->andFilterWhere(['like', 'user_ip', $this->user_ip]);
         if (!is_null($this->sell_currency_name) && !empty($this->sell_currency_name)) {

            $query->andFilterWhere(['in', 'sell_currency_id', $this->sellCurrencyID]);
        }
        if (!is_null($this->buy_currency_name) && !empty($this->buy_currency_name)) {
            $query->andFilterWhere(['in', 'buy_currency_id', $this->buyCurrencyID]);
        }
        return $dataProvider;
    }
}
