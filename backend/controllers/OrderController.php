<?php

namespace backend\controllers;

use Yii;
use common\models\Order;
use common\models\search\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\ApiManager;
use backend\components\exts\BackendController;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends BackendController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }

    public function actionUpdateStatus()
    {
        if (Yii::$app->request->post() && Yii::$app->request->isAjax) {
            $atribut = ['status', 'sub_status'];
            if ($model   = $this->findModel(Yii::$app->request->post('id'))) {
                if (!is_null(Yii::$app->request->post('status'))) {
                    if (!is_null(Yii::$app->request->post('type'))) {
                        if (Yii::$app->request->post('status') == $model::ORDER_SRATUS_CANCELED && $model->sub_status == $model::ORDER_SUB_STATUS_RECEIVED) {
                            return false;
                        }
                        if (Yii::$app->request->post('status') == $model::ORDER_SRATUS_CANCELED) {
                            $api    = new ApiManager();
                            $result = $api->createOrderCancel($model->order_hash);
                            if (!$result) {
                                return false;
                            }
                        }

                        $attributeName         = ($atribut[Yii::$app->request->post('type')]);
                        $model->$attributeName = Yii::$app->request->post('status');

                        if ($model->save()) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }
    public function actionOrderInfo()
    {
      
        if (Yii::$app->request->post() && Yii::$app->request->isAjax) {
            
            if ($id = Yii::$app->request->post('id')) {
               
                if ($model = $this->findModel($id)) {
                    return $this->renderAjax('parts/_table-view', ['model' => $model]);
                }
            }
        }
        return false;
    }
    public function actionCheckOrderInfo()
    {
        if (Yii::$app->request->post() && Yii::$app->request->isAjax) {
            if ($order_hash = Yii::$app->request->post('order_hash')) {
               
                $api = new ApiManager();
                
                return $api->checkOrderInfo($order_hash);
            }
        }
    }

}
