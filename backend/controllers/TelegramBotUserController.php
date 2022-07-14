<?php

namespace backend\controllers;

use Yii;
use common\models\TelegramBotUser;
use common\models\search\TelegramBotUserSearch;
use backend\models\MessageForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\exts\BackendController;

/**
 * TelegramBotUserController implements the CRUD actions for TelegramBotUser model.
 */
class TelegramBotUserController extends BackendController
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
     * Lists all TelegramBotUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TelegramBotUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $messageModel = new MessageForm();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'messageModel' => $messageModel
        ]);
    }

    /**
     * Displays a single TelegramBotUser model.
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
     * Creates a new TelegramBotUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TelegramBotUser();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TelegramBotUser model.
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
     * Deletes an existing TelegramBotUser model.
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
     * Finds the TelegramBotUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TelegramBotUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TelegramBotUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }

    public function actionSendMessage()
    {
        if (Yii::$app->request->post() && Yii::$app->request->isAjax) {
            $model = new MessageForm();

            if ($model->load(Yii::$app->request->post()) && $model->sendMessage()) {
                $message = Yii::t('frontend', 'Receipt send');
                Yii::info($message, 'reserve_notification');
            } else {
                $message = Yii::t('backend', 'Receipt  wasn\'t send');
                Yii::warning($message, 'reserve_notification');
            }
            return $this->renderAjax('parts/_send-message-form', ['model' => $model, 'message' => $message]);
        }
        return false;
    }

}
