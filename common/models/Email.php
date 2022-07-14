<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\components\telegram\components\models\RequestHelper;

/**
 * This is the model class for table "email".
 *
 * @property int $id
 * @property string $email
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property int $user_id
 */
class Email extends \yii\db\ActiveRecord
{
    const EMAIL_STATUS_NEW       = 0;
    const EMAIL_STATUS_PENDING   = 1;
    const EMAIL_STATUS_CONFIRMED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'email';
    }
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (isset($changedAttributes['status'])) {
            RequestHelper::sendEmailRequest($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['status', 'user_id'], 'integer'],
            [['email', 'created_at', 'updated_at'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['status'], 'default', 'value' => self::EMAIL_STATUS_NEW]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'email' => Yii::t('common', 'Email'),
            'status' => Yii::t('common', 'Status'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'user_id' => Yii::t('common', 'User ID'),
        ];
    }
    public static function getNewCount()
    {
        return count(self::find()->where(['status' => self::EMAIL_STATUS_NEW])->all());
    }

    public static function emailStatus()
    {
        return [
            self::EMAIL_STATUS_NEW       => Yii::t('common', 'New'),
            self::EMAIL_STATUS_PENDING   => Yii::t('common', 'Pending'),
            self::EMAIL_STATUS_CONFIRMED => Yii::t('common', 'Confirmed'),
        ];
    }

    public function getStatusName()
    {
        $status = self::emailStatus();
        return (isset($status[$this->status])) ? $status[$this->status] : 'none';
    }

    public function getStatusValue($name)
    {
        $status = [
            'new'       => self::EMAIL_STATUS_NEW,
            'pending'   => self::EMAIL_STATUS_PENDING,
            'confirmed' => self::EMAIL_STATUS_CONFIRMED
        ];
        $name   = strtolower($name);
        if (isset($status[$name])) {
            return $status[$name];
        }
        return false;
    }

    public static function addNewEmail($email, $user_id)
    {
        $model          = new Email();
        $model->email   = $email;
        $model->user_id = $user_id;

        $result = $model->sendApiRequest($email, $user_id);

        if ($result) {
            if ($model->getStatusValue($result->email->status) || $model->getStatusValue($result->email->status) == 0) {
                $model->status = $model->getStatusValue($result->email->status);
            }
        }
        if ($model->save()) {
            return $model;
        }
        return false;
    }

    private function sendApiRequest($email)
    {
        $ApiManager = new ApiManager($this->user_id);
        $result     = $ApiManager->createEmailValidate($email);

        if (isset($result->result)) {
            if ($ApiManager->validateEmailRequest($result->result)) {
                return $result->result;
            }
        }
        return false;
    }

    public static function isEmailConfirm($email)
    {
        return self::find()->where(['email' => $email, 'status' => self::EMAIL_STATUS_CONFIRMED])->one();
    }

    public static function checkingEmail($email, $user_id)
    {
        $model = self::find()->where(['email' => $email])->one();
        if ($model) {
            if ($model->status != self::EMAIL_STATUS_CONFIRMED) {
                $model->updateEmailStatus($model->sendApiRequest($model->email));
            }
        } else {
            $model = self::addNewEmail($email, $user_id);
            if (!$model) {
                return false;
            }
        }
        if ($model->status == self::EMAIL_STATUS_CONFIRMED) {
            return true;
        }
        return false;
    }

    public function updateEmailStatus($result)
    {
        if ($this->getStatusValue($result->email->status)) {
            $this->status = $this->getStatusValue($result->email->status);
            return true;
        }
        return false;
    }

}
