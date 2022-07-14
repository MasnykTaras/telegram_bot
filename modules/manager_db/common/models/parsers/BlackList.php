<?php
namespace modules\manager_db\common\models\parsers;

use Yii;
use common\models\Currency;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class BlackList
{

    public $tableName;

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
    }

    public function export()
    {
       
        return json_encode($this->substitutionCurrencyID($this->readTable()));
    }
    public function readTable()
    {
        return Yii::$app->db->createCommand('SELECT * FROM ' . $this->tableName)->queryAll();
    }

    public function substitutionCurrencyID($lists)
    {
        if (is_array($lists)) {
            $newList = [];
            foreach ($lists as $list) {
                $newList[] = array_replace($list, ['currency_id' => $this->getCurrencyCode($list['currency_id'])]);
            }
            return $newList;
        }
        return false;
    }

    public function getCurrencyCode($currency_id)
    {
        if ($model = Currency::find()->where(['id' => $currency_id])->one()) {
            return $model->code;
        }
        return null;
    }
    public function import($json)
    {
        return $this->addNewColumn($this->substitutionCurrencyCode(json_decode($json, true)));
    }

    public function substitutionCurrencyCode($lists)
    {
        if (is_array($lists)) {
            $newList = [];
            foreach ($lists as $list) {
                $newList[] = array_replace($list, ['id' => null, 'currency_id' => $this->getCurrencyID($list['currency_id'])]);
            }
            return $newList;
        }
        return false;
    }

    public function getCurrencyID($code)
    {
        if ($model = Currency::find()->where(['code' => $code])->one()) {
            return $model->id;
        }
        return null;
    }

    public function addNewColumn($data)
    {
        if ($data) {
            $column = array_keys($data[0]);
            $db     = Yii::$app->db;
            $update = [];
            foreach ($column as $field) {
                $update[] = $field . '=VALUES(' . $field . ')';
            }
            $update = implode(',', $update);

            $sql    = $db->queryBuilder->batchInsert($this->tableName, $column, $data);
            return $db->createCommand($sql . ' ON DUPLICATE KEY UPDATE ' . $update)->execute();
        }
        return false;
    }

}
