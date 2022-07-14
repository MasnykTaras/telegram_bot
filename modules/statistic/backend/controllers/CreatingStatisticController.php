<?php

namespace modules\statistic\backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\exts\BackendController;
use modules\statistic\common\models\OrdersCreatingStatistic;

/**
 * CardController implements the CRUD actions for Card model.
 */
class CreatingStatisticController extends BackendController
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Card models.
     * @return mixed
     */
    public function actionIndex()
    {
        $day      = OrdersCreatingStatistic::find()->where(['type' => OrdersCreatingStatistic::TYPE_DAY])->one();
        $month    = OrdersCreatingStatistic::find()->where(['type' => OrdersCreatingStatistic::TYPE_MONTH])->one();
        $fullTime = OrdersCreatingStatistic::find()->where(['type' => OrdersCreatingStatistic::TYPE_FULL_TIME])->one();
        return $this->render('index', [
                    'day'      => $day,
                    'month'    => $month,
                    'fullTime' => $fullTime,
        ]);
    }

}
