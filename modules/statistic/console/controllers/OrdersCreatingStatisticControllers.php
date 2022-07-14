<?php

namespace modules\statistic\console\controllers;


use yii\console\Controller;
use modules\statistic\common\models\OrdersCreatingStatistic;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class OrdersCreatingStatisticControllers extends Controller
{
    public function actionUpdateMonthStatistic()
    {
        $model = new OrdersCreatingStatistic();
        $model->updateMonthStatistic();
    }

    public function actionUpdateFullTimeStatistic()
    {
        $model = new OrdersCreatingStatistic();
        $model->updateFullTimeStatistic();
    }

}
