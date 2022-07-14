<?php

namespace common\models;


use Yii;
use yii\db\ActiveRecord;
use common\models\TelegramBotUser;
use yii\behaviors\TimestampBehavior;
use common\behaviors\CacheInvalidateBehavior;

/**
 * This is the model class for table "text_block".
 *
 * @property integer $id
 * @property string $key
 * @property string $title
 * @property string $body
 * @property integer $status
 */
class WidgetText extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT  = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%widget_text}}';
    }

    /**
     * @return array statuses list
     */
    public static function statuses()
    {
        return [
            self::STATUS_DRAFT => Yii::t('common', 'Draft'),
            self::STATUS_ACTIVE => Yii::t('common', 'Active'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            'cacheInvalidate' => [
                'class' => CacheInvalidateBehavior::class,
                'cacheComponent' => 'frontendCache',
                'keys' => [
                    function ($model) {
                        return [
                            self::class,
                            $model->key
                        ];
                    }
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'title', 'body', 'language'], 'required'],
            [['body', 'language'], 'string'],
            [['status'], 'integer'],
            [['title', 'key'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'key' => Yii::t('common', 'Key'),
            'title' => Yii::t('common', 'Title'),
            'body' => Yii::t('common', 'Body'),
            'status' => Yii::t('common', 'Active'),
        ];
    }

    public static function getText($key, $user_id = false)
    {
        ($user_id) ? $language = TelegramBotUser::userLanguage($user_id) : $language = Yii::$app->language;

        if ($model = self::find()->where(["key" => $key, "language" => $language])->one()) {
            return $model->body;
        } else {
            if ($model = self::find()->where(["key" => $key])->one()) {
                return $model->body;
            }
        }
        return false;
    }

}
