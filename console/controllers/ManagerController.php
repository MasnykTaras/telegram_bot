<?php
namespace console\controllers;

use common\models\Order;
use common\models\PreOrder;
use yii\console\Controller;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ManagerController extends Controller
{

    // php console/yii api/clear-
    public function actionClearPreOrder()
    {
        PreOrder::deleteAll(['and', ['status' => [PreOrder::STATUS_DONE, PreOrder::STATUS_CANCELED]], ['<=', 'updated_at', time() + 3600 * 12]]);
    }
    public function actionCheckOrder()
    {
        Order::checkPendingOrder();
    }
    
}
