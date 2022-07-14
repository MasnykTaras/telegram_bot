<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Direction;
use common\models\Currency;
use yii\helpers\ArrayHelper;

/**
 * DirectionSearch represents the model behind the search form of `common\models\Direction`.
 */
class DirectionSearch extends Direction
{

    public $sell_currency_name;
    public $buy_currency_name;
    public $main_currency_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sell_currency_id', 'buy_currency_id', 'main_currency', 'status'], 'integer'],
            [['rate', 'min_sell', 'min_buy', 'max_sell', 'max_buy'], 'number'],
            [['sell_currency_name', 'buy_currency_name', 'main_currency_name'], 'safe'],
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

    public function getMainCurrencyID()
    {
        if ($model = Currency::find()->andFilterWhere(['like', 'name', $this->main_currency_name])->all()) {
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
        $query = Direction::find();

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
            'sell_currency_id' => $this->sell_currency_id,
            'buy_currency_id' => $this->buy_currency_id,
            'rate' => $this->rate,
            'main_currency' => $this->main_currency,
            'status' => $this->status,
            'min_sell' => $this->min_sell,
            'min_buy' => $this->min_buy,
            'max_sell' => $this->max_sell,
            'max_buy' => $this->max_buy,
        ]);
          if (!is_null($this->sell_currency_name) && !empty($this->sell_currency_name)) {

            $query->andFilterWhere(['in', 'sell_currency_id', $this->sellCurrencyID]);
        }
        if (!is_null($this->buy_currency_name) && !empty($this->buy_currency_name)) {
            $query->andFilterWhere(['in', 'buy_currency_id', $this->buyCurrencyID]);
        }
        if (!is_null($this->main_currency_name) && !empty($this->main_currency_name)) {
            $query->andFilterWhere(['in', 'main_currency', $this->mainCurrencyID]);
        }
        return $dataProvider;
    }
}
