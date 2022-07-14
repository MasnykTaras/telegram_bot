<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%whitelist}}".
 *
 * @property int $id
 * @property string $ip
 * @property string $name
 * @property int $type
 */
class Whitelist extends \yii\db\ActiveRecord
{
    const TYPE_ADMIN = 1;
    const TYPE_RATES_API = 2;

    public static function types()
    {
        return [
            self::TYPE_ADMIN => Yii::t('common', 'Admin'),
            self::TYPE_RATES_API => Yii::t('common', 'Frontent Api Rates'),

        ];
    }

    public function getTypeLabel()
    {
        $types = self::types();
        return isset($types[$this->type]) ? $types[$this->type] : $this->type;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%whitelist}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip'], 'required'],
            [['ip', 'name'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
            'name' => 'Name',
        ];
    }

    public function beforeValidate()
    {
//        if($this->scenario == 'createUpdateScenario')
//        if (filter_var($this->ip, FILTER_VALIDATE_IP, YII_ENV == 'prod' ? FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE : FILTER_FLAG_NO_PRIV_RANGE) === false) {
//            $this->addError('ip', 'Wrong IP format');
//            return false;
//        }

        return parent::beforeValidate();
    }

    public static function hasIp($ip)
    {
        if ((Whitelist::find()->where(['ip' => $ip])->one())) {
            return true;
        }
        return false;
    }

    public static function addIp($ip, $name)
    {
        $model = new Whitelist();
        $model->ip = $ip;
        $model->name = $name;
        return $model->save();
    }

    public static function getAccessType($ip){
        if(($model = Whitelist::find()->where(['ip' => $ip])->one())){
            return $model->type;
        }
        return false;
    }
}
