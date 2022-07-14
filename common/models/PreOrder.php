<?php

namespace common\models;

use Yii;
use common\models\Email;
use common\models\Card;
use common\models\Currency;
use yii\db\ActiveRecord;
use modules\statistic\common\models\OrdersCreatingStatistic;

/**
 * This is the model class for table "pre_order".
 *
 * @property int $id
 * @property int $user_id
 * @property int $direction_id
 * @property string $main_email
 * @property string $sell_amount
 * @property string $sell_wallet
 * @property string $sell_card_first_name
 * @property string $sell_card_last_name
 * @property string $sell_phone
 * @property string $buy_phone
 * @property string $buy_wallet
 * @property string $buy_card_first_name
 * @property string $buy_card_middle_name
 * @property string $buy_card_last_name
 */
class PreOrder extends ActiveRecord
{

    const STATUS_CANCELED    = -1;
    const STATUS_DONE = 0;
    const STATUS_INPROGRESS  = 1;
    const STATUS_PAUSE       = 2;
    const DEFAULT_DELAY_TIME = 300;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pre_order';
    }
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class'      => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'chat_id', 'direction_id', 'status', 'conversation_id'], 'integer'],
            [['status'], 'default', 'value' => self::STATUS_INPROGRESS],
            [['sell_amount', 'rate'], 'number'],
            [['user_ip'], 'string', 'max' => 24],
            [['main_email', 'sell_wallet', 'sell_card_first_name', 'sell_card_last_name', 'sell_phone', 'buy_phone', 'buy_wallet', 'buy_card_first_name', 'buy_card_middle_name', 'buy_card_last_name', 'created_at', 'updated_at'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                   => Yii::t('common', 'ID'),
            'user_id'              => Yii::t('common', 'User ID'),
            'chat_id'              => Yii::t('common', 'Chat ID'),
            'direction_id'         => Yii::t('common', 'Direction ID'),
            'conversation_id'      => Yii::t('common', 'Conversation ID'),
            'main_email'           => Yii::t('common', 'Main Email'),
            'rate'                 => Yii::t('common', 'Rate'),
            'sell_amount'          => Yii::t('common', 'Sell Amount'),
            'sell_wallet'          => Yii::t('common', 'Sell Wallet'),
            'sell_card_first_name' => Yii::t('common', 'Sell Card First Name'),
            'sell_card_last_name'  => Yii::t('common', 'Sell Card Last Name'),
            'sell_phone'           => Yii::t('common', 'Sell Phone'),
            'buy_wallet'           => Yii::t('common', 'Buy Wallet'),
            'buy_card_first_name'  => Yii::t('common', 'Buy Card First Name'),
            'buy_card_middle_name' => Yii::t('common', 'Buy Card Middle Name'),
            'buy_card_last_name'   => Yii::t('common', 'Buy Card Last Name'),
            'status'               => Yii::t('common', 'Status'),
            'created_at'           => Yii::t('common', 'Created At'),
            'updated_at'           => Yii::t('common', 'Updated At'),
        ];
    }

    public function getDirection()
    {
        return $this->hasOne(Direction::className(), ['id' => 'direction_id']);
    }
    public function getFields()
    {
        return json_decode($this->direction->direction_fields);
    }

    public function getNextField($currentName = false)
    {
        $answer = [
            'result' => false,
            'answer' => ''
        ];
        if (empty($this->user_ip) && $this->direction->sellCurrency->ip_validation) {
            $answer['result'] = true;
            $answer['answer'] = Yii::t('telegram', 'To make a transfer in this direction enter the IP from which you will pay. Write your IP in the format') . ' * 0.0.0.0 * ' . PHP_EOL;
        } else {
            if ($fields = $this->fields) {
                foreach ($fields as $key => $field) {
                    $name = $field->name;
                    if (is_null($this->$name) || empty($this->$name)) {
                        if ($name == 'main_email' || $currentName == 'main_email') {
                            $answer['result'] = true;
                            $answer['answer'] = Yii::t('telegram', 'Write your Email in the format') . " \"example@mail.com\"" . PHP_EOL;
                            break;
                        }
                        if ($name == 'sell_wallet' || $currentName == 'sell_wallet') {
                            $answer['result'] = true;
                            $answer['answer'] = hex2bin('F09F919B') . ' ' . Yii::t('telegram', 'Write your sell address in the format') . " *\"" . $this->direction->sellCurrency->placeholder . "\"*" . PHP_EOL;
                            break;
                        }
                        if ($name == 'buy_wallet' || $currentName == 'buy_wallet') {
                            $answer['result'] = true;
                            $answer['answer'] = hex2bin('F09F919B') . ' ' . Yii::t('telegram', 'Write your buy address in the format') . " *\"" . $this->direction->buyCurrency->placeholder . "\"*" . PHP_EOL;
                            break;
                        }

                        if ($name == 'buy_phone' || $name == 'sell_phone' || $currentName == 'buy_phone' || $currentName == 'sell_phone') {

                            $answer['result'] = true;
                            $answer['answer'] = hex2bin('E2988E') . ' ' . Yii::t('telegram', 'Write your {phone} in the format', ['phone' => Yii::t('common', $field->label)]) . " *\"+380837684350\"*" . PHP_EOL;

                            break;
                        }
                        if ($currentName) {
                            $answer['result'] = true;
                            $answer['answer'] = Yii::t('telegram', 'Write your {params}', ['params' => Yii::t('common', $this->getAttributeLabel($currentName))]) . PHP_EOL;
                            break;
                        }
                        $answer['result'] = true;
                        $answer['answer'] = Yii::t('telegram', 'Write your {params}', ['params' => Yii::t('common', $field->label)]) . PHP_EOL;
                        break;
                    }
                }
            }
        }
        return (object) $answer;
    }
    

    public function formFilling($value)
    {

        $result = [
            'next'    => false,
            'answer' => false,
            'field'  => false,
        ];
        if ($fields = $this->fields) {

            if (empty($this->user_ip) && $this->direction->sellCurrency->ip_validation) {
                $name = 'user_ip';
                if (filter_var($value, FILTER_VALIDATE_IP)) {
                    $result['next'] = true;
                } else {
                    $result['next']   = false;
                    $result['field']  = $name;
                    $result['answer'] = hex2bin('E29D97') . Yii::t('telegram', 'Invalid IP') . hex2bin('E29D97') . PHP_EOL;
                }
            } else {
                foreach ($fields as $key => $field) {
                    $name = $field->name;
                    if (is_null($this->$name) || empty($this->$name)) {

                        if ($name == 'main_email') {
                            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                if ($this->direction->sellCurrency->email_validation || ($this->direction->buyCurrency->code == Currency::EXMO_CODE)) {
                                    if (!Email::checkingEmail($value, $this->user_id)) {
                                        OrdersCreatingStatistic::updateDayCounters(['email_verify_start' => 1]);
                                        $result['next']   = true;
                                        $result['answer'] = hex2bin('E29D97') . Yii::t('telegram', 'A letter has been sent to you. Follow the instructions in it to verify your Email.') . PHP_EOL;
                                        $result['answer'] .= hex2bin('E280BC') . Yii::t('telegram', 'You will get a message when the Email will be confirmed.');
                                        $result['answer'] .= hex2bin('E29AA0') . Yii::t('telegram', 'You can continue to fill out the form, but you must have a registered Email to complete the order.') . PHP_EOL;
                                        $result['answer'] .= hex2bin('E2AC87') . PHP_EOL;
                                        break;
                                    }
                                }
                                OrdersCreatingStatistic::updateDayCounters(['email' => 1]);
                                $result['next'] = true;
                                break;
                            }
                            $result['answer'] = hex2bin('E29D97') . Yii::t('telegram', 'Invalid Email Address') . hex2bin('E29D97') . PHP_EOL;
                            $result['field']  = $name;
                            break;
                        } else if ($name == 'sell_wallet') {
                            if (preg_match($this->direction->sellCurrency->regular, $value)) {
                                if ($this->direction->sellCurrency->card_validation) {
                                    if (!Card::checkingCard($value, $this->user_id, $this->direction->sellCurrency->code)) {
                                        $result['next']   = true;
                                        $result['answer'] = hex2bin('E29D97') . Yii::t('telegram', 'Please verify your card. To more info write comand {comand}', ['comand' => '/verify']) . PHP_EOL;
                                        $result['answer'] .= hex2bin('E280BC') . Yii::t('telegram', 'You will get a message when the card will be confirmed.');
                                        $result['answer'] .= hex2bin('E29AA0') . Yii::t('telegram', 'You can continue to fill out the form, but you must have a registered card to complete the order.') . PHP_EOL;
                                        $result['answer'] .= hex2bin('E2AC87') . PHP_EOL;
                                        break;
                                    }
                                }
                                $result['field'] = $name;
                                $result['next']  = true;
                                break;
                            }
                            $result['field']  = $name;
                            $result['answer'] = hex2bin('E29D97') . Yii::t('telegram', 'Invalid Wallet Address') . hex2bin('E29D97') . PHP_EOL;
                            break;
                        } else if ($name == 'buy_wallet') {
                            if (!isset($this->direction->buyCurrency->regular) && $this->direction->buyCurrency->regular == null) {
                                $result['next']   = false;
                                $result['answer'] = Yii::t('telegram', 'Sorry, something wrong please try later.') . PHP_EOL;
                                break;
                            }
                            if (preg_match($this->direction->buyCurrency->regular, $value)) {
                                $result['next'] = true;
                                break;
                            }
                            $result['field']  = $name;
                            $result['answer'] = hex2bin('E29D97') . Yii::t('telegram', 'Invalid Wallet Address') . hex2bin('E29D97') . PHP_EOL;
                            break;
                        } else if ($name == 'buy_phone' || $name == 'sell_phone') {
                            if (preg_match('/^\+[0-9]{9,13}/', $value)) {
                                $result['next'] = true;
                                break;
                            }
                            $result['next']   = false;
                            $result['field']  = $name;
                            $result['answer'] = hex2bin('E29D97') . Yii::t('telegram', 'Invalid Phone Number') . hex2bin('E29D97') . PHP_EOL;
                            break;
                        } else {
                            if (ctype_alpha($value)) {
                                $result['next'] = true;
                                break;
                            }
                            $result['field']  = $name;
                            $result['answer'] = hex2bin('E29D97') . Yii::t('telegram', 'Invalid {param}', ['param' => $this->getAttributeLabel($name)]) . hex2bin('E29D97') . PHP_EOL;
                            break;
                        }
                    }
                }
            }
            if ($result['next']) {
                $this->$name = $value;
                if (!$this->save()) {
                    $result['next']    = false;
                    $result['answer'] = Yii::t('telegram', 'Sorry, something wrong please try later.') . PHP_EOL;
                }
            }
        }
        return (object) $result;
    }
    public function createPreOrderInfo()
    {
        $valid   = true;
        $message = Yii::t('telegram', 'Now let\'s check that we are doing everything right!') . PHP_EOL . Yii::t('telegram', 'Exchange will be made:') . PHP_EOL;
        $message .= Yii::t('telegram', 'Сourse may differ from the current') . PHP_EOL;
        $message .= Yii::t('telegram', 'According to the course') . ' ' . $this->direction->shortInfo($this->user_id) . PHP_EOL;
        if ($this->main_email) {
            if (($this->direction->sellCurrency->email_validation || ($this->direction->buyCurrency->code == Currency::EXMO_CODE)) && !Email::isEmailConfirm($this->main_email)) {
                $message .= hex2bin('F09F9AAB') . Yii::t('telegram', 'Email Not validated') . PHP_EOL;
            }
            $message .= Yii::t('telegram', 'Email') . ' ' . $this->main_email . PHP_EOL;
        }
        if ($this->user_ip) {
            $message .= Yii::t('telegram', 'IP') . ' ' . $this->user_ip . PHP_EOL;
        }
        $message .= hex2bin('E2AC85') . Yii::t('telegram', 'You give:') . ' ' . $this->sell_amount . ' ' . $this->direction->sellCurrency->name . PHP_EOL;
        $message .= hex2bin('E29EA1') . Yii::t('telegram', 'You get:') . ' ' . $this->direction->recalculateAmount($this->sell_amount, false, $this->direction->getCurrentRate($this->user_id), false) . ' ' . $this->direction->buyCurrency->name . '(' . $this->direction->buyCurrency->code . ')' . PHP_EOL;

        if ($this->sell_wallet) {
            if ($this->direction->sellCurrency->card_validation && !Card::isCardConfirm($this->sell_wallet)) {
                $message .= hex2bin('F09F9AAB') . Yii::t('telegram', 'Card Not validated') . ' ';
            }
            $message .= Yii::t('telegram', 'Account / Wallet Number') . ' ' . $this->sell_wallet . ' ' . PHP_EOL;
        }
        if ($this->buy_wallet) {
            
            $message .= Yii::t('telegram', 'To the account:') . ' ' . $this->buy_wallet . ' ' . PHP_EOL;
        }
        if ($this->sell_card_first_name) {
            $message .= Yii::t('telegram', 'Sell Card First Name') . ' ' . $this->sell_card_first_name . ' ' . PHP_EOL;
        }
        if ($this->sell_card_last_name) {
            $message .= Yii::t('telegram', 'Sell Card Last Name') . ' ' . $this->sell_card_last_name . ' ' . PHP_EOL;
        }
        if ($this->buy_card_first_name) {
            $message .= Yii::t('telegram', 'Buy Card First Name') . ' ' . $this->buy_card_first_name . ' ' . PHP_EOL;
        }
        if ($this->buy_card_middle_name) {
            $message .= Yii::t('telegram', 'Buy Card Middle Name') . ' ' . $this->buy_card_middle_name . ' ' . PHP_EOL;
        }
        if ($this->buy_card_last_name) {
            $message .= Yii::t('telegram', 'Buy Card Last Name') . ' ' . $this->buy_card_last_name . ' ' . PHP_EOL;
        }
        if ($this->sell_phone) {
            $message .= Yii::t('telegram', 'Phone:') . ' ' . $this->sell_phone . ' ' . PHP_EOL;
        }
        if ($this->buy_phone) {
            $message .= Yii::t('telegram', 'Phone:') . ' ' . $this->buy_phone . ' ' . PHP_EOL;
        }
        if ($this->direction->sellCurrency->card_validation && !Card::isCardConfirm($this->sell_wallet)) {
            $message .= hex2bin('E29AA0') . Yii::t('telegram', 'Please, wait for when the card will be validated or fill the new card.');
            $valid   = false;
        }
        if ($this->direction->sellCurrency->email_validation && !Email::isEmailConfirm($this->main_email)) {
            $message .= hex2bin('E29AA0') . Yii::t('telegram', 'Please, wait for when the email will be validated or fill the new email.');
            $valid   = false;
        }
        if ($valid) {
            $message .= Yii::t('telegram', 'If everything is correct, click Accepted') . PHP_EOL;
        }
        return $message;
    }
    public function getFillingValue()
    {
        $answer = Yii::t('telegram', 'You already filled fields') . PHP_EOL;
        $answer .= Yii::t('telegram', 'Direction') . ' ' . $this->direction->directionFullName . PHP_EOL;
        $fields = $this->fields;
        foreach ($fields as $field) {
            $name = $field->name;
            if (!is_null($this->$name) && !empty($this->$name)) {
                $answer .= $this->getAttributeLabel($name) . ' ' . $this->$name . PHP_EOL;
            }
        }
        $answer .= hex2bin('E2AC87') . PHP_EOL;
        return $answer;
    }
    public static function pausePreOrder($user_id, $chat_id)
    {
        if ($model = self::find()->where(['user_id' => $user_id, 'chat_id' => $chat_id, 'status' => self::STATUS_INPROGRESS])->one()) {
            $model->status = self::STATUS_PAUSE;
            $model->save();
        }
    }

    public function updateStatusToProgress()
    {
        $this->status = self::STATUS_INPROGRESS;
        $this->save();
    }

}
