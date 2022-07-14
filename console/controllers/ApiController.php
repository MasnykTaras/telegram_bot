<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\ApiManager;

class ApiController extends Controller
{

    // php console/yii api/get-currencies
    public function actionGetCurrencies()
    {
        $data_string = json_encode(['jsonrpc' => '2.0', 'method' => 'currency.all', 'id' => 1]);
        $model       = new ApiManager();
        $result      = $model->checkCurrencies($model->sendRequest($data_string));
        print_r($result);
    }

    // php console/yii api/get-directions
    public function actionGetDirections()
    {
        $data_string = json_encode(['jsonrpc' => '2.0', 'method' => 'direction.rates', 'id' => 1]);
        $model       = new ApiManager();
        $result      = $model->checkDirections($model->sendRequest($data_string));
        print_r($result);
    }

    // php console/yii api/get-directions-rate
    public function actionGetDirectionsRate()
    {
        $data_string = json_encode(['jsonrpc' => '2.0', 'method' => 'direction.rates', 'id' => 1]);
        $model       = new ApiManager();
        $result      = $model->checkDirectionsRate($model->sendRequest($data_string));
        print_r($result);
    }

    // php console/yii api/get-orders-info
    public function actionGetOrdersInfo()
    {
        $model = new ApiManager();
        $result = $model->checkOrdersInfo();
        print_r($result);
    }

}
