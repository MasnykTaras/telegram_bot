<?php

namespace backend\components\exts;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

class BackendController extends Controller
{

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

}
