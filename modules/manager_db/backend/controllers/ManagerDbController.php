<?php
namespace modules\manager_db\backend\controllers;

use Yii;
use yii\base\DynamicModel;
use backend\components\exts\BackendController;
use modules\manager_db\common\models\ExportParser;
use modules\manager_db\common\models\ImportParser;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ManagerDbController extends BackendController
{
  
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionExport()
    {
//        $tables = Yii::$app->db->schema->getTableNames();
        $tables = ['black_list'];
        $model  = new DynamicModel(['table_id']);
        $model->addRule(['table_id'], 'integer')->addRule(['table_id'], 'required');
        $json   = null;
        if ($model->load(Yii::$app->request->post())) {
            $parser = new ExportParser($tables, $model);
            $json   = $parser->run();
        }
        return $this->render('export', ['tables' => $tables, 'model' => $model, 'json' => $json]);
    }

    public function actionImport()
    {
//        $tables = Yii::$app->db->schema->getTableNames();
        $tables = ['black_list'];
        $model = new DynamicModel(['table_id', 'json']);
        $model->addRule(['table_id'], 'integer')->addRule(['json'], 'string')->addRule(['table_id', 'json'], 'required');
        $result = null;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $parser = new ImportParser($tables, $model);
            $result = $parser->run();
        }
        return $this->render('import', ['tables' => $tables, 'model' => $model, 'result' => $result]);
    }

}
