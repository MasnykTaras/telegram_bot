<?php
namespace modules\manager_db\common\models;
use Yii;
use common\models\KeyStorageItem;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ExportParser
{
    public $tables;
    public $tableID;
    public $tableName;
    public $path = 'modules/manager_db/common/models/parsers/';
    public $key  = 'last_export_';

    public function __construct($tables, $model)
    {
        $this->tables    = $tables;
        $this->tableID   = $model->table_id;
        $this->tableName = $this->findTableName();
    }

    public function run()
    {
        if ($this->tableName && $this->tableExist()) {
            if ($parser = $this->parserClass()) {
               
                $model = new $parser($this->tableName);
                if ($result = $model->export()) {
                    return $result;
                }
            }
        }
    }

    public function findTableName()
    {
        return (isset($this->tables[$this->tableID])) ? $this->tables[$this->tableID] : false;
    }

    public function tableExist()
    {
        return (Yii::$app->db->schema->getTableSchema($this->tableName)) ? true : false;
    }
    public function parserClass()
    {
        $part = explode('_', $this->tableName);
       
        $part = array_map(function($value) {
            return ucfirst($value);
        }, $part);
        $className = implode($part);

        if (file_exists(Yii::getAlias('@base') . '/' . $this->path . $className . '.php')) {
            return "modules\manager_db\common\models\parsers\\" . $className;
        }
    }
    
}
