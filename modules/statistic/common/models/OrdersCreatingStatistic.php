<?php

namespace modules\statistic\common\models;

use Yii;

/**
 * This is the model class for table "orders_creating_statistic".
 *
 * @property int $id
 * @property int|null $type
 * @property int|null $start
 * @property int|null $completed
 * @property int|null $email
 * @property int|null $email_verify_start
 * @property int|null $email_verify_end
 * @property int|null $card
 * @property int|null $card_verify_start
 * @property int|null $card_verify_end
 * @property int|null $canceled_preorder
 * @property int|null $canceled_continued
 */
class OrdersCreatingStatistic extends \yii\db\ActiveRecord
{

    const TYPE_DAY      = 1;
    const TYPE_MONTH    = 2;
    const TYPE_FULL_TIME = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders_creating_statistic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'start', 'completed', 'email', 'email_verify_start', 'email_verify_end', 'card', 'card_verify_start', 'card_verify_end', 'canceled_preorder', 'canceled_continued'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'type' => Yii::t('common', 'Type'),
            'start' => Yii::t('common', 'Start'),
            'completed' => Yii::t('common', 'Completed'),
            'email' => Yii::t('common', 'Email'),
            'email_verify_start' => Yii::t('common', 'Email Verify Start'),
            'email_verify_end' => Yii::t('common', 'Email Verify End'),
            'card' => Yii::t('common', 'Card'),
            'card_verify_start' => Yii::t('common', 'Card Verify Start'),
            'card_verify_end' => Yii::t('common', 'Card Verify End'),
            'canceled_preorder' => Yii::t('common', 'Canceled Preorder'),
            'canceled_continued' => Yii::t('common', 'Canceled Continued'),
        ];
    }

    public static function updateDayCounters($colums)
    {
        $model = self::find()->where(['type' => self::TYPE_DAY])->one();
        if ($model && is_array($colums)) {
            return $model->updateCounters($colums);
        }
        return false;
    }
    public function updateMonthStatistic()
    {
        $day   = self::find()->where(['type' => self::TYPE_DAY])->one();
        $month = self::find()->where(['type' => self::TYPE_MONTH])->one();
        
        $this->updateNewCounters($month, $day);
        $this->resetCounters($day);
    }
    public function updateFullTimeStatistic()
    {
        $month    = self::find()->where(['type' => self::TYPE_MONTH])->one();
        $fullTime = self::find()->where(['type' => self::TYPE_FULL_TIME])->one();
        $this->updateNewCounters($fullTime, $month);
        $this->resetCounters($month);
    }
    public function updateNewCounters($model, $counters)
    {
        $model->updateCounters([
            'start'              => $counters->start,
            'completed'          => $counters->completed,
            'email'              => $counters->email,
            'email_verify_start' => $counters->email_verify_start,
            'email_verify_end'   => $counters->email_verify_end,
            'card'               => $counters->card,
            'card_verify_start'  => $counters->card_verify_start,
            'card_verify_end'    => $counters->card_verify_end,
            'canceled_preorder'  => $counters->canceled_preorder,
            'canceled_continued' => $counters->canceled_continued,
        ]);
    }

    public function resetCounters($model)
    {
        $model->start              = 0;
        $model->completed          = 0;
        $model->email              = 0;
        $model->email_verify_start = 0;
        $model->email_verify_end   = 0;
        $model->card               = 0;
        $model->card_verify_start  = 0;
        $model->card_verify_end    = 0;
        $model->canceled_preorder  = 0;
        $model->canceled_continued = 0;
        $model->save();
    }

}
