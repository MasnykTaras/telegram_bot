<?php

namespace backend\models;

use yii\base\Model;
use common\components\telegram\components\models\RequestHelper;

class MessageForm extends Model
{

    public $id;
    public $message;

    public function rules(): array
    {
        return [
            [['id', 'message'], 'required'],
            [['id'], 'integer'],
            [['message'], 'string']
        ];
    }
    public function sendMessage()
    {
        return RequestHelper::sendSpamRequest($this->id, $this->message);
    }

}
