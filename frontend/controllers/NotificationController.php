<?php

namespace frontend\controllers;

use Yii;
use yii\console\Controller;
use yii\web\NotFoundHttpException;
use common\models\ApiManager;
use yii\filters\VerbFilter;
use common\components\helpers\NetificationHelper;

class NotificationController extends Controller
{

    public $apiManager;
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'request' => ['POST'],
                ],
            ],
        ];
    }
    
    public function beforeAction($action)
    {

        if (Yii::$app->request->userIP !== env('ALLOWABLE_API')) {

            throw new NotFoundHttpException('Not Found');
        }
        return parent::beforeAction($action);
    }

    //curl -X POST "http://zibit.net/notification/request" -H "Content-Type: application/json" -H "cache-control: no-cache" -d '{"email":{"value":"test@test.com","status":"Confirmed"},"notification_type":"email_verification"}'
    //curl -X POST "http://zibit.net/notification/request" -H "Content-Type: application/json" -H "cache-control: no-cache" -d '{"card":{"code":"SBERRUB", "number":"1234123412341234","status":"Confirmed"}, "notification_type":"card_verification"}'
    //curl -X POST "http://zibit.net/notification/request" -H "Content-Type: application/json" -H "cache-control: no-cache" -d '{"orderHash":"LrePhia-IP","status":"canceled","sell_amount":"70.00000000","buy_amount":"0.96920034","rate":72.22449,"recalculated":false,"notification_type":"order_status"}'

    public function actionRequest()
    {
        $apiManager = new ApiManager();
        $data = file_get_contents('php://input');

        if (!$data) {
            if (!($data = Yii::$app->request->post())) {
                if (!($data = Yii::$app->request->get())) {
                    throw new HttpException(400, 'Bad Request');
                }
            }
        }
       
        $apiManager->logRequest($data);
        $result = json_decode($data);

        if (NetificationHelper::checkingSignature(Yii::$app->request->headers, $result)) {
            if ($result->notification_type == 'card_verification') {
                $result = $apiManager->updateCardStatus($result);
            } else if ($result->notification_type == 'order_status') {
                $result = $apiManager->updateStatus($result);
            } else if ($result->notification_type == 'email_verification') {
                $result = $apiManager->updateEmailStatus($result);
            } else if ($result->notification_type == 'user_discount') {
                $result = $apiManager->updateUserDiscount($result);
            }
            echo json_encode(["status" => 200]);
        } else {
            echo json_encode(["status" => 400]);
            Yii::warning('Invalid type of request', 'api_request_answer');
        }
    }


}
